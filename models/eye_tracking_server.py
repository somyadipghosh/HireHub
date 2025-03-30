import asyncio
import websockets
import json
import cv2
import mediapipe as mp
import numpy as np
import base64
import time

# Initialize MediaPipe Face Mesh
mp_face_mesh = mp.solutions.face_mesh
face_mesh = mp_face_mesh.FaceMesh(
    max_num_faces=1,
    refine_landmarks=True,
    min_detection_confidence=0.3,
    min_tracking_confidence=0.3
)

async def process_frame(websocket):
    async for message in websocket:
        try:
            # Decode received frame
            data = json.loads(message)
            image_data = data.get("frame", None)

            if image_data:
                image_bytes = base64.b64decode(image_data)
                np_arr = np.frombuffer(image_bytes, np.uint8)
                frame = cv2.imdecode(np_arr, cv2.IMREAD_COLOR)

                # Process the frame (Eye tracking)
                result = process_eye_tracking(frame)

                # Send tracking data back
                await websocket.send(json.dumps(result))

        except Exception as e:
            print(f"Error processing frame: {e}")

def process_eye_tracking(image):
    """Process the received frame and return eye tracking results."""
    rgb_image = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
    results = face_mesh.process(rgb_image)

    eye_data = {
        "left": "Center",
        "right": "Center",
        "state": "Open",
        "confidence": {"left": 1.0, "right": 1.0}
    }

    if results.multi_face_landmarks:
        face_landmarks = results.multi_face_landmarks[0]
        eye_data["left"], eye_data["confidence"]["left"] = detect_eye_direction(face_landmarks, LEFT_EYE, LEFT_IRIS, image)
        eye_data["right"], eye_data["confidence"]["right"] = detect_eye_direction(face_landmarks, RIGHT_EYE, RIGHT_IRIS, image)
        
        # Detect if both eyes are closed
        if eye_data["left"] == "Closed" and eye_data["right"] == "Closed":
            eye_data["state"] = "Closed"

    return {"data": eye_data, "timestamp": time.time(), "status": "active"}

# Define eye landmarks
LEFT_EYE = [362, 382, 381, 380, 374, 373, 390, 249, 263, 466, 388, 387, 386, 385, 384, 398]
RIGHT_EYE = [33, 7, 163, 144, 145, 153, 154, 155, 133, 173, 157, 158, 159, 160, 161, 246]
LEFT_IRIS = [474, 475, 476, 477]
RIGHT_IRIS = [469, 470, 471, 472]

def detect_eye_direction(face_landmarks, eye_points, iris_points, image):
    """Detect eye direction from landmarks."""
    iris_region = np.array([
        [face_landmarks.landmark[i].x * image.shape[1],
         face_landmarks.landmark[i].y * image.shape[0]]
        for i in iris_points
    ], dtype=np.float32)

    iris_center = np.mean(iris_region, axis=0)
    eye_left = min([face_landmarks.landmark[i].x * image.shape[1] for i in eye_points])
    eye_right = max([face_landmarks.landmark[i].x * image.shape[1] for i in eye_points])
    
    if eye_right - eye_left == 0:
        return "Center", 1.0

    gaze_ratio = (iris_center[0] - eye_left) / (eye_right - eye_left)

    if gaze_ratio < 0.4:
        return "Left", min(abs((0.4 - gaze_ratio) * 3), 1.0)
    elif gaze_ratio > 0.6:
        return "Right", min(abs((gaze_ratio - 0.6) * 3), 1.0)
    else:
        return "Center", 1.0

async def main():
    async with websockets.serve(process_frame, "localhost", 8766):
        print("WebSocket server started on ws://localhost:8766")
        await asyncio.Future()

if __name__ == "__main__":
    try:
        asyncio.run(main())
    except KeyboardInterrupt:
        print("Shutting down...")