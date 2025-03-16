<?php
include __DIR__ . '/../database/config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    echo "You must log in first.";
    exit;
}

$user_id = isset($_GET['user_id']) ? $con->real_escape_string($_GET['user_id']) : '';
$_SESSION['primary_id']=$user_id;
$stmt = $con->prepare("SELECT u.name, p.birthday,p.gender ,p.current_location, p.hometown, p.educatione, p.bio, p.profile_picture ,p.background
                        FROM users u 
                        LEFT JOIN user_profiles p ON u.id = p.user_id 
                        WHERE u.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = isset($row["name"]) ? $row["name"] : '';
    $birthday = isset($row["birthday"]) ? $row["birthday"] : 'Not Provided';
    $gender = isset($row["gender"]) ? $row["gender"] : 'Not Provided';
    $current_location = isset($row["current_location"]) ? $row["current_location"] : 'Not Provided';
    $hometown = isset($row["hometown"]) ? $row["hometown"] : 'Not Provided';
    $educatione = isset($row["educatione"]) ? $row["educatione"] : 'Not Provided';
    $bio = isset($row["bio"]) ? $row["bio"] : '';
    
    $profile_picture = $row["profile_picture"];
    $background=$row["background"];

    
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
                <div id="profile-bg">
                    <img src="<?php echo "http://localhost:3000/".htmlspecialchars($background);?>" alt="">
                </div>
                <DIV id="profile-image-div">
                    <img src="<?php echo "http://localhost:3000/".htmlspecialchars($profile_picture);?>" alt="">
                </DIV>
                <div id="info">
                    <h2><?php echo htmlspecialchars($name); ?></h2>
                    <h3><?php echo htmlspecialchars($bio); ?></h3>
                    <div id="button-holder">
                        <button id="toggleBtn" class="buttonsdiv active">+ Add to story</button>
                        <button id="toggleBtn" class="buttonsdiv" onclick="window.location.href='/editProfile.php';">Edit profile</button>
                    </div>
                </div>
            </div>
        <div id="profile-info">
                <div id="button-group">
                    <button id="button-border">Post</button>
                    <button id="button-border">Photos</button>
                    <button id="button-border">Video</button>
                </div>
                <h2>Detials</h2>
                <p><strong>Lives in </strong> <?php echo htmlspecialchars($current_location); ?></p>
                <p><strong>Home Town </strong> <?php echo htmlspecialchars($hometown); ?></p>
                <p><strong>Studied at</strong> <?php echo htmlspecialchars($educatione); ?></p>
        </div>
        </div>
        <!-- friend -->
        <div class="card">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-users"></i>
                        Friend Lists
                    </h2>
                    <div class="card-description">Your Friend lists is here!</div>
                </div>
                <div class="card-content" id="friends_show">
                </div>
          </div>
        <div id="media">

        </div>

    </div>
    <script src="../javascript/fetchpostbyuser.js">

    </script>
    <script src="../javascript/fetchfriendbyuser.js">
    </script>

    

    <script>
        const buttons = document.querySelectorAll(".buttonsdiv");

        buttons.forEach(button => {
            button.addEventListener("click", function () {
                // Remove "active" class from all buttons
                buttons.forEach(btn => btn.classList.remove("active"));
                
                // Add "active" class to the clicked button
                this.classList.add("active");
            });
        });
    </script>
</body>
</html>