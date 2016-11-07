<?php
session_start();
include('connection.php');
extract($_REQUEST);
$uid=$_SESSION['uid'];
$e=$_SESSION['user'];
//insert into table replyTocommentOfPost values are replyId,commentId,userId,replyText
mysql_query("insert into replyTocommentOfPost values('','$commentId','$uid','$replyText')");
 ?>
 <?php
 $detOfUserOfComment=mysql_fetch_assoc(mysql_query("select user_id from user_comment where  comment_id='$commentId'"));
 $userIdOfComment=$detOfUserOfComment['user_id'];
 $replyQ=mysql_query("select * from replyTocommentOfPost where commentId='$commentId'");
 $countOfReplies=mysql_num_rows($replyQ);
 echo "Replies".":".$countOfReplies;
 echo "<br/>";
 while($res=mysql_fetch_assoc($replyQ))
 {
 $replyId=$res['replyId'];
 $commentId=$res['commentId'];
 $userOfReplyId=$res['userId'];
 $replyText=$res['replyText'];
 $userOfReply=mysql_fetch_assoc(mysql_query("select fname,lname from user where userid='$userOfReplyId'"));
 echo $userOfReply['fname']." ".$userOfReply['lname'].":".$replyText;

 $delReplyOfPostButId="delReplyOfPostBut".$replyId;//this is for deleting the reply to the comment of the post//
 if($userOfReplyId==$uid||$userIdOfComment==$uid)
 {
 ?>
 <!--section fr deeting the reply on comment of the post-->
 <div class="delReplyBut" id="<?php echo $delReplyOfPostButId; ?>" onclick="deleteReplyOfPost(<?php echo $replyId; ?>,<?php echo $commentId;?>)">Delete</div>
 <?php
 }
 echo "<br/>";
 }
?>
