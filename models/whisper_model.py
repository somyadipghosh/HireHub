import whisper
import os
import json
import warnings
import numpy as np
from flask import Flask, jsonify, request, Response, make_response
from flask_cors import CORS
from werkzeug.middleware.proxy_fix import ProxyFix

app = Flask(__name__)
app.wsgi_app = ProxyFix(app.wsgi_app)
CORS(app, resources={r"/*": {"origins": "*"}})

class InterviewAnalyzer:
    def __init__(self):
        self.model = None
        self.language = "en"
        self.fumble_words = {
            "en": ["uh", "um", "hmm", "like", "you know", "actually", "basically"],
            "hi": ["अー", "एम्म्", "मतलब", "वो", "देखिये"],
            "bn": ["আ", "উম", "মানে", "ধরুন", "কি যেন"]
        }
        
    def load_model(self, language):
        print(f"Loading model for {language}...")
        self.language = language
        with warnings.catch_warnings():
            warnings.filterwarnings("ignore")
            self.model = whisper.load_model("medium", device="cpu")
    
    def analyze_segment(self, audio_file):
        try:
            result = self.model.transcribe(
                audio_file,
                fp16=False,
                language=self.language,
                word_timestamps=True,
                initial_prompt="This is a job interview conversation."
            )
            
            text = result["text"].strip()
            segments = result["segments"]
            
            # Analyze metrics
            words = text.lower().split()
            fumble_count = sum(words.count(fw) for fw in self.fumble_words[self.language])
            fumble_ratio = fumble_count / max(len(words), 1)
            
            # Calculate pause durations
            pause_durations = [segments[i]["start"] - segments[i-1]["end"] 
                             for i in range(1, len(segments))]
            avg_pause = np.mean(pause_durations) if pause_durations else 0
            
            # Calculate confidence
            confidence_score = self._calculate_confidence(fumble_ratio, avg_pause)
            
            return {
                "text": text,
                "confidence_level": confidence_score,
                "fumble_ratio": f"{fumble_ratio:.2%}",
                "language": self.language
            }
            
        except Exception as e:
            print(f"Error in analysis: {str(e)}")
            return None
    
    def _calculate_confidence(self, fumble_ratio, pause_duration):
        if fumble_ratio < 0.02 and pause_duration < 1.5:
            return "High"
        elif fumble_ratio < 0.05 and pause_duration < 3:
            return "Medium"
        return "Low"

# Initialize analyzer globally
analyzer = InterviewAnalyzer()
analyzer.load_model("en")  # Load default model at startup

@app.route('/')
def home():
    return make_response(jsonify({
        "status": "online",
        "endpoints": {
            "POST /analyze": "Initialize analysis with language",
            "GET /results": "Get analysis results"
        }
    }), 200)

@app.route('/analyze', methods=['POST', 'OPTIONS'])
def analyze():
    if request.method == 'OPTIONS':
        return make_response('', 200)
        
    try:
        if not request.is_json:
            return make_response(jsonify({"error": "Content-Type must be application/json"}), 400)
            
        data = request.get_json()
        language = data.get('language', 'en')
        
        if language not in ['en', 'hi', 'bn']:
            return make_response(jsonify({"error": "Unsupported language"}), 400)
            
        analyzer.load_model(language)
        return make_response(jsonify({"status": "success", "language": language}), 200)
    except Exception as e:
        return make_response(jsonify({"error": str(e)}), 500)

@app.route('/results', methods=['GET'])
def get_results():
    try:
        if not os.path.exists("interview_audio.mp3"):
            return make_response(jsonify({"error": "No audio file found"}), 404)
            
        results = analyzer.analyze_segment("interview_audio.mp3")
        if not results:
            return make_response(jsonify({"error": "Analysis failed"}), 500)
            
        return make_response(jsonify(results), 200)
    except Exception as e:
        return make_response(jsonify({"error": str(e)}), 500)

if __name__ == "__main__":
    print("Starting Whisper Analysis Server...")
    print("Available endpoints:")
    print("  - GET  /")
    print("  - POST /analyze")
    print("  - GET  /results")
    app.run(host='0.0.0.0', port=5000, debug=True)
