<?php
include('connection.php');
session_start();
$postId=$_REQUEST['val'];//post id
$user=$_SESSION['user'];
$getUid=mysql_query("select userid from user where uname='$user'");//user id
$res=mysql_fetch_assoc($getUid);
$uid=$res['userid'];
mysql_query("delete from post_status where post_id=\"$postId\"  and user_id=\"$uid\" and status=\"like\"");
$qt=mysql_query("select * from post_status where post_id=\"$postId\"  and user_id=\"$uid\" and status=\"unlike\"");
if(!mysql_num_rows($qt)){
mysql_query("insert into post_status values('','$postId','$uid','unlike')");
}

$pid=$postId;
$likeid="like".$pid;//id of each like button//
$unlikeid="unlike".$pid;//id of each unlike button//

$postLikeStatId="postStatId".$pid;

//like and ulike section starts here//
$rate_all_count = mysql_query('SELECT COUNT(*) FROM post_status WHERE post_id = "'.$pid.'"');
$rate_all_count = mysql_result($rate_all_count, 0);
$rate_like_count =mysql_num_rows(mysql_query("select * from post_status where post_id='$pid' and status='like'"));
$rate_like_percent =($rate_like_count/$rate_all_count)*100;


$rate_dislike_count = mysql_num_rows(mysql_query("select * from post_status where post_id='$pid' and status='unlike'"));
$rate_dislike_percent = ($rate_dislike_count/$rate_all_count)*100;


//like and unlike section ends here//


?>
<div  class="stat-cnt">
<div id="<?php echo $postLikeStatId; ?>" >
<div  class="rate-count"><?php echo $rate_all_count; ?></div>
<div  class="stat-bar">
<div  class="bg-green" style="width:<?php echo $rate_like_percent; ?>%;"></div>
<div  class="bg-red" style="width:<?php echo $rate_dislike_percent; ?>%"></div>
</div><!-- stat-bar -->
<div  class="dislike-count"><?php echo $rate_dislike_count; ?></div>
<div  class="like-count"><?php echo $rate_like_count; ?></div>
</div>
</div><!-- /stat-cnt -->
