import cv2
import numpy as np
import logging
from datetime import datetime
from collections import deque
import math
import websockets
import asyncio
import json
import time

logging.basicConfig(level=logging.INFO)

class GazeTracker:
    def __init__(self):
        self.face_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_frontalface_default.xml')
        self.eye_cascade = cv2.CascadeClassifier(cv2.data.haarcascades + 'haarcascade_eye.xml')
        self.cap = None
        self.suspicious_count = 0
        self.last_alert_time = datetime.now()
        self.alert_cooldown = 2  # seconds
        self.screen_bounds = None
        self.cheating_threshold = 10  # frames looking away before alert
        self.consecutive_suspicious = 0
        self.max_suspicious_frames = 30  # Need 30 consecutive suspicious frames
        self.both_eyes_threshold = True
        self.last_directions = []
        self.direction_history_size = 10
        self.min_pupil_size = 3
        self.max_pupil_size = 15
        self.confidence_threshold = 0.4  # Lowered threshold
        self.calibration_points = []

        # Setup blob detector for pupil detection
        params = cv2.SimpleBlobDetector_Params()
        params.minThreshold = 0
        params.maxThreshold = 255
        params.filterByArea = True
        params.minArea = 100
        params.maxArea = 2000
        params.filterByCircularity = True
        params.minCircularity = 0.1
        params.filterByConvexity = True
        params.minConvexity = 0.5
        params.filterByInertia = False
        self.blob_detector = cv2.SimpleBlobDetector_create(params)

        # Calibration and tracking parameters
        self.calibration_complete = False
        self.calibration_points = []
        self.center_points = {'left': None, 'right': None}
        self.movement_history = {'left': deque(maxlen=30), 'right': deque(maxlen=30)}
        self.baseline_established = False
        
        # Threshold parameters
        self.movement_threshold = 20  # pixels
        self.sustained_movement_frames = 15
        self.max_normal_angle = 35  # degrees
        self.suspicious_zones = {
            'left': (-60, -30),   # degrees
            'right': (30, 60),
            'up': (-90, -45)
        }

        # New tracking parameters
        self.pupil_history = {'left': deque(maxlen=60), 'right': deque(maxlen=60)}
        self.center_calibrated = False
        self.center_positions = {'left': None, 'right': None}
        self.last_eye_shape = None
        
        # Improved thresholds
        self.movement_threshold = 15
        self.cheating_duration = 1.5  # seconds
        self.frames_for_cheating = 45  # 1.5 seconds at 30fps
        self.suspicious_count = {'left': 0, 'right': 0}

        # Update calibration parameters
        self.calibration_frames_needed = 30
        self.calibration_points = {'left': [], 'right': []}

        self.websocket = None
        self.silent_mode = True  # Run without UI

    def set_screen_bounds(self, frame):
        height, width = frame.shape[:2]
        self.screen_bounds = {
            'left': width * 0.1,    # 10% from left
            'right': width * 0.9,   # 90% from left
            'top': height * 0.1,    # 10% from top
            'bottom': height * 0.8   # 80% from top
        }

    def preprocess_eye_region(self, eye_roi):
        if len(eye_roi.shape) > 2:
            eye_roi = cv2.cvtColor(eye_roi, cv2.COLOR_BGR2GRAY)
        
        # Increase contrast
        eye_roi = cv2.equalizeHist(eye_roi)
        
        # Reduce noise while preserving edges
        eye_roi = cv2.bilateralFilter(eye_roi, 5, 75, 75)
        
        # Sharpen the image
        kernel = np.array([[-1,-1,-1],
                         [-1, 9,-1],
                         [-1,-1,-1]])
        eye_roi = cv2.filter2D(eye_roi, -1, kernel)
        
        return eye_roi

    def calculate_movement_angle(self, eye_center, pupil_pos):
        dx = pupil_pos[0] - eye_center[0]
        dy = pupil_pos[1] - eye_center[1]
        angle = math.degrees(math.atan2(dy, dx))
        return angle

    def is_movement_suspicious(self, angle):
        for zone in self.suspicious_zones.values():
            if zone[0] <= angle <= zone[1]:
                return True
        return False

    def analyze_movement_pattern(self, eye_id, pupil_pos, eye_roi_shape):
        if not self.center_points[eye_id]:
            # Set center point during calibration
            self.center_points[eye_id] = (eye_roi_shape[1]//2, eye_roi_shape[0]//2)
            return False

        center = self.center_points[eye_id]
        self.movement_history[eye_id].append(pupil_pos)

        if len(self.movement_history[eye_id]) < self.sustained_movement_frames:
            return False

        # Calculate movement angle and distance
        angle = self.calculate_movement_angle(center, pupil_pos)
        distance = math.sqrt((pupil_pos[0] - center[0])**2 + (pupil_pos[1] - center[1])**2)

        # Analyze recent movement history
        recent_movements = list(self.movement_history[eye_id])
        consistent_movement = all(
            math.sqrt((pos[0] - center[0])**2 + (pos[1] - center[1])**2) > self.movement_threshold
            for pos in recent_movements[-self.sustained_movement_frames:]
        )

        if consistent_movement and self.is_movement_suspicious(angle):
            return True

        return False

    def detect_pupils(self, eye_roi, eye_id):
        if len(eye_roi.shape) > 2:
            eye_roi = cv2.cvtColor(eye_roi, cv2.COLOR_BGR2GRAY)
        
        # Enhanced preprocessing
        eye_roi = cv2.equalizeHist(eye_roi)
        eye_roi = cv2.GaussianBlur(eye_roi, (7, 7), 0)
        
        # Multi-threshold pupil detection
        _, thresh1 = cv2.threshold(eye_roi, 25, 255, cv2.THRESH_BINARY_INV)
        _, thresh2 = cv2.threshold(eye_roi, 50, 255, cv2.THRESH_BINARY_INV)
        thresh = cv2.bitwise_or(thresh1, thresh2)
        
        # Clean up noise
        kernel = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (3, 3))
        thresh = cv2.erode(thresh, kernel, iterations=2)
        thresh = cv2.dilate(thresh, kernel, iterations=1)
        
        contours, _ = cv2.findContours(thresh, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)
        
        if contours:
            largest = max(contours, key=cv2.contourArea)
            if cv2.contourArea(largest) > 50:  # Minimum pupil size
                M = cv2.moments(largest)
                if M["m00"] != 0:
                    cx = int(M["m10"] / M["m00"])
                    cy = int(M["m01"] / M["m00"])
                    
                    # Updated calibration logic
                    if not self.calibration_complete:
                        self.calibration_points[eye_id].append((cx, cy))
                        if all(len(points) >= self.calibration_frames_needed 
                              for points in self.calibration_points.values()):
                            self.complete_calibration()
                    
                    return (cx, cy, 1.0)  # High confidence for good detection
        return None

    def complete_calibration(self):
        """New method to handle calibration completion"""
        for eye_id in ['left', 'right']:
            points = self.calibration_points[eye_id]
            if points:
                # Calculate average position
                avg_x = sum(p[0] for p in points) / len(points)
                avg_y = sum(p[1] for p in points) / len(points)
                self.center_points[eye_id] = (int(avg_x), int(avg_y))
        
        self.calibration_complete = True
        logging.info("Calibration completed successfully")

    def calculate_center(self, eye_id):
        positions = list(self.pupil_history[eye_id])
        if positions:
            avg_x = sum(p[0] for p in positions) / len(positions)
            avg_y = sum(p[1] for p in positions) / len(positions)
            return (int(avg_x), int(avg_y))
        return None

    def check_gaze_direction(self, pupil_data, eye_roi_shape):
        if not pupil_data:
            return None, 0
        
        px, py, confidence = pupil_data
        height, width = eye_roi_shape[:2]
        
        # More lenient thresholds
        norm_x = (px - width/2) / (width/4)
        norm_y = (py - height/2) / (height/4)
        
        if confidence >= self.confidence_threshold:
            if norm_x < -0.3:
                return "left", confidence
            elif norm_x > 0.3:
                return "right", confidence
            elif norm_y < -0.3:
                return "up", confidence
            elif norm_y > 0.3:
                return "down", confidence
            return "center", confidence
        return "uncertain", confidence

    def detect_cheating(self, gaze_data):
        if not self.center_calibrated or not gaze_data:
            return False, None
        
        cheating_detected = False
        direction = None
        
        for eye_id, pupil_pos in gaze_data.items():
            if not pupil_pos or not self.center_positions[eye_id]:
                continue
                
            px, py = pupil_pos[:2]
            cx, cy = self.center_positions[eye_id]
            
            # Calculate displacement
            dx = px - cx
            dy = py - cy
            displacement = math.sqrt(dx*dx + dy*dy)
            
            # Calculate angle
            angle = math.degrees(math.atan2(dy, dx))
            
            # Check if movement is suspicious
            if displacement > self.movement_threshold:
                if abs(angle) > 30:  # Looking too far left/right
                    self.suspicious_count[eye_id] += 1
                elif angle < -20:  # Looking up
                    self.suspicious_count[eye_id] += 1
                else:
                    self.suspicious_count[eye_id] = max(0, self.suspicious_count[eye_id] - 1)
            
            # Both eyes must be suspicious for several frames
            if all(count > self.frames_for_cheating for count in self.suspicious_count.values()):
                cheating_detected = True
                direction = "horizontal" if abs(angle) > 45 else "vertical"
                # Reset counters
                self.suspicious_count = {'left': 0, 'right': 0}
        
        return cheating_detected, direction

    async def send_data(self, data):
        if self.websocket:
            await self.websocket.send(json.dumps(data))

    def detect_eyes(self, frame):
        if self.screen_bounds is None:
            self.set_screen_bounds(frame)

        gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
        faces = self.face_cascade.detectMultiScale(gray, 1.1, 5, minSize=(30, 30))
        
        for (x, y, w, h) in faces:
            roi_gray = gray[y:y+h//2, x:x+w]
            
            eyes = self.eye_cascade.detectMultiScale(roi_gray, 1.1, 5, minSize=(30, 30))
            gaze_data = {}
            gaze_directions = []  # Initialize here
            confidences = []      # Initialize here
            
            # Sort eyes by x-coordinate to distinguish left/right
            eyes = sorted(eyes, key=lambda e: e[0])[:2]
            
            if len(eyes) == 2:
                left_eye, right_eye = eyes
                
                for eye, is_left in [(left_eye, True), (right_eye, False)]:
                    ex, ey, ew, eh = eye
                    eye_roi = roi_gray[ey:ey+eh, ex:ex+ew]
                    eye_id = 'left' if is_left else 'right'
                    pupil_data = self.detect_pupils(eye_roi, eye_id)
                    
                    if pupil_data:
                        gaze_data[eye_id] = pupil_data
                        px, py, conf = pupil_data
                        
                        direction, conf = self.check_gaze_direction(pupil_data, eye_roi.shape)
                        if direction and direction != "uncertain":
                            gaze_directions.append(direction)
                            confidences.append(conf)
            
            # Show average confidence if we have data
            if confidences:
                avg_conf = sum(confidences) / len(confidences)
                
            if gaze_data:
                cheating_detected, direction = self.detect_cheating(gaze_data)
                if cheating_detected:
                    asyncio.create_task(self.send_data({
                        'type': 'cheating_alert',
                        'direction': direction,
                        'timestamp': time.time()
                    }))
        
        # Return data instead of annotated frame
        return {
            'gaze_directions': gaze_directions,
            'confidence': avg_conf if confidences else 0,
            'cheating_detected': cheating_detected
        }

    async def run_websocket(self):
        async with websockets.serve(self.handle_connection, "localhost", 8765):
            await asyncio.Future()  # run forever

    async def handle_connection(self, websocket, path):
        self.websocket = websocket
        try:
            self.cap = cv2.VideoCapture(0)
            while True:
                ret, frame = self.cap.read()
                if not ret:
                    break
                
                data = self.detect_eyes(frame)
                await self.send_data(data)
                await asyncio.sleep(0.1)  # Rate limit
        finally:
            self.cap.release()
            self.websocket = None

if __name__ == "__main__":
    tracker = GazeTracker()
    asyncio.run(tracker.run_websocket())