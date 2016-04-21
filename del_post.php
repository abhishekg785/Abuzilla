<?php
session_start();
error_reporting(0);
$e=$_SESSION['user'];
include('connection.php');
$pid=$_GET['pot'];
echo $pid;
$qt=mysql_query("select post_pic from user_post where post_id='$pid'");
unlink("users/$")
mysql_query("delete from user_post where post_id='$pid'");
header('location:user.php?x=post');
?>
