<?php
session_start();
include 'database/config.php';
if($_SERVER["REQUEST_METHOD"]=="POST"){
    
    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];

    $name=mysqli_real_escape_string($con,$name);
    $email=mysqli_real_escape_string($con,$email);
    $password=mysqli_real_escape_string($con,$password);

    $hashed_Password=password_hash($password,PASSWORD_DEFAULT);

    $sql="INSERT INTO users (name,email,password) Values('$name','$email','$hashed_Password')";
    if($con->query($sql) === True) {
        $user_id = $con->insert_id;
        $_SESSION['user_id'] = $user_id;
        header("Location: profiledata.php");
        exit();
    } else {
        echo "Error: " . $con->error;
    }
    
}
  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/usersignup.css">
</head>
<body>
    <div class="container">
        <h2>Sign Up</h2>
        <form action="" method="post">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Sign Up</button>
        </form>
    </div>
    <div class="content">
        <img src="your-image.jpg" alt="Website Image" width="400">
        <p>Blah Blah</p>
    </div>
</body>
</html>