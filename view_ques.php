<?php
error_reporting(0);
session_start();
extract($_POST);
include('connection.php');
$ques_id=$_GET['ques'];
$e=$_SESSION['user'];
//current user//
$ust=mysql_fetch_assoc(mysql_query("select * from user where uname='$e'"));
$uid=$ust['userid'];
$gen=$ust['gender'];
//ends here//

//deletes the question//
if(isset($sub)){
  $qid=$q_id;
  $rt=mysql_fetch_assoc(mysql_query("select ques_pic from ask_ques where ques_id='$qid'"));
  $pic=$rt['ques_pic'];
  unlink("users/$gen/$e/askQuesFiles/$pic");
  $qy=mysql_query("select * from ans_ques where ques_id='$qid'");
  if(mysql_num_rows($qy)){
    while($res=mysql_fetch_assoc($qy)){
      $aId=$res['ans_id'];
      mysql_query("delete from ans_status where ansId='$aId'");
    }
  }
  mysql_query("delete from ask_ques where ques_id='$q_id'");
  mysql_query("delete from ans_ques where ques_id='$q_id'");
  mysql_query("delete from quesStatus where quesId='$qid'");
  header('location:user.php?x=ask');
}


if(isset($g_ans))
{
  mysql_query("insert into ans_ques values('','$q_id','$uid','$ans_txt')");

}

//write answer text goes here//


//
?>
<html>
<head>
  <title>view Question</title>
  <link rel="stylesheet" type="text/css" href="css/like&unlike.css">
  <style>
  .upvote_click{
    color:green;
  }
  .downvote_click{
    color: red;
  }
  .like_click{
    color:green;
  }
  .unlike_click{
    color:red;
  }
  .deleteButton{
    height:18px;
    width: 50px;
    border: 1px solid black;
    text-align: center;
    padding: 1px;
    cursor: pointer;
  }
  .deleteButton:hover{
    background-color: lightgreen;
  }
  </style>
</head>
<body>
<section>
<?php
$ques=mysql_query("select * from ask_ques where ques_id='$ques_id' ");
$res=mysql_fetch_assoc($ques);
$pic=$res['ques_pic'];
$upid=$res['user_id'];//id of the user who asked the question
//details of particular user//
$ques=mysql_fetch_assoc(mysql_query("select * from user where userid='$upid'"));
$genp=$ques['gender'];
$uname=$ques['uname'];
$text=$res['ques_text'];
echo "Question By:".$ques['fname']." ".$ques['lname'];
echo "<h1>$text</h1>";
if($pic==""){

}
else{
  echo "<br/>";
echo "<img height='500px' width='600px' src='users/$genp/$uname/askQuesFiles/$pic'  />";
}
//php of like and unlike section goes here//
$likeid="like".$ques_id;
$unlikeid="unlike".$ques_id;
$likeAndUnlikeStatPortionId="likestatPor".$ques_id;//id of like and unlike stat portion
 $pStatusQ=mysql_query("select * from quesStatus where quesId='$ques_id' and userId='$uid'");
            $res_status=mysql_fetch_assoc($pStatusQ);
            $action=$res_status['status'];
               //like and ulike section starts here//
            $rate_all_count = mysql_query('SELECT COUNT(*) FROM quesStatus WHERE quesId = "'.$ques_id.'"');
            $rate_all_count = mysql_result($rate_all_count, 0);
             $rate_like_count =mysql_num_rows(mysql_query("select * from quesStatus where quesId='$ques_id' and status='like'"));
            $rate_like_percent =($rate_like_count/$rate_all_count)*100;


            $rate_dislike_count = mysql_num_rows(mysql_query("select * from quesStatus where quesId='$ques_id' and status='unlike'"));
            $rate_dislike_percent = ($rate_dislike_count/$rate_all_count)*100;

//php of like and unlike section ends here//
?>

<!--the like and unlike  section STARTS here -->

<div class="tab-tr" id="t1">
<div id="<?php echo $likeid; ?>"   class="but like-btn <?php if($action=='like'){echo 'like_click' ;} ?>"   onClick="likeQues(<?php echo $ques_id; ?>,'likeQues')">Like</div>
<div id="<?php echo $unlikeid; ?>"  class=" but dislike-btn <?php if($action=='unlike'){echo 'unlike_click' ;} ?>"   onClick="likeQues(<?php echo $ques_id;?>,'unlikeQues')">Unlike</div>

