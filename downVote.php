<?php
include('connection.php');
session_start();
$ansId=$_REQUEST['val'];//ansid
$user=$_SESSION['user'];
$getUid=mysql_query("select userid from user where uname='$user'");//user id
$res=mysql_fetch_assoc($getUid);
$uid=$res['userid'];
mysql_query("delete from ans_status where ansId=\"$ansId\"  and userId=\"$uid\" and status=\"upVote\"");
$qt=mysql_query("select *  from ans_status where ansId=\"$ansId\"  and userId=\"$uid\" and status=\"downVote\"");
if(!mysql_num_rows($qt)){
mysql_query("insert into ans_status values('','$ansId','$uid','downVote')");
}

$upVoteId="upVote".$ansId;//id of the upvote button//
$downVoteId="downVote".$ansId;//id of the down vote button//

$upDownVoteStatId="voteStatPor".$ansId;//id of the upvote and downvote stat portion id//


$checkVote=mysql_query("select * from ans_status where ansId='$ansId' and userId='$uid'");
$act=mysql_fetch_assoc($checkVote);
$voteAction=$act['status'];
$totalVoteCount = mysql_query('SELECT COUNT(*) FROM ans_status WHERE ansId = "'.$ansId.'"');
$totalVoteCount = mysql_result($totalVoteCount, 0);
 $upVoteCount=mysql_num_rows(mysql_query("select * from ans_status where ansId='$ansId' and status='upVote'"));
$upVotePercent =($upVoteCount/$totalVoteCount)*100;
$downVoteCount = mysql_num_rows(mysql_query("select * from ans_status where ansId='$ansId' and status='downVote'"));
$downVotePercent = ($downVoteCount/$totalVoteCount)*100;

?>
<div class="stat-cnt">
  <div id="<?php echo $upDownVoteStatId; ?>" >
 <div class="rate-count"><?php echo $totalVoteCount; ?></div>
 <div class="stat-bar">
     <div class="bg-green" style="width:<?php echo $upVotePercent; ?>%;"></div>
     <div class="bg-red" style="width:<?php echo $downVotePercent; ?>%"></div>
 </div>
 <div class="dislike-count"><?php echo $downVoteCount; ?></div>
 <div class="like-count"><?php echo $upVoteCount; ?></div>
 </div>
</div>
