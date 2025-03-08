<?php
    include 'mainlayout.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile UI</title>
    <link rel="stylesheet" href="../css/home.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

       /* Parent Wrapper */
       .user-wrapper {
            position: relative;
            width: 100%;
            display: inline-block;
        }

        /* Background Container */
        .user-container-bg {
            position: absolute;
            top: 0;
            left: 0;
            background-color: #dcdfe4;
            border-radius: 10px;
            width: 100%;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding-right: 15px;
            gap: 20px;
            z-index: 1;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.15);
        }

        /* Icons */
        .user-container-bg i {
            font-size: 18px;
            color: #333;
            cursor: pointer;
            transition: transform 0.2s ease-in-out;
        }

        .user-container-bg i:hover {
            transform: scale(1.2);
            color: #1877f2;
        }

        /* Main User Container */
        .user-container {
            display: flex;
            align-items: center;
            background-color: #f0f2f5;
            border-radius: 10px;
            padding: 10px 15px;
            width: 100%;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 2;
            transition: width 0.3s ease-in-out;
        }

        /* Profile Picture */
        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #1877f2;
        }

        /* User Name */
        .user-name {
            margin-left: 10px;
            font-size: 16px;
            font-weight: bold;
            color: #333;
            white-space: nowrap;
            transition: opacity 0.3s ease-in-out;
        }

        /* Hover Effect - Shrink to 60% */
        .user-wrapper:hover .user-container {
            width: 60%;
        }

        /* Hide Name When Shrinking */
        .user-wrapper:hover .user-name {
            opacity: 0;
        }

    </style>
</head>
<body>
    <div id="main">
        <div id="media">
            <div class="user-wrapper">
            <!-- Background Layer with Icons -->
                <div class="user-container-bg">
                    <i class="fa-solid fa-user-plus" title="Add Friend"></i>
                    <i class="fa-solid fa-eye" title="View Profile"></i>
                </div>

                <!-- Main User Container -->
                <div class="user-container">
                    <img src="https://via.placeholder.com/50" alt="Profile Picture" class="profile-pic">
                    <span class="user-name">John Doe</span>
                </div>
            </div>
        </div>
    </div>
   

</body>
</html>
