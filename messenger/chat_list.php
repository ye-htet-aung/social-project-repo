<?php
include 'db_connect.php';
session_start();

// ✅ Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("User not logged in. Please log in first.");
}

$logged_in_user = $_SESSION['user_id'];

// ✅ Fetch users along with the last message
$query = "SELECT u.id, u.name, 
                 (SELECT message FROM chat_messages 
                  WHERE (sender_id = u.id AND receiver_id = ?) 
                     OR (sender_id = ? AND receiver_id = u.id) 
                  ORDER BY timestamp DESC LIMIT 1) AS last_message 
          FROM users u 
          WHERE u.id != ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("iii", $logged_in_user, $logged_in_user, $logged_in_user);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        body { background-color: #fff; font-family: Arial, sans-serif; }
        .chat-container { max-width: 400px; margin: 20px auto; background: white; }
        
        /* Header */
        .chat-header { display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #ddd; }
        .chat-header .menu-icon { font-size: 20px; cursor: pointer; }
        .chat-header .edit-icon { font-size: 20px; cursor: pointer; }

        /* Search bar */
        .search-bar { padding: 10px; border-bottom: 1px solid #ddd; }
        .search-bar input { width: 100%; padding: 8px; border-radius: 20px; border: 1px solid #ccc; outline: none; }

        /* Stories section */
        .stories { display: flex; overflow-x: auto; padding: 10px; }
        .story { text-align: center; margin-right: 10px; }
        .story img { width: 50px; height: 50px; border-radius: 50%; border: 2px solid #007bff; }

        /* Tabs */
        .tabs { display: flex; justify-content: space-around; padding: 10px 0; border-bottom: 2px solid #ddd; font-weight: bold; }
        .tab { cursor: pointer; padding: 5px 10px; }
        .tab.active { border-bottom: 3px solid #007bff; color: #007bff; }

        /* Chat list */
        .chat-list { max-height: 500px; overflow-y: auto; }
        .chat-item { display: flex; align-items: center; padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; }
        .chat-item:hover { background: #f8f9fa; }
        .chat-item img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; }
        .chat-details { flex-grow: 1; margin-left: 10px; }
        .chat-name { font-weight: bold; }
        .chat-message { font-size: 14px; color: #666; }

        /* Bottom Nav */
        .bottom-nav { display: flex; justify-content: space-around; padding: 10px 0; border-top: 1px solid #ddd; position: fixed; bottom: 0; width: 100%; max-width: 400px; background: white; }
        .bottom-nav i { font-size: 20px; cursor: pointer; }
        .active-icon { color: #007bff; }
    </style>
</head>
<body>
    <div class="chat-container">
        
        <!-- Header -->
        <div class="chat-header">
            <i class="fas fa-bars menu-icon"></i>
            <span style="font-size: 18px; font-weight: bold;">Chats</span>
            <i class="fas fa-edit edit-icon"></i>
        </div>

        <!-- Search Bar -->
        <div class="search-bar">
            <input type="text" id="searchInput" class="form-control" placeholder="Search" onkeyup="filterChats()">
        </div>
        <!-- Stories -->
        <div class="stories">
            <div class="story"><img src="#" alt="User"><br><small>Your Note</small></div>
            <div class="story"><img src="#" alt="User"></div>
            <div class="story"><img src="#" alt="User"></div>
            <div class="story"><img src="#" alt="User"></div>
        </div>

        <!-- Tabs -->
        <!-- <div class="tabs">
            <div class="tab active">HOME</div>
            <div class="tab">CHANNELS</div>
        </div> -->

        <!-- Chat List -->
        <div class="chat-list" id="chatList">
            <?php while ($row = $result->fetch_assoc()): ?>
                <a href="chatUI.php?receiver_id=<?= $row['id']; ?>&receiver_name=<?= urlencode($row['name']); ?>" class="chat-item">
                    <img src="#" alt="Avatar">
                    <div class="chat-details">
                        <div class="chat-name"><?= htmlspecialchars($row['name']); ?></div>
                        <div class="chat-message">
                            <?= htmlspecialchars($row['last_message'] ?? "Click to start chat"); ?>
                        </div>
                    </div>
                </a>
            <?php endwhile; ?>
        </div>

        <!-- Bottom Navigation -->
        <div class="bottom-nav">
            <i class="fas fa-comment-dots active-icon"></i>
            <i class="fas fa-phone"></i>
            <i class="fas fa-users"></i>
            <i class="fas fa-book-open"></i>
        </div>
        <script>
        function filterChats() {
            let input = document.getElementById("searchInput").value.toLowerCase();
            let chatItems = document.querySelectorAll(".chat-item");

            chatItems.forEach(item => {
                let name = item.querySelector(".chat-name").textContent.toLowerCase();
                if (name.includes(input)) {
                    item.style.display = "";
                } else {
                    item.style.display = "none";
                }
            });
        }
</script>
    </div>

</body>
</html>

<?php
$stmt->close();
$mysqli->close();
?>
