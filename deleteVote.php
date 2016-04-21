<?php
session_start();
extract($_REQUEST);
include('connection.php');
$e=$_SESSION['user'];
$uid=$_SESSION['uid'];
$gen=$_SESSION['gender'];
$voteId=$vId;
mysql_query("delete from userVote where voteId='$voteId' ");
mysql_query("delete from voteStatus where voteId='$voteId'");
?>
<section id="viewAllVotes">
<?php
$query=mysql_query("select * from userVote order by voteId desc");
while($res=mysql_fetch_assoc($query))
{
$voteId=$res['voteId'];
$userOfVoteId=$res['userId'];//user who gave the issue of the vote//
$resOfUser=mysql_fetch_assoc(mysql_query("select gender,uname from user where userid='$userOfVoteId'"));
$genderOfVoter=$resOfUser['gender'];//gender of the user corresponding to a prticular vote//
$unameOfVoter=$resOfUser['uname'];//username of the user corresponing to a particular vote
$voteText=$res['voteText'];
$fileName=$res['fileName'];
$voteTime=$res['voteTime'];
//details of the user who gave the issue of the vote//
$res=mysql_fetch_assoc(mysql_query("select * from user where userid='$userOfVoteId'"));

//detils of the user ends here//
//section in which all the vote details will be displayed//
$idOfVoteSec="voteSec".$voteId;//id of ech section showing the vote details//
$userOfVote=$res['fname']." ".$res['lname'];

//ids of vote buttons//
$idOfUpvoteBut="upvoteBut".$voteId;
$idOfDownVoteBut="downVoteBut".$voteId;
$idOfCantSayBut="cantSayBut".$voteId;

//vote buttons id section ends here//

$idOfDelVoteBut="delVoteBut".$voteId;//id for the delete button//

//id for the section div conatining the stat portion//
$idOfvoteStatDiv="voteStatDiv".$voteId;
//ends here//
//calculation of the votesecId//
$totalVoteCount=mysql_query('SELECT COUNT(*) FROM voteStatus WHERE voteId = "'.$voteId.'"');
$totalVoteCount=mysql_result($totalVoteCount, 0);
$upVoteCount=mysql_num_rows(mysql_query("select * from voteStatus where voteId='$voteId' and status='upVote'"));
$downVoteCount=mysql_num_rows(mysql_query("select * from voteStatus where voteId='$voteId' and status='downVote'"));
$cantSayCount=mysql_num_rows(mysql_query("select * from voteStatus where voteId='$voteId' and status='cantSay'"));

$upVotePercent =($upVoteCount/$totalVoteCount)*100;
$downVotePercent = ($downVoteCount/$totalVoteCount)*100;
$cantSayPercent=($cantSayCount/$totalVoteCount)*100;
//calculation ends here//

//to check whether the button has been pressed//
$chkStatus=mysql_fetch_assoc(mysql_query("select * from voteStatus where voteId='$voteId' and userId='$uid'"));
$status=$chkStatus['status'];
//button check ends here//

?>
<div class="voteSec" id="<?php echo $idOfVoteSec; ?>" >
<?php
echo $userOfVote;
echo "</br>";
echo $voteTime;
?>
<h1><?php echo $voteText; ?></h1>
<br/>
<?php
if($fileName==""){
}
else
{
echo "<img height='200px' width='400px' src='users/$genderOfVoter/$unameOfVoter/voteFiles/$fileName' />";
}
if($userOfVoteId==$uid)
{
?>
<div class="delVoteButClass" id="<?php echo $idOfDelVoteBut; ?>" onclick="deleteVote(<?php echo $voteId; ?>)">Delete Vote</div>
<?php
}
 ?>
<div class="butDiv">
<!-- section of the upvote,downvote and nothing buttons-->
<div class="butSec  <?php if($status=="upVote"){echo 'upvoteClick' ;}  ?>" id="<?php echo $idOfUpvoteBut; ?>" onclick="postVoteStatus(<?php echo $voteId; ?>,'upVote')">UpVote</div>
<div class="butSec  <?php if($status=="downVote"){echo 'downVoteClick' ;} ?>" id="<?php echo $idOfDownVoteBut; ?>" onclick="postVoteStatus(<?php echo $voteId; ?>,'downVote')">DownVote</div>
<div class="butSec  <?php if($status=="cantSay"){echo 'cantSayClick' ;} ?>" id="<?php echo $idOfCantSayBut; ?>" onclick="postVoteStatus(<?php echo $voteId; ?>,'cantSay')">Can'T Say</div>
<!-- button section ends here -->
</div>
<div class="voteStatSec" id="<?php echo $idOfvoteStatDiv; ?>" >
<p>Total no of votes:<?php echo $totalVoteCount; ?></p><br/>
<p>No of upvotes:<?php echo $upVoteCount; ?></p><br/>
<p>No of Downvotes:<?php echo $downVoteCount; ?></p><br/>
<p>No of peoples who say nothing:<?php echo $cantSayCount; ?></p><br/>
<p>%of upvotes:<?php echo $upVotePercent; ?></p><br/>
<p>%of downvotes:<?php echo $downVotePercent; ?></p><br/>
<p>%of can't say:<?php echo $cantSayPercent; ?> </p>
</div>
</div>
<?php
}
?>
</section>
