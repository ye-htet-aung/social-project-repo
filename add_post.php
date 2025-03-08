<?php
    $con = mysqli_connect("localhost:3306","root","");
    if (!$con){ 
    die('Could not connect: ');
    }
    $myDB="social_app_db";
    $db= mysqli_select_db($con,$myDB);
    $notificationTableCrate="CREATE TABLE IF NOT EXISTS notifications(
    noti_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    noti_text text NOT NULL,
    noti_status varchar(10) NOT NULL,
    FOREIGN KEY(user_id) references users(id))
    ";
    $notificationTrigger = "DELIMITER $$
    CREATE TRIGGER after_post_insert
    AFTER INSERT ON users
    FOR EACH ROW
    BEGIN
        INSERT INTO notification(user_id,noti_text,noti_status)
        VALUES (userid,notitext,noti_status);
    END $$
    DELIMITER ;";

    $postTableCreate="CREATE TABLE IF NOT EXISTS post( 
    post_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    post_text text,
    post_status Varchar(10) NOT NULL),
    FOREIGN KEY(user_id) references users(id))
    ";

    $postLikeTableCreate="CREATE TABLE IF NOT EXISTS likes(
    like_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    like_count INT NOT NULL,
    FOREIGN KEY(post_id) references post(post_id))";

    $postCommentTableCreate="CREATE TABLE IF NOT EXISTS comment(
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    comment_text text,
    FOREIGN KEY(post_id) references post(post_id))";
    //creating tables
    if(mysqli_query($con,$postTableCreate))
    if(mysqli_query($con,$postLikeTableCreate))
    if(mysqli_query($con,$postCommentTableCreate))
        echo "table createted";
?>
