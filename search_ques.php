<?php
error_reporting(0);
include('connection.php');
$Ques=$_REQUEST['Ques'];//i want this value
$keywords=mysql_escape_string($Ques);
$que=mysql_query("select * from ask_ques where ques_text like '%{$keywords}%'");
while($res=mysql_fetch_assoc($que))
{
  //details of particular individual question//
  $qid=$res['ques_id'];
  echo "<a href='view_ques.php?ques=$qid'>";
echo $res['ques_text'];
echo "</a>";
echo "<br/>";
}
 ?>
