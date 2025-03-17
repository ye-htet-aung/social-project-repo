<?php
include __DIR__ . '/../database/config.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    echo "You must log in first.";
    exit;
}
$logeduser=$_SESSION['user_id'];
$user_id = isset($_GET['user_id']) ? $con->real_escape_string($_GET['user_id']) : '';
$_SESSION['primary_id']=$user_id;
$stmt = $con->prepare("SELECT u.name, p.birthday,p.gender ,p.current_location, p.hometown, p.educatione, p.bio, p.profile_picture ,p.background
                        FROM users u
                        LEFT JOIN user_profiles p ON u.id = p.user_id 
                        WHERE u.id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt1=$con->prepare("SELECT status,id,friend_id FROM friends where friend_id=? AND user_id=?");
$stmt1->bind_param("ii", $user_id,$logeduser);
$stmt1->execute();
$friend_id=null;

$status = $stmt1->get_result();
if($status->num_rows > 0){
    $r=$status->fetch_assoc();
    $request_id=$r['id'];
    $status=$r['status'];
    $friend_id=$r['friend_id'];
}
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
                    <?php if($logeduser == $user_id){
                    ?>
                        <div id="button-holder">
                        <button id="toggleBtn" class="buttonsdiv active">+ Add to story</button>
                        <button id="toggleBtn" class="buttonsdiv" onclick="window.location.href='/editProfile.php';">Edit profile</button>
                        </div>
                    <?php }else{ ?>
                        <div id="button-holder">
                        <?php  if($status=='pending' && $friend_id!==$logeduser){ ?>
                        <button id="toggleBtn" class="buttonsdiv active" onclick="confirmFriend(<?php echo htmlspecialchars($request_id); ?>)">Confirm Request</button>

                        <?php }else if($status =='accepted'){ ?>
                        <button id="toggleBtn" class="buttonsdiv active" >Friend</button>

                        <?php }else if($friend_id!==$user_id && $status !=='accepted' ){?>
                        <button id="toggleBtn" class="buttonsdiv active" onclick="removeFriendRequest(<?php echo htmlspecialchars($request_id);?>)">Cancel Request</button>

                        <?php }else{?>
                        <button id="toggleBtn" class="buttonsdiv active" onclick="addFriend(<?php echo htmlspecialchars($user_id); ?>)">+ Add Friend</button>
                        <?php }?>

                        <button id="toggleBtn" class="buttonsdiv" onclick="sendMessage(<?php echo htmlspecialchars($user_id);?>,'<?php echo htmlspecialchars($name);?>')">Message</button>
                        </div>
                        <?php }?>
                </div>
            </div>
        <div id="profile-info">
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
<script>
async function addFriend(friendId) {
  console.log(friendId);
  // Create a new FormData object to send data to the server
  const formData = new FormData();
  formData.append('friend_id', friendId);
  
  try {
        const response = await fetch('http://localhost:3000/add_friend.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        console.log(result);
        alert("friend request sent successfully!");
        location.reload();
    } catch (error) {
        alert.log(error.message)
        console.error("Error add friend request:", error.message);
    }
}

async function confirmFriend(requestId) {
  // Create a new FormData object to send data to the server
  const formData = new FormData();
  formData.append('request_id', requestId);
  
  try {
        const response = await fetch('http://localhost:3000/confirm_friend_request.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        console.log(result);
        alert("you are friend now!");
        location.reload();
    } catch (error) {
        console.error("Error conforming friend request:", error.message);
    }
}

async function removeFriendRequest(requestId) {
  alert(requestId);
  // Create a new FormData object to send data to the server
  const formData = new FormData();
  formData.append('request_id', requestId);
  
  try {
        const response = await fetch('http://localhost:3000/remove_friend_request.php', {
            method: 'POST',
            body: formData,
        });

        const result = await response.json();
        console.log(result);
        alert("you are removing a friend!");
        location.reload();
    } catch (error) {
        console.error("Error conforming friend request:", error.message);
    }
}
function sendMessage(userid,username) {
    window.location.href = `http://localhost:3000/messenger/chatUI.php?receiver_id=${userid}&receiver_name=${username}`;
}
</script>