<?php
session_start();
include('connection.php');
extract($_REQUEST);
$e=$_SESSION['user'];
$uid=$_SESSION['uid'];//id of the user currenetly logged in
$titleText=$titleText;
$broadcastText=$broadcastText;
$fileName=$fileName;
$time=$time;
mysql_query("insert into userBroadcast values('','$uid','$titleText','$broadcastText','$fileName','$time')");
//upload the files//
?>
<!--this section is for displaying all the broadcasts -->
<h1 style="text-align:center">Previous queries</h1>
<!-- this div is for showing the all the previous broadcasts -->
<div id="idForViewBroadcastDiv">
<?php
$query=mysql_query("Select * from userBroadcast order by bId desc");
while($res=mysql_fetch_assoc($query))
{
  $bId=$res['bId'];
  $userOfBroadcastId=$res['userId'];
  $bTitle=$res['bTitle'];
  $bText=$res['broadcastText'];
  $fileName=$res['fileName'];
  $time=$res['time'];

  //id for each div corresponding to a particular broadcast//
  $broadcastDivId="broadcastDiv".$bId;
  $deleteButtonId="deleteBut".$bId;

  //ids for query section buttons//
  $queryTextId="queryText".$bId;//id for the each  query text box//
  $submitQueryButId="submitQueryBut".$bId;
  $showAllQueriesDivId="showAllQueriesDiv".$bId;
  //query section ends here//

?>
<div id="<?php echo $broadcastDivId; ?>" class="classForEachBroadcast">
<h1><?php echo $bTitle; ?></h1>
<?php echo $bText; ?><br/>
<?php echo $time; ?>
<br/>
<!--query section starts here -->
<input type="text" placeholder="Enter your query here..." id="<?php echo $queryTextId; ?>"/>
<input type="submit" value="Submit Query" onclick="postQuery(<?php echo $bId; ?>)" id="<?php echo $submitQueryButId; ?>" />
<!--query section ends here -->
<?php
if($userOfBroadcastId==$uid)
{
 ?>
<div class="delBroadcastButton" id="<?php echo $deleteButtonId; ?>" onclick="deleteBroadcast(<?php echo $bId;  ?>)">Delete</div>
<?php
}
 ?>
<!--showing previous queries -->
<div id="<?php echo $showAllQueriesDivId; ?>" class="showAllQueries">
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
</div>
<!-- showing all queries ende here -->
</div>
<?php
echo "<br/>";

}
?>
</div>
