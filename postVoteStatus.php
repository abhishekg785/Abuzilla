<?php
session_start();
$e=$_SESSION['user'];
$uid=$_SESSION['uid'];
include('connection.php');
$voteId=$_REQUEST['vId'];
$action=$_REQUEST['action'];
 $chk=mysql_query("select * from voteStatus where voteId='$voteId' and userId='$uid'");
 if(mysql_num_rows($chk))
 {
//if such pair exists that means user has voted earlier so we update the new vote//
 mysql_query("update voteStatus set status='$action' where voteId='$voteId' and userId='$uid'");
 }
 else
 {
   //no user's vote exists  so we insert the new vote//
   mysql_query("insert into voteStatus values('','$voteId','$uid','$action')");
 }

//this all starts for the ajax part //
$idOfvoteStatDiv="voteStatDiv".$voteId;
//calculation for the voting section starts here //
$totalVoteCount=mysql_query('SELECT COUNT(*) FROM voteStatus WHERE voteId = "'.$voteId.'"');
$totalVoteCount = mysql_result($totalVoteCount, 0);
$upVoteCount=mysql_num_rows(mysql_query("select * from voteStatus where voteId='$voteId' and status='upVote'"));
$downVoteCount=mysql_num_rows(mysql_query("select * from voteStatus where voteId='$voteId' and status='downVote'"));
$cantSayCount=mysql_num_rows(mysql_query("select * from voteStatus where voteId='$voteId' and status='cantSay'"));

$upVotePercent =($upVoteCount/$totalVoteCount)*100;
$downVotePercent = ($downVoteCount/$totalVoteCount)*100;
$cantSayPercent=($cantSayCount/$totalVoteCount)*100;
//calculation ends here bro!!!!  //
?>
<!--to display the changes made in the vote dynamically -->
<div class="voteStatSec" id="<?php echo $idOfvoteStatDiv; ?>" >
<p>Total no of votes:<?php echo $totalVoteCount; ?></p><br/>
<p>No of upvotes:<?php echo $upVoteCount; ?></p><br/>
<p>No of Downvotes:<?php echo $downVoteCount; ?></p><br/>
<p>No of peoples who say nothing:<?php echo $cantSayCount; ?></p><br/>
<p>%of upvotes:<?php echo $upVotePercent; ?></p><br/>
<p>%of downvotes:<?php echo $downVotePercent; ?></p><br/>
<p>%of can't say:<?php echo $cantSayPercent; ?> </p>
</div>
