<?php
session_start();
error_reporting(0);
require('../config/db.php');
include("../config/auth_session.php");

// Fetch the username from session
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>HireHub Interview Platform</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src='https://8x8.vc/vpaas-magic-cookie-a6728c13a8d448fbb7a0c5ac59823c46/external_api.js' async></script>
    <style>
        html, body, #jaas-container { height: 100%; }
        #status { position: absolute; top: 10px; right: 10px; background: red; color: white; padding: 5px; border-radius: 5px; }
    </style>
    <script type="text/javascript">
        window.onload = () => {
            const userName = "<?php echo htmlspecialchars($username, ENT_QUOTES, 'UTF-8'); ?>";

            const api = new JitsiMeetExternalAPI("8x8.vc", {
                roomName: "vpaas-magic-cookie-a6728c13a8d448fbb7a0c5ac59823c46/HireHub Interview Room",
                parentNode: document.querySelector('#jaas-container'),
                jwt: "eyJraWQiOiJ2cGFhcy1tYWdpYy1jb29raWUtYTY3MjhjMTNhOGQ0NDhmYmI3YTBjNWFjNTk4MjNjNDYvMDVjN2RmLVNBTVBMRV9BUFAiLCJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiJqaXRzaSIsImlzcyI6ImNoYXQiLCJpYXQiOjE3NDMzMDk5MzEsImV4cCI6MTc0MzMxNzEzMSwibmJmIjoxNzQzMzA5OTI2LCJzdWIiOiJ2cGFhcy1tYWdpYy1jb29raWUtYTY3MjhjMTNhOGQ0NDhmYmI3YTBjNWFjNTk4MjNjNDYiLCJjb250ZXh0Ijp7ImZlYXR1cmVzIjp7ImxpdmVzdHJlYW1pbmciOnRydWUsIm91dGJvdW5kLWNhbGwiOnRydWUsInNpcC1vdXRib3VuZC1jYWxsIjpmYWxzZSwidHJhbnNjcmlwdGlvbiI6dHJ1ZSwicmVjb3JkaW5nIjp0cnVlfSwidXNlciI6eyJoaWRkZW4tZnJvbS1yZWNvcmRlciI6ZmFsc2UsIm1vZGVyYXRvciI6dHJ1ZSwibmFtZSI6InNvbXlhZGlwZ2hvc2giLCJpZCI6Imdvb2dsZS1vYXV0aDJ8MTE0MTExOTE3NzY4NDkxODAzNDYwIiwiYXZhdGFyIjoiIiwiZW1haWwiOiJzb215YWRpcGdob3NoQGdtYWlsLmNvbSJ9fSwicm9vbSI6IioifQ.jy5-XqidkaHFvalnc883XUyTyMmbgKlpFgMGv09l8eW0lLmoG1AVJeDz-xNjip9KW9VbMwQNDGUfHIR91PZL6VUBdJw70i4vYyzfosETqjhpt2FWrEXphKMnh--sbyutgxIXRrjUjPlrvubpSpjoanTfZ9iO4mXtTMql851N1QLaUUBsEUKVuEuR2UJd6sq6JHyuw7W4g06a7eL4v5AvTgO4eKAnOFwxqnqbhr8E1wQnrgq4tFbrAsvPzLQI-rl851o-Z0mOE14hV-yGY2bwdCE5TdjmAUZ7NFMwVoVXjGkZ0f-BohW4FH4wg7GeZQnDrVfxLpaRhVYZicartoYJgw",
                configOverwrite: { disableProfile: true }
            });

            let attempts = 0;
            const interval = setInterval(() => {
                api.executeCommand('displayName', userName);
                attempts++;
                if (attempts >= 5) clearInterval(interval);
            }, 1000);

            api.addEventListener("readyToClose", () => {
                window.location.href = "../views/meetingend.php";
            });

            // WebSocket for Eye-Tracking Data
            const ws = new WebSocket("ws://localhost:8766");

            ws.onopen = () => {
                console.log("Connected to eye-tracking server");
                document.getElementById("status").innerText = "Eye Tracking: Active";
                document.getElementById("status").style.background = "green";
            };

            ws.onmessage = (event) => {
                const data = JSON.parse(event.data);
                console.log("Eye Tracking Data:", data);

                if (data.status === "active") {
                    let message = "";
                    if (data.data.left === "Closed" || data.data.right === "Closed") {
                        message = "⚠️ Eyes closed! Stay attentive.";
                    } else if (data.data.left !== "Center" || data.data.right !== "Center") {
                        message = `Looking ${data.data.left === data.data.right ? data.data.left : 'away'}`;
                    }

                    if (message) {
                        alert(message);
                    }
                }
            };

            ws.onerror = (error) => {
                console.error("WebSocket Error:", error);
                document.getElementById("status").innerText = "Eye Tracking: Disconnected";
                document.getElementById("status").style.background = "red";
            };

            ws.onclose = () => {
                console.log("Disconnected from eye-tracking server");
                document.getElementById("status").innerText = "Eye Tracking: Disconnected";
                document.getElementById("status").style.background = "red";
            };
        };
    </script>
</head>
<body>
    <div id="jaas-container"></div>
    <div id="status">Eye Tracking: Disconnected</div>
</body>
</html>
