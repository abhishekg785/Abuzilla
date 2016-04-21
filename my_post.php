<?php
session_start();
include('connection.php');
$e=$_SESSION['user'];
$query=mysql_fetch_assoc(mysql_query("select userid  from user where uname='$e'  "));
$uid=$query['userid'];//user_id of user logged in
$query=mysql_query("select * from user_post where user_id='$uid' order by post_id desc" );
while($res=mysql_fetch_assoc($query))
{
  $pid=$res['post_id'];
  $p_pic=$res['post_pic'];
  $p_time=$res['post_time'];
  echo "<h1>$p_time</h1>";
  echo "<img height='500px' width='600px' src=\"users/male/$e/post/$p_pic\" />";
  echo $res['post_txt'];
echo "<a href=\"del_post.php?pot=$pid\">Delete</a>";
  echo "<br/>";
  echo "<hr/>";
}
?>
