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
    $profile_picture_path = __DIR__ . '/../uploads/' . $row["profile_picture"];
    $profile_picture = (!empty($row["profile_picture"]) && file_exists($profile_picture_path))
        ? "uploads/" . $row["profile_picture"] 
        : "uploads/default_profile_picture.jpg";
} else {
    echo "Profile not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href=""> 
</head>
<body>
    <div id="profile-container">
        <div id="profile-header">
            <h1>Welcome, <?php echo htmlspecialchars($name); ?>!</h1>
        </div>

        <div id="profile-details">
            <div id="profile-picture">
            
            <img src="uploads/<?php echo htmlspecialchars($profile_picture); ?>" width="50" height="50">

           <?php 
            if (file_exists($profile_picture)) {
                echo "File exists.";
            } else {
                echo "File does not exist.";
}

?>
            </div>

            <div id="profile-info">
                <h2>Profile Information</h2>
                <p><strong>Birthday:</strong> <?php echo htmlspecialchars($birthday); ?></p>
                <p><strong>Current Location:</strong> <?php echo htmlspecialchars($current_location); ?></p>
                <p><strong>HomeTown:</strong> <?php echo htmlspecialchars($hometown); ?></p>
                <p><strong>Education:</strong> <?php echo htmlspecialchars($educatione); ?></p>
                <p><strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($bio)); ?></p>
            </div>
        </div>

        <div id="edit-profile">
            <a href="profiledata.php">Edit Profile</a>
        </div>
    </div>
</body>
</html>
