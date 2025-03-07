<?php
session_start();

// ✅ MySQLi Database Connection
$mysqli = new mysqli("localhost", "root", "", "social_app_db", 3307);

// ✅ Check Connection
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// ✅ Ensure User is Logged In
if (!isset($_SESSION['user_id'])) {
    die("User not logged in. Please log in first.");
}

$sender_id = $_SESSION['user_id'];  // ✅ Logged-in user

// ✅ Get receiver_id from URL or fetch last chat
$receiver_id = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : null;

if (!$receiver_id) {
    // Get last chat partner if no receiver_id is provided
    $query = "SELECT receiver_id FROM chat_messages WHERE sender_id = ? ORDER BY timestamp DESC LIMIT 1";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $sender_id);
    $stmt->execute();
    $stmt->bind_result($receiver_id);
    $stmt->fetch();
    $stmt->close();
}

// If still no receiver_id, redirect to chat list
if (!$receiver_id) {
    header("Location: chat_list.php");
    exit;
}
?>

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
    const sender_id = <?php echo json_encode($sender_id); ?>;
    const receiver_id = <?php echo json_encode($receiver_id); ?>;
    const ws = new WebSocket("ws://192.168.1.6:8080");

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
        displayMessage(data.sender_id, data.message, data.sender_id == sender_id ? "outgoing" : "incoming");
    };

    function displayMessage(senderId, message, type) {
        const chatBox = document.getElementById("chat-box");
        const msgDiv = document.createElement("div");

        msgDiv.classList.add("message", type);
        msgDiv.innerHTML = `<div class="text">${message}</div>`;
        
        chatBox.appendChild(msgDiv);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function loadChatHistory() {
        fetch(`load_messages.php?sender_id=${sender_id}&receiver_id=${receiver_id}`)
            .then(response => response.json())
            .then(messages => {
                document.getElementById("chat-box").innerHTML = "";
                messages.forEach(msg => {
                    const type = (msg.sender_id == sender_id) ? "outgoing" : "incoming";
                    displayMessage(msg.sender_id, msg.message, type);
                });
            })
            .catch(error => console.error("Error loading messages:", error));
    }

    loadChatHistory();
</script>

</body>
</html>
