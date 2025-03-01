<?php
include "database/config.php";
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $birthday=$_POST["birthday"];
    $current_location=$_POST["current_location"];
    $hometown=$_POST["hometown"];
    $education=$_POST["education"];
    $bio=$_POST["bio"];

    $profile_picture="";
    if(isset($_FILES['profile_picture'])&& $_FILES['profile_picture']['erroe']==0){
        $targer_dir="uplodes/";
        $traget_file=$targer_dir.basename($_FILES["profile_picture"]["name"]);
        $imageFileType=strtolower(pathinfo($traget_file,PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
        if ($check !== false) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = $target_file; // Store file path
            } else {
                echo "Error uploading file.";
                exit;
            }
        } else {
            echo "File is not an image.";
            exit;
        }
            
    $sql="INSERT INTO user_profiles(birthday,current_location,hometown,education,bio,profile_picture) Values('$birthday','$current_location','$hometown','$education','$bio','$profile_picture')";
    if($con->query($sql)===True){
        header("Location:index.php");
        echo"Registration Succesful! ";
    }else{
        echo "Error:",$con->error;
    }
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
<form action="" method="post">
        <label for="">Birthday</label>
        <input type="date" name="birthday" required><br>
        <label for="">Current Location</label>
        <input type="text" name="current_location" required>
        <label for="">HomeTown</label>
        <input type="text" name="howntown" required>
        <label for="">Education</label>
        <input type="text" name="education" required>
        <label for="">Bio</label>
        <input type="text" name="bio" >
        <input type="file" name="profile_picture" required>
        <button type="submit" name="submit">Upload</button>
        <button type="submit">Login</button>
    </form>
</body>
</body>
</html>