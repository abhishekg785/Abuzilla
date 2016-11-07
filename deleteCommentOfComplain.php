<?php
session_start();
include('connection.php');
extract($_REQUEST);
$uid=$_SESSION['uid'];
$e=$_SESSION['user'];
$cId=$complainId;
mysql_query("delete from commentToComplain where commentId='$commentId'");
$commentQuery=mysql_query("select * from commentToComplain where complainId='$cId'");
$countOfComments=mysql_num_rows($commentQuery);
 ?>
 Comments:<?php echo $countOfComments; ?>
 <br/>
 <?php
 while($res=mysql_fetch_assoc($commentQuery))
 {
   $commentId=$res['commentId'];
   $userOfCommentId=$res['userId'];
   $commentText=$res['commentText'];
   $res=mysql_fetch_assoc(mysql_query("select fname,lname from user where userid='$userOfCommentId'"));
   $userOfCommentName=$res['fname']." ".$res['lname'];
   echo $userOfCommentName.":";
   echo $commentText;
   //id for the button for deleting the comments//
   $deleteCommentButId="deleteCommentBut".$commentId;
   if($userOfCommentId==$uid||$userIdOfComplain==$uid)
   {
   ?>
  <div class="deleteComment" id="<?php echo $deleteCommentButId; ?>" onclick="deleteCommentOfComplain(<?php echo $cId; ?>,<?php echo $commentId; ?>)">Delete Comment</div>
   <?php
   }
   echo "<br/>";
   }
  ?>