<div class="stat-cnt">
<div id="<?php echo $likeAndUnlikeStatPortionId; ?>" >
 <div class="rate-count"><?php echo $rate_all_count; ?></div>
 <div class="stat-bar">
     <div class="bg-green" style="width:<?php echo $rate_like_percent; ?>%;"></div>
     <div class="bg-red" style="width:<?php echo $rate_dislike_percent; ?>%"></div>
 </div><!-- stat-bar -->
 <div class="dislike-count"><?php echo $rate_dislike_count; ?></div>
 <div class="like-count"><?php echo $rate_like_count; ?></div>
</div><!-- /stat-cnt -->
</div>
</div>
<!--the like and unlike  section ENDS here -->



<?php
if($upid==$uid ){
?>
<form method="post">
<input type="submit" name="sub" value="Delete Question"/>
<input type="hidden" name="q_id" value="<?php echo $ques_id; ?>" />
</form>
<?php
}
?>
<!--write answer section here-->
<form method="post">
<input type="text" name="ans_txt" placeholder="write your answer here...."/>
<input type="hidden" name="q_id" value="<?php echo $ques_id; ?>" />
<input type="submit" name="g_ans" style="display:none;">
</form>
<!--section ends here-->


<?php
//answer section//
$ans=mysql_query("select * from ans_ques where ques_id='$ques_id'");
$count=mysql_num_rows($ans);

$idOfAnsSec="ansSec".$ques_id;//id of each answer section//

?>
<div id="<?php echo $idOfAnsSec; ?>" >
<h1>Answers:<?php echo $count; ?></h1>
<?php
while($a=mysql_fetch_assoc($ans)){
  //user of particular answer//
$a_id=$a['ans_id'];//answer id//
$auid=$a['user_id'];//id of user who gave the answer//
$usel=mysql_query("select * from user where userid='$auid'");
$rt=mysql_fetch_assoc($usel);
$ufname=$rt['fname'];
$ulname=$rt['lname'];
echo "<h1>$ufname&nbsp;$ulname</h1>";
  echo $a['ans_text'];

  //php of upvote section here//
  $upDownVoteStatId="voteStatPor".$a_id;
  $upVoteId="upVote".$a_id;//id of the upvote button//
  $downVoteId="downVote".$a_id;//id of the down vote button//
  $checkVote=mysql_query("select * from ans_status where ansId='$a_id' and userId='$uid'");
  $act=mysql_fetch_assoc($checkVote);
  $voteAction=$act['status'];
  $totalVoteCount = mysql_query('SELECT COUNT(*) FROM ans_status WHERE ansId = "'.$a_id.'"');
  $totalVoteCount = mysql_result($totalVoteCount, 0);
   $upVoteCount=mysql_num_rows(mysql_query("select * from ans_status where ansId='$a_id' and status='upVote'"));
  $upVotePercent =($upVoteCount/$totalVoteCount)*100;
  $downVoteCount = mysql_num_rows(mysql_query("select * from ans_status where ansId='$a_id' and status='downVote'"));
  $downVotePercent = ($downVoteCount/$totalVoteCount)*100;
  //php pf upvote section ends here//

  $idOfDeleteButton="deleteBut".$a_id;

if($auid==$uid || $upid==$uid)
{
?>
<div class="deleteButton" id="<?php echo $idOfDeleteButton; ?>" onclick="deleteAnswer(<?php echo $a_id; ?>,<?php echo $ques_id; ?>)" >Delete</div>
<?php
}
 ?>
<br/>
<!--like and unlike section-->
<div class="tab-tr" id="t1">
<div id="<?php echo $upVoteId; ?>"   class="but like-btn <?php if($voteAction=="upVote"){echo 'upvote_click'; } ?>"  onClick="vote(<?php echo $a_id; ?>,'upVote')">UpVote</div>
<div id="<?php echo $downVoteId; ?>"  class=" but dislike-btn <?php if($voteAction=="downVote"){echo 'downvote_click'; } ?>"   onClick="vote(<?php echo $a_id; ?>,'downVote')">DownVote</div>
<br/>
<br/>
<br/>

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
</div>
<!-- like section ends here -->
<hr/>
<?php
}
?>
</div>
</section>
<script src="js/askLikeSystem.js"></script>
<script src="js/delete.js"></script>
</body>
</html>
