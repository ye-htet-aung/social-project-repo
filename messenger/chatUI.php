<?php
include 'db_connect.php';
session_start();

// ✅ Ensure User is Logged In
if (!isset($_SESSION['user_id'])) {
    die("User not logged in. Please log in first.");
}

$sender_id = $_SESSION['user_id'];  // ✅ Logged-in user
$receiver_id = isset($_GET['receiver_id']) ? intval($_GET['receiver_id']) : null;
$receiver_name = $_GET['receiver_name'];
$receiver_profile = "default.png"; // Default profile image

if ($receiver_id) {
    // Fetch receiver profile picture
    $query = "SELECT profile_picture FROM user_profiles WHERE user_id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $receiver_id);
    $stmt->execute();
    $stmt->bind_result($profile_pic);
    if ($stmt->fetch() && !empty($profile_pic)) {
        $receiver_profile = $profile_pic;
    }
    $stmt->close();
}

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

</head>
<body>
    <div class="chat-container">
        <div class="chat-header">
            <img src="../<?php echo htmlspecialchars($receiver_profile); ?>" alt="Profile">
            <span><?php echo htmlspecialchars($receiver_name); ?></span>
        </div>
        <div class="chat-box" id="chat-box"></div>
        <div class="chat-footer">
            <input type="text" id="message" placeholder="Type a message">
            <label for="image-upload" class="image-upload-btn"> <i style="font-size:30px; margin-top:8px;margin-left:2px;" class="fa fa-paperclip"></i></label>
            <input type="file" id="image-upload" accept="image/*" style="display: none;">
            <button id="send-btn">&#9658;</button>
        </div>
    </div>
    
    <script>
        const sender_id = <?php echo json_encode($sender_id); ?>;
        const receiver_id = <?php echo json_encode($receiver_id); ?>;
        const ws = new WebSocket("ws://127.0.0.1:9090");

        document.getElementById("send-btn").addEventListener("click", sendMessage);
        document.getElementById("message").addEventListener("keypress", (event) => {
            if (event.key === "Enter") sendMessage();
        });

        function sendMessage() {
            const messageInput = document.getElementById("message");
            const message = messageInput.value.trim();
            if (message && ws.readyState === WebSocket.OPEN) {
                ws.send(JSON.stringify({ sender_id, receiver_id, message}));
                ws.send(JSON.stringify({ sender_id, receiver_id, message, type: "text" }));
                messageInput.value = "";
            } else {
                console.warn("WebSocket not connected.");
            }
        }

        // ✅ Send Image
        document.getElementById("image-upload").addEventListener("change", function(event) {
    let file = event.target.files[0];
    if (!file) return;

    let formData = new FormData();
    formData.append("image", file);
    formData.append("sender_id", sender_id);
    formData.append("receiver_id", receiver_id);

    fetch("upload_image.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            let imageUrl = data.image_url;
            ws.send(JSON.stringify({ sender_id, receiver_id, image: imageUrl }));
        } else {
            console.error("Image upload failed:", data.error);
        }
    })
    .catch(error => console.error("Error uploading image:", error));
});


let imagesDisplayed = {};  // Object to track displayed images

ws.onmessage = function(event) {
    const data = JSON.parse(event.data);

    if (data.message) {
        // Display text message
        displayMessage(data.sender_id, data.message, data.sender_id == sender_id ? "outgoing" : "incoming", data.image);
    }
    if (data.image) {
        // Display image only if it hasn't been displayed yet
        displayImage(data.sender_id, data.image, data.sender_id == sender_id ? "outgoing" : "incoming");
    }
};

function displayMessage(senderId, message, type, image = null) {
    const chatBox = document.getElementById("chat-box");
    const msgDiv = document.createElement("div");
    msgDiv.classList.add("message", type);

    if (image) {
        // Display image only if it hasn't been shown before
        if (!imagesDisplayed[image]) {
            msgDiv.innerHTML = `<img src="/social_app/messenger/${image}" alt="Sent Image" class="chat-image">`;
            imagesDisplayed[image] = true; // Mark the image as displayed
            chatBox.appendChild(msgDiv);
        }
    } else {
        msgDiv.innerHTML = `<div class="text">${message}</div>`;
        chatBox.appendChild(msgDiv);
    }

    chatBox.scrollTop = chatBox.scrollHeight;
}

function displayImage(senderId, imageUrl, type) {
    const chatBox = document.getElementById("chat-box");
    const imgDiv = document.createElement("div");
    imgDiv.classList.add("message", type);

    // Ensure the image is not displayed again
    if (!imagesDisplayed[imageUrl]) {
        imgDiv.innerHTML = `<img src="${imageUrl}" class="chat-image" alt="Image">`;
        imagesDisplayed[imageUrl] = true; // Mark this image as displayed
        chatBox.appendChild(imgDiv);
    }

    chatBox.scrollTop = chatBox.scrollHeight;
}

function loadChatHistory() {
    fetch(`load_messages.php?sender_id=${sender_id}&receiver_id=${receiver_id}`)
        .then(response => response.json())
        .then(messages => {
            const chatBox = document.getElementById("chat-box");
            chatBox.innerHTML = ""; // Clear chat box before adding new messages
            
            messages.forEach(msg => {
                const type = msg.sender_id == sender_id ? "outgoing" : "incoming";
                if (msg.image && !imagesDisplayed[msg.image]) {
                    displayImage(msg.sender_id, msg.image, type);
                }
                displayMessage(msg.sender_id, msg.message, type, msg.image);
            });
        })
        .catch(error => console.error("Error loading messages:", error));
}

loadChatHistory();


    </script>
<script src="../javascript/setting.js"></script>

</body>
</html>
