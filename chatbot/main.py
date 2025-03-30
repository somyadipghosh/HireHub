from flask import Flask, request, render_template, Blueprint
import openai
from dotenv import load_dotenv
import os
import json
from urllib.parse import quote

# Load environment variables from .env file
load_dotenv()

# Configure OpenAI API
openai.api_key = os.getenv("OPENAI_API_KEY")

server = Flask(__name__)
server.config['STATIC_FOLDER'] = 'static'
static_bp = Blueprint('static', __name__, static_url_path='/static', static_folder='static')
server.register_blueprint(static_bp)

initiate_txt = """I am building Hirehub, an AI-powered online interview platform with 
video conferencing. The system must integrate a robust anti-cheating mechanism that 
includes eye-tracking and face detection to monitor gaze shifts and eye movement 
patterns, tab and window switching detection to track if the interviewee navigates away, and 
screen recording with dual monitor detection to prevent external assistance. Additionally, 
real-time speech analysis using Whisper AI should detect hesitations, fumbling, and unnatural pauses during the interview.
The AI should provide real-time analytics on user attention, suspicious activity 
alerts, and generate a detailed post-interview report. The system needs to 
integrate seamlessly with a PHP-based web application and run efficiently 
in a browser. I need a chatbot that can answer questions about 
implementing these AI features, provide relevant code snippets 
for WebSocket communication between Python and PHP, and assist 
with troubleshooting face-tracking models and WebRTC integration 
for interviews. The chatbot should prioritize efficiency, accuracy, 
and security to ensure a seamless AI-powered interview experience.
"""

def send_gpt(prompt):
    try:
        response = openai.ChatCompletion.create(
            model="gpt-4",  # or "gpt-3.5-turbo" depending on your plan
            messages=[{"role": "system", "content": initiate_txt},
                      {"role": "user", "content": prompt}]
        )
        return response
    except Exception as e:
        return {"error": str(e)}

def save_response_to_json(resp):
    try:
        # Save the entire response object to JSON
        with open('response.json', 'w') as json_file:
            json.dump(resp, json_file, indent=4)
    except Exception as e:
        print("Error saving response:", e)

def extract_text_from_json():
    try:
        with open('response.json', 'r') as json_file:
            resp_data = json.load(json_file)
        
        # Extract the content from the response JSON
        choices = resp_data.get('choices', [])
        if not choices:
            return "No response content found."
        
        text_parts = [choice.get('message', {}).get('content', '') for choice in choices]
        return "\n".join(text_parts)
    except Exception as e:
        return f"Error reading response: {e}"

@server.route('/', methods=['GET', 'POST'])
def get_request_json():
    if request.method == 'POST':
        question = request.form.get('question', '')
        if not question:
            return render_template(
                'chat3.5.html',
                question="I have nothing to recycle. Provide some of your quotes regarding waste recycling",
                res="No question provided."
            )
        
        resp = send_gpt(question)
        if isinstance(resp, dict) and "error" in resp:
            res = resp["error"]
        else:
            save_response_to_json(resp)
            res = extract_text_from_json()
        
        return render_template('chat3.5.html', question=question, res=res)
    
    return render_template('chat3.5.html', question=0)

if __name__ == '__main__':
    server.run(debug=True, host='0.0.0.0', port=5001)
