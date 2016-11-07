<?php
session_start();
include('connection.php');
extract($_REQUEST);
mysql_query("delete from replyToQueryOfBroadcast where rId='$replyId'");
?>
<?php
$query=mysql_query("select * from replyToQueryOfBroadcast where qId='$queryId'");
$countReplies=mysql_num_rows($query);
 ?>
 Replies:<?php echo $countReplies; ?>
 <br/>
 <?php
while($res=mysql_fetch_assoc($query))
{
$rId=$res['rId'];
$userId=$res['userId'];
$replyText=$res['replyText'];
$uname=mysql_fetch_assoc(mysql_query("select fname,lname from user where userid='$userId'"));
$name=$uname['fname']." ".$uname['lname'].":";
echo $name;
echo $replyText;
//id for the delete button for deleting the reply to  a query//
$delReplyButId="delReplyBut".$rId;
if($userId==$uid || $userOfBroadcastId==$uid)
{
?>
?>
<div class="replyDelBut" id="<?php echo $rId; ?>" onclick="delReplyOfQueryOfBroadcast(<?php echo $rId; ?>,<?php echo $queryId;?>)">Delete</div>
<?php
}
echo "<br/>";
}
?>
