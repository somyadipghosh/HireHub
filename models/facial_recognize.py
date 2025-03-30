import cv2
import mediapipe as mp
from deepface import DeepFace
import numpy as np
from collections import deque, Counter

mp_face_detection = mp.solutions.face_detection
face_detection = mp_face_detection.FaceDetection(min_detection_confidence=0.5)

cap = cv2.VideoCapture(0)

# Smooth predictions
emotion_history = deque(maxlen=10)

while cap.isOpened():
    ret, frame = cap.read()
    if not ret:
        break

    rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
    results = face_detection.process(rgb_frame)

    if results.detections:
        for detection in results.detections:
            bboxC = detection.location_data.relative_bounding_box
            h_frame, w_frame, _ = frame.shape

            x = int(bboxC.xmin * w_frame)
            y = int(bboxC.ymin * h_frame)
            w = int(bboxC.width * w_frame)
            h = int(bboxC.height * h_frame)

            padding = 20
            x1 = max(0, x - padding)
            y1 = max(0, y - padding)
            x2 = min(w_frame, x + w + padding)
            y2 = min(h_frame, y + h + padding)

            face_roi = frame[y1:y2, x1:x2]

            # Resize face ROI
            if face_roi.size == 0:
                continue
            face_roi = cv2.resize(face_roi, (224, 224))

            try:
                analysis = DeepFace.analyze(
                    face_roi,
                    actions=['emotion'],
                    enforce_detection=False,
                    detector_backend='mediapipe'
                )
                emotion = analysis[0]['dominant_emotion']
                emotion_history.append(emotion)

                common_emotion = Counter(emotion_history).most_common(1)[0][0]

                cv2.rectangle(frame, (x1, y1), (x2, y2), (255, 0, 0), 2)
                cv2.putText(frame, common_emotion, (x1, y1 - 10), cv2.FONT_HERSHEY_SIMPLEX, 0.9, (255, 0, 0), 2)

            except Exception as e:
                print("Emotion analysis error:", e)

    cv2.imshow("Face Emotion Analysis", frame)

    if cv2.waitKey(1) & 0xFF == ord('q'):
        break

cap.release()
cv2.destroyAllWindows()
