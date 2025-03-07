<?php
session_start();
include 'database/config.php';

if($_SERVER['REQUEST_METHOD']=='POST'){
    $email=$_POST['email'];
    $password=$_POST['password'];

    $email=mysqli_real_escape_string($con,$email);
    $password=mysqli_real_escape_string($con,$password);

    $sql="SELECT * FROM users where email='$email' LIMIT 1";
    $data=$con->query($sql);

    if($data->num_rows>0){
        $user=$data->fetch_assoc();
        
        if(password_verify($password,$user['password'])){
            $_SESSION['user_id']=$user['id'];
            $_SESSION['user_name']=$user['name'];
            header("Location: screen/Home.php");
            exit();
        }else{
            $error="Invalid Password.";
        }
    }else{
        $error="User not Found";
    }

}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/userlogin.css">
    <title>Document</title>
</head>
<body>
    <h2>Login page</h2>
<div class="container">
    <form action="" method="post">
        <label for="">Email:</label>
        <input type="email" name="email" required><br>
        <label for="">Password:</label>
        <input type="password" name="password" required>
        <button type="submit">Login</button>
        <?php if (!empty($error)): ?>
        <p style="color: red;"><?php echo $error; ?></p>
        <button onclick="window.location.href='usersignup.php'">Go to Sign Up</button>
    <?php endif; ?>
    </form>
</div>
</body>
</html>