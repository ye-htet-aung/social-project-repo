<?php
session_start();
include __DIR__ . '/../database/config.php';

if(!isset($_SESSION['user_id'])){
    echo "You must log in first.";
    exit;
}
include 'mainlayout.php';

$user_id=$_SESSION["user_id"];
$sql = "SELECT name, profile_picture FROM users u
        LEFT JOIN user_profiles p ON u.id = p.user_id
        WHERE u.id = '$user_id'";


$result=$con->query($sql);

if($result->num_rows>0){
    $row=$result->fetch_assoc();
    $name=$row["name"];
    $profile_picture=$row["profile_picture"];
}else{
    echo "User profile not found.";
}
?>
<link rel="stylesheet" href="/css/home.css">
<link rel="stylesheet" href="/css/addpost.css">
<link rel="stylesheet" href="../css/home.css">
<div id="main">
        <div id="media">
            <div id="addnewpost">
                <div id="profile">
                <?php if (!empty($profile_picture)) : ?>
                    <img src="<?php echo "http://localhost:3000/".htmlspecialchars($profile_picture); ?>" alt="Profile Picture">
                    <?php else : ?>
                        <img src="default_profile_picture.jpg" alt="Default Profile Picture" width="50" height="50">
                    <?php endif; ?>
                </div>
                <button id="addbutton" href="addpost.php">Add a Post</button>
                <a href="">
                <i class="fa-solid fa-image fa-xl" style="color: #005eff;"></i>
                </a>
            </div>
        <div id="addpost">
            <div id="nav">
                <h2><i class="fa-solid fa-xmark" id="cancelbutton"></i> Create post</h2>
                <button id="postbutton">POST</button>
            </div>
            <form action="" id="postform">
                <input type="text" id="postcontenttext" placeholder="What's on your mind?">
            </form>
                <div id="addpostnode">
                    <button id="addPhoto">Photo</button>
                    <button id="addVideo">Video</button>
                </div>
        </div>

            <div id="stories">
                <div id="stories-video-div">
                    <div id="addstory">
                        <div id="nav" >
                            <button id="poststorybutton">POST</button>
                            <h4 id="storyde" style="width:100%; text-align: center;">Add Story</h4>
                        </div>
                        <form action="" id="storyform">
                            <input type="file" id="poststory" name="story" accept="video/*" style="display: none;">
                        </form>
                        <div id="proimg">
                            <img src="<?php echo "http://localhost:3000/".htmlspecialchars($profile_picture); ?>" alt="">
                        </div>
                    </div>
                    
                </div>
            </div>
            <div id="post-container">

            </div>
        </div>
</div>
<script src="../javascript/addpost.js"></script>