<?php
include('connection.php');
session_start();
$quesId=$_REQUEST['val'];//post id
$user=$_SESSION['user'];
$getUid=mysql_query("select userid from user where uname='$user'");//user id
$res=mysql_fetch_assoc($getUid);
$uid=$res['userid'];
mysql_query("delete from quesstatus where quesId=\"$quesId\"  and userId=\"$uid\" and status=\"unlike\"");
$qt=mysql_query("select *  from quesstatus where quesId=\"$quesId\"  and userId=\"$uid\" and status=\"like\"");
if(!mysql_num_rows($qt)){
mysql_query("insert into quesstatus values('','$quesId','$uid','like')");
}



//to refresh the stat portion//
$ques_id=$quesId;
$likeid="like".$ques_id;
$unlikeid="unlike".$ques_id;

$likeAndUnlikeStatPortionId="likestatPor".$ques_id;//id of like and unlike stat portion

$rate_all_count = mysql_query('SELECT COUNT(*) FROM quesstatus WHERE quesId = "'.$ques_id.'"');
$rate_all_count = mysql_result($rate_all_count, 0);
$rate_like_count =mysql_num_rows(mysql_query("select * from quesstatus where quesId='$ques_id' and status='like'"));
$rate_like_percent =($rate_like_count/$rate_all_count)*100;


$rate_dislike_count = mysql_num_rows(mysql_query("select * from quesstatus where quesId='$ques_id' and status='unlike'"));
$rate_dislike_percent = ($rate_dislike_count/$rate_all_count)*100;
?>
<div class="stat-cnt">
  <div id="<?php echo $likeAndUnlikeStatPortionId; ?>" >
 <div class="rate-count"><?php echo $rate_all_count; ?></div>
 <div class="stat-bar">
     <div class="bg-green" style="width:<?php echo $rate_like_percent; ?>%;"></div>
     <div class="bg-red" style="width:<?php echo $rate_dislike_percent; ?>%"></div>
 </div><!-- stat-bar -->
 <div class="dislike-count"><?php echo $rate_dislike_count; ?></div>
 <div class="like-count"><?php echo $rate_like_count; ?></div>
</div>
</div><!-- /stat-cnt -->
