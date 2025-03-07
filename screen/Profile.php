<?php
include __DIR__ . '/../database/config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    echo "You must log in first.";
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT u.name, p.birthday, p.current_location, p.hometown, p.educatione, p.bio, p.profile_picture 
        FROM users u 
        LEFT JOIN user_profiles p ON u.id = p.user_id 
        WHERE u.id = '$user_id'";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = isset($row["name"]) ? $row["name"] : 'No Name';
    $birthday = isset($row["birthday"]) ? $row["birthday"] : 'Not Provided';
    $current_location = isset($row["current_location"]) ? $row["current_location"] : 'Not Provided';
    $hometown = isset($row["hometown"]) ? $row["hometown"] : 'Not Provided';
    $educatione = isset($row["educatione"]) ? $row["educatione"] : 'Not Provided';
    $bio = isset($row["bio"]) ? $row["bio"] : 'No Bio';
    $profile_picture = !empty($row["profile_picture"]) && file_exists("uploads/" . $row["profile_picture"]) 
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
            <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" width="150" height="150">

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
            <a href="edit_profile.php">Edit Profile</a>
        </div>
    </div>
</body>
</html>
