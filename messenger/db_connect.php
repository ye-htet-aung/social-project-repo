
<?php

$host="localhost:3308";
$user="root";
$pass="";
$dbname="social_app_db";

$mysqli=new mysqli($host,$user,$pass);

if($mysqli->connect_error){
    die("Connection failed:".$con->connect_error);
}

$sql="Create database if not exists $dbname";
// if($con->query($sql)==TRUE){
//     echo"Database checked and created successfully<br>";
// }else{
//     die("Erroe creating database:".$con->error);
// }
$mysqli->select_db($dbname);

$table_sql = "CREATE TABLE IF NOT EXISTS chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);";
if ($mysqli->query($table_sql) === TRUE) {
    echo "";
} else {
    die("Error creating table: " . $mysqli->error);
}

?>
