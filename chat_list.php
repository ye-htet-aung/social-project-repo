<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "social_app_db", 3308);

if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

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
    <title>Chat List UI</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .chat-container { max-width: 400px; margin: 20px auto; background: white; border-radius: 10px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .search-bar { padding: 10px; border-bottom: 1px solid #ddd; }
        .chat-list { max-height: 500px; overflow-y: auto; }
        .chat-item { display: flex; align-items: center; padding: 10px; border-bottom: 1px solid #eee; cursor: pointer; }
        .chat-item:hover { background: #f1f1f1; }
        .chat-item img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        .chat-details { flex-grow: 1; margin-left: 10px; }
        .chat-name { font-weight: bold; }
        .chat-message { font-size: 14px; color: #666; }
        .chat-time { font-size: 12px; color: #999; }
        a { text-decoration: none; color: inherit; }
    </style>
</head>
<body>
    <div class="chat-container">
        <div class="search-bar">
            <input type="text" class="form-control" placeholder="Search people and groups">
        </div>
        <div class="chat-list">
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
    </div>  
</body>
</html>

<?php
$stmt->close();
$mysqli->close();
?>
