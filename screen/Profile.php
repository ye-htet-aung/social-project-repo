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
include 'mainlayout.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../css/profile.css"> 
</head>
<body>
    <div id="main">
        <div id="profile-container">
            <div id="profile-back">
                <div id="profile-bg"></div>
                <DIV id="profile-image-div"></DIV>
                <div id="info">
                    <h2><?php echo htmlspecialchars($name); ?></h2>
                    <h3>i am very handsome</h3>
                    <div id="buttonsdiv">
                        <button id="addfriend">+ Add to story</button>
                        <button id="editprofile">Edit profile</button>
                    </div>
                </div>
            </div>
        <div id="profile-info">
                <h2>Detials</h2>
                <p><strong>Lives in </strong> <?php echo htmlspecialchars($current_location); ?></p>
                <p><strong>Home Town </strong> <?php echo htmlspecialchars($hometown); ?></p>
                <p><strong>Studied at</strong> <?php echo htmlspecialchars($educatione); ?></p>
                kl
        </div>
        </div>
    </div>
</body>
</html>