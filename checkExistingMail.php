<?php
include('connection.php');
session_start();
$txt=$_REQUEST['val'];
$quer=mysql_query("select uname from user where uname like '%{$txt}%'");
if(mysql_num_rows($quer))
{
	echo "<p style='color:red'>user exists</p>";
}
else
{
	echo "<p style='color:green'>Email oK</p>";
}
?>
