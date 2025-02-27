<?php
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
    if($con->query($sql)===True){
        header("Location:Home.php");
        echo"Registration Succesful! ";
    }else{
        echo "Error:",$con->error;
    }
}
  



?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h2>Sign Up Page</h2>
    <form action="" method="post">
        <label for="">Name:</label>
        <input type="text" name="name" required><br>
        <label for="">Email:</label>
        <input type="email" name="email" required><br>
        <label for="">Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>