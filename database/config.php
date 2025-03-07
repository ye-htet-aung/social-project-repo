<?php

$host="localhost:3308";
$user="root";
$pass="";
$dbname="social_app_db";

$con=new mysqli($host,$user,$pass);

if($con->connect_error){
    die("Connection failed:".$con->connect_error);
}

$sql="Create database if not exists $dbname";
if($con->query($sql)==TRUE){
    echo"Database checked and created successfully<br>";
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
    echo "Users table checked and created successfully.";
} else {
    die("Error creating table: " . $con->error);
}

?>
