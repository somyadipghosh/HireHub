import cv2
import mediapipe as mp

# Initialize mediapipe face detection
mp_face_detection = mp.solutions.face_detectionq
mp_drawing = mp.solutions.drawing_utils

# Start webcam
cap = cv2.VideoCapture(0)

with mp_face_detection.FaceDetection(model_selection=0, min_detection_confidence=0.5) as face_detection:
    while cap.isOpened():
        success, image = cap.read()
        if not success:
            print("Ignoring empty camera frame.")
            continue

        # Convert the image color (optional but recommended)
        image_rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)

        # Process the image and detect faces
        results = face_detection.process(image_rgb)

        # Draw face detections
        if results.detections:
            for detection in results.detections:
                mp_drawing.draw_detection(image, detection)

        # Show the image
        cv2.imshow('MediaPipe Face Detection', image)
 # Exit on pressing 'q'
        if cv2.waitKey(1) & 0xFF == ord('q'):

            break

cap.release()
cv2.destroyAllWindows()
