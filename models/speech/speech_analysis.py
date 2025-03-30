from flask import Flask, request, jsonify
import whisper
import os

app = Flask(__name__)

# Set the base directory to the script's location
BASE_DIR = os.path.dirname(os.path.abspath(__file__))

# Load Whisper AI model
model = whisper.load_model("base")

def analyze_speech(audio_path):
    """
    Transcribes speech, detects pauses, filler words, and calculates confidence score.
    """
    result = model.transcribe(audio_path, word_timestamps=True)
    words = result['segments']

    pauses = 0
    filler_words = ['um', 'uh', 'like', 'you know']
    filler_count = 0
    total_duration = words[-1]['end'] if words else 0

    last_end_time = 0
    for segment in words:
        if last_end_time > 0:
            pause_duration = segment['start'] - last_end_time
            if pause_duration > 1.5:
                pauses += 1
        
        for word in filler_words:
            if word in segment['text'].lower():
                filler_count += 1

        last_end_time = segment['end']

    # Calculate confidence score (adjustable formula)
    confidence_score = max(100 - (pauses * 10 + filler_count * 5), 0)

    return {
        "transcript": result['text'],
        "pauses": pauses,
        "fillers": filler_count,
        "confidence_score": confidence_score
    }

@app.route('/', methods=['GET'])
def home():
    return jsonify({"message": "Speech Analysis API is running!"})

@app.route('/analyze', methods=['POST'])
def analyze():
    """
    API endpoint to analyze speech from an uploaded audio file.
    """
    if 'audio' not in request.files:
        return jsonify({"error": "No audio file provided"}), 400

    audio = request.files['audio']
    audio_path = os.path.join(BASE_DIR, "temp_audio.mp3")
    audio.save(audio_path)

    # Process the audio file
    analysis = analyze_speech(audio_path)

    # Remove temporary audio file
    os.remove(audio_path)

    return jsonify(analysis)

@app.route('/status', methods=['GET'])
def status():
    """
    Detailed status endpoint for the Speech Analysis API
    """
    return jsonify({
        "status": "running",
        "model_loaded": model is not None,
        "version": "1.0",
        "endpoints": {
            "analyze": "/analyze",
            "status": "/status"
        }
    })

if __name__ == '__main__':
    app.run(host="0.0.0.0", port=5001, debug=True)