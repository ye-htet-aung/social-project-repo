<?php
include 'db_connect.php';
session_start();

// ✅ Ensure User is Logged In
if (!isset($_SESSION['user_id'])) {
    die("User not logged in. Please log in first.");
}

$sender_id = $_SESSION['user_id'];  // ✅ Logged-in user

// ✅ Get receiver_id from URL or fetch last chat
$receiver_id = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : null;
$receiver_name = $_GET['receiver_name'];
$receiver_profile = "default-profile.png"; // Default profile image

// if ($receiver_id) {
//     // Fetch receiver profile picture
//     $query = "SELECT profile_pic FROM users WHERE id = ?";
//     $stmt = $mysqli->prepare($query);
//     $stmt->bind_param("i", $receiver_id);
//     $stmt->execute();
//     $stmt->bind_result($profile_pic);
//     if ($stmt->fetch() && !empty($profile_pic)) {
//         $receiver_profile = $profile_pic;
//     }
//     $stmt->close();
// }

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
    <link rel="stylesheet" href="chatUI.css">

</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <!-- <img src="uploads/<?php echo htmlspecialchars($receiver_profile); ?>" alt="Profile"> -->
             <img src="cu.png">
            <span><?php echo htmlspecialchars($receiver_name); ?></span>
        </div>
        <div class="chat-box" id="chat-box"></div>
        <div class="chat-footer">
            <input type="text" id="message" placeholder="Type a message">
            <button id="send-btn">&#9658;</button>
        </div>
    </div>
    <script>
        const sender_id = <?php echo json_encode($sender_id); ?>;
        const receiver_id = <?php echo json_encode($receiver_id); ?>;
        const ws = new WebSocket("ws://192.168.4.105:8080");

        document.getElementById("send-btn").addEventListener("click", sendMessage);
        document.getElementById("message").addEventListener("keypress", (event) => {
            if (event.key === "Enter") sendMessage();
        });

        function sendMessage() {
            const messageInput = document.getElementById("message");
            const message = messageInput.value.trim();
            if (message && ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({ sender_id, receiver_id, message }));
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
                        const type = msg.sender_id == sender_id ? "outgoing" : "incoming";
                        displayMessage(msg.sender_id, msg.message, type);
                    });
                })
                .catch(error => console.error("Error loading messages:", error));
        }
        loadChatHistory();
    </script>
</body>
</html>
