<?php
session_start();
$uid=$_SESSION['uid'];
$e=$_SESSION['user'];
include('connection.php');
extract($_REQUEST);
mysql_query("insert into broadcastQuery values('','$bId','$uid','$queryText','$qTime')");
?>
<?php
$queries=mysql_query("select * from broadcastQuery where bId='$bId'");
$countOfQueries=mysql_num_rows($queries);
?>
<p>QUERIES:<?php echo $countOfQueries; ?></p>
<?php
while($res=mysql_fetch_assoc($queries))
{
  $queryId=$res['queryId'];//id of the query
  $qText=$res['qText'];
  $qTime=$res['qTime'];
  $userIdOfQuery=$res['userId'];
  $userDetOfQuery=mysql_fetch_assoc(mysql_query("select fname,lname from user where userid='$userIdOfQuery'"));//details of the user who asked the query//
  $queryUser=$userDetOfQuery['fname']." ".$userDetOfQuery['lname'];
  echo "<h1>$qText</h1>";
  echo $queryUser;
  echo $qTime;
  $replyTextId="replyText".$queryId;
  $replyButId="replyBut".$queryId;
  $replyDelButId="replyDelBut".$queryId;
  $replySecDivId="replySecDiv".$queryId;
?>
<br/>
<input class="replyText" id="<?php echo $replyTextId; ?>" type="text" placeholder="Write reply..." />
<div class="replyBut" onclick="postReplyToQuery(<?php echo $queryId; ?>)" id="<?php echo $replyButId; ?>">Reply</div>
<!--delete button should appear if the the user of the query is the current user or the current user is the user of the broadcast -->
<?php
if($userIdOfQuery==$uid || $userOfBroadcastId==$uid)
{
?>
<div class="replyBut" id="<?php echo $replyDelButId; ?>" onclick="deleteQuery(<?php echo $bId; ?>,<?php echo $queryId; ?>)">Delete</div>
<?php
}
?>
<br/>
<br/>
<div id="<?php echo $replySecDivId; ?>" class="showAllRepliesToQuery">
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
<div class="replyDelBut" id="<?php echo $rId; ?>" onclick="delReplyOfQueryOfBroadcast(<?php echo $rId; ?>,<?php echo $queryId;?>)">Delete</div>
<?php
}
echo "<br/>";
}
?>
</div>
<?php
echo "<br/>";
}
?>
