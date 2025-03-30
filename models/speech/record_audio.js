let mediaRecorder;
let audioChunks = [];

navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
    mediaRecorder = new MediaRecorder(stream);
    mediaRecorder.ondataavailable = event => {
        audioChunks.push(event.data);
    };
    
    mediaRecorder.onstop = async () => {
        const audioBlob = new Blob(audioChunks, { type: "audio/wav" });
        const formData = new FormData();
        formData.append("audio", audioBlob, "interview.wav");

        // Send audio to the Python server for analysis
        const response = await fetch("http://localhost:5000/analyze_audio", {
            method: "POST",
            body: formData
        });

        const result = await response.json();
        console.log("Confidence Score:", result.confidence_score);
        document.getElementById("confidence").innerText = `Confidence: ${result.confidence_score}`;
    };

    document.getElementById("start").addEventListener("click", () => {
        mediaRecorder.start();
        audioChunks = [];
    });

    document.getElementById("stop").addEventListener("click", () => {
        mediaRecorder.stop();
    });
});
