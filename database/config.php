<?php

<<<<<<< HEAD
$host="localhost:3306";
=======
$host="localhost:3308";
>>>>>>> 7d673ca7e012b176e773f99458db5fc3c7fae880
$user="root";
$pass="";
$dbname="social_app_db";

$con=new mysqli($host,$user,$pass);

if($con->connect_error){
    die("Connection failed:".$con->connect_error);
}

$sql="Create database if not exists $dbname";
if($con->query($sql)==TRUE){
  
}else{
    die("Erroe creating database:".$con->error);
}
$con->select_db($dbname);

$table_sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$table_sql = "CREATE TABLE if not exists chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);
";
if ($con->query($table_sql) === TRUE) {
    
} else {
    die("Error creating table: " . $con->error);
}

$profile_table_sql="CREATE TABLE IF NOT EXISTS user_profiles(
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    birthday DATE,
    current_location VARCHAR(200),
    hometown VARCHAR(200),
    educatione varchar(200),
    bio TEXT,
    profile_picture LONGBLOB,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE


)";
if($con->query($profile_table_sql)===TRUE){
 
}else{
    die("Error creating user_profiles table" .$con->error);
}
?>
