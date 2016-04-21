<?php
error_reporting(0);
include('connection.php');
$Name=$_REQUEST['Name'];//i want this value
$keywords=mysql_escape_string($Name);
$que=mysql_query("select fname,lname from user where fname like '%{$keywords}%' or lname like '%{$keywords}%'");
while($res=mysql_fetch_assoc($que))
{
  $fnam=$res['fname'];
  echo "<a href='viewpro.php?profile=$fnam'>";
  echo $res['fname'].' '.$res['lname'];
  echo "</a>";
  echo "<br/>";
}
 ?>
