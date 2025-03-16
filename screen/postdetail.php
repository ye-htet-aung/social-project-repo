<?php
session_start();
include __DIR__ . '/../database/config.php';

if(!isset($_SESSION['user_id'])){
    echo "You must log in first.";
    exit;
}
include 'mainlayout.php';

$user_id=$_SESSION["user_id"];

$query = isset($_GET['post_id']) ? $con->real_escape_string($_GET['post_id']) : '';
$_SESSION['post_id']=$query;

?>
<link rel="stylesheet" href="/css/home.css">
<link rel="stylesheet" href="/css/addpost.css">
<link rel="stylesheet" href="../css/home.css">
<div id="main">
        <div id="media">
            
        </div>
</div>
<script src="../javascript/postbyid.js"></script>