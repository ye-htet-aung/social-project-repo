<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .chat-container { max-width: 600px; margin: 50px auto; }
        .chat-box { height: 400px; overflow-y: auto; background: white; padding: 10px; border: 1px solid #ddd; }
        .message { display: flex; margin-bottom: 10px; }
        .incoming { justify-content: flex-start; }
        .outgoing { justify-content: flex-end; }
        .message .text { padding: 8px 12px; border-radius: 10px; max-width: 75%; }
        .incoming .text { background-color: #e9ecef; }
        .outgoing .text { background-color: #0d6efd; color: white; }
    </style>
</head>
<body>

<div class="container">
    <div class="chat-container">
        <div class="chat-box" id="chat-box"></div>
        <div class="input-group mt-2">
            <input type="text" id="message" class="form-control" placeholder="Type a message">
            <button class="btn btn-primary" id="send-btn">Send</button>
        </div>
    </div>
</div>

<script>
    const ws = new WebSocket("ws://127.0.0.1:8080");

    const sender_id = 1;  // Replace with dynamic user ID
    const receiver_id = 2;  // Replace with dynamic receiver ID

    document.getElementById("send-btn").addEventListener("click", sendMessage);
    document.getElementById("message").addEventListener("keypress", (event) => {
        if (event.key === "Enter") sendMessage();
    });

    function sendMessage() {
        const messageInput = document.getElementById("message");
        const message = messageInput.value.trim();
        if (message && ws.readyState === WebSocket.OPEN) {
            ws.send(JSON.stringify({
                sender_id: sender_id,
                receiver_id: receiver_id,
                message: message
            }));
            messageInput.value = "";
        } else {
            console.warn("WebSocket not connected.");
        }
    }

    ws.onmessage = function(event) {
        const data = JSON.parse(event.data);
        displayMessage(data.sender_id, data.message, data.type);
    };

    function displayMessage(sender_id, message, type) {
        const chatBox = document.getElementById("chat-box");
        const msgDiv = document.createElement("div");

        msgDiv.classList.add("message", type === "outgoing" ? "outgoing" : "incoming");
        msgDiv.innerHTML = `<div class="text"><strong>User ${sender_id}:</strong> ${message}</div>`;
        
        chatBox.appendChild(msgDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    // ✅ Fetch and Load Full Chat History on Page Load
    function loadChatHistory() {
        fetch(`load_messages.php?sender_id=${sender_id}&receiver_id=${receiver_id}`)
            .then(response => response.json())
            .then(messages => {
                document.getElementById("chat-box").innerHTML = ""; // Clear previous messages
                messages.forEach(msg => {
                    displayMessage(msg.sender_id, msg.message, msg.sender_id == sender_id ? "outgoing" : "incoming");
                });
            })
            .catch(error => console.error("Error loading messages:", error));
    }

    // Call function on page load
    loadChatHistory();
</script>

</body>
</html>
