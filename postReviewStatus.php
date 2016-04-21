<?php
session_start();
include('connection.php');
$e=$_SESSION['user'];                             //email id of user or username
$uid=$_SESSION['uid'];                           //user id of the current user
$revId=$_REQUEST['revId'];                           //id of the review currently being accessed//
$action=$_REQUEST['action'];                        //like or unlike

if($action=="like"){
mysql_query("delete from reviewStatus where reviewId='$revId' and userId='$uid' and status='unlike'");
$query=mysql_query("select * from reviewStatus where reviewId='$revId' and userId='$uid' and status='like'");
if(!mysql_num_rows($query)){
mysql_query("insert into reviewStatus values('','$revId','$uid','like')");
}
}
else if($action=="unlike"){
mysql_query("delete from reviewStatus where reviewId='$revId' and userId='$uid' and status='like'");
$query=mysql_query("select * from reviewStatus where reviewId='$revId' and userId='$uid' and status='unlike'");
if(!mysql_num_rows($query)){
mysql_query("insert into reviewStatus values('','$revId','$uid','unlike')");
}
}

$reviewId=$revId;
//review stat section//
$idForRevStatSec="reviewStatSec".$reviewId;//id of each  review section
$totalVoteCount = mysql_query('SELECT COUNT(*) FROM reviewStatus WHERE reviewId = "'.$reviewId.'"');
$totalVoteCount = mysql_result($totalVoteCount, 0);
$upVoteCount=mysql_num_rows(mysql_query("select * from reviewStatus where reviewId='$reviewId' and status='like'"));
$upVotePercent =($upVoteCount/$totalVoteCount)*100;
$downVoteCount = mysql_num_rows(mysql_query("select * from reviewStatus where reviewId='$reviewId' and status='unlike'"));
$downVotePercent = ($downVoteCount/$totalVoteCount)*100;
//review stat  section ends here//
 ?>

     <div class="stat-cnt">
     <div id="<?php echo $idForRevStatSec; ?>" >
     <div class="rate-count"><?php echo $totalVoteCount; ?></div>
     <div class="stat-bar">
     <div class="bg-green" style="width:<?php echo $upVotePercent; ?>%;"></div>
     <div class="bg-red" style="width:<?php echo $downVotePercent; ?>%"></div>
     </div>
     <div class="dislike-count"><?php echo $downVoteCount; ?></div>
     <div class="like-count"><?php echo $upVoteCount; ?></div>
     </div>
     </div>
