<?php
include __DIR__ . '/../database/config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    echo "You must log in first.";
    exit;
}

$user_id = $_SESSION['user_id'];
$stmt = $con->prepare("SELECT u.name, p.birthday, p.current_location, p.hometown, p.educatione, p.bio, p.profile_picture 
                        FROM users u 
                        LEFT JOIN user_profiles p ON u.id = p.user_id 
                        WHERE u.id = ?");
$user_id=$_SESSION["user_id"];
$sql = "SELECT name, profile_picture FROM users u
        LEFT JOIN user_profiles p ON u.id = p.user_id
        WHERE u.id = '$user_id'";
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = isset($row["name"]) ? $row["name"] : 'No Name';
    $birthday = isset($row["birthday"]) ? $row["birthday"] : 'Not Provided';
    $current_location = isset($row["current_location"]) ? $row["current_location"] : 'Not Provided';
    $hometown = isset($row["hometown"]) ? $row["hometown"] : 'Not Provided';
    $educatione = isset($row["educatione"]) ? $row["educatione"] : 'Not Provided';
    $bio = isset($row["bio"]) ? $row["bio"] : 'No Bio';
    
    $profile_picture = $row["profile_picture"];

    
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
            <div id="profile-picture">
            <?php if (!empty($profile_picture)) : ?>
                    <img src="<?php echo "http://localhost:3000/".htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
                    <?php else : ?>
                        <img src="default_profile_picture.jpg" alt="Default Profile Picture" width="50" height="50">
                    <?php endif; ?>
            </div>
                <h1><?php echo htmlspecialchars($name); ?></h1>
            </div>
            <div class="profile-info">
                <p><strong>Birthday:</strong> <?php echo htmlspecialchars($birthday); ?></p>
                <p><strong>Current Location:</strong> <?php echo htmlspecialchars($current_location); ?></p>
                <p><strong>Hometown:</strong> <?php echo htmlspecialchars($hometown); ?></p>
                <p><strong>Education:</strong> <?php echo htmlspecialchars($education); ?></p>
                <p><strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($bio)); ?></p>
            </div>
            <div class="profile-actions">
                <a href="/profiledata.php" class="edit-btn">Edit Profile</a>
            </div>
        </div>
    </div>
</body>
</html>