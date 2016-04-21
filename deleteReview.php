<?php
session_start();
include('connection.php');
extract($_REQUEST);
$e=$_SESSION['user'];
$UID=$_SESSION['uid'];
$reviewId=$revId;
mysql_query("delete from review where rId='$reviewId'");
mysql_query("delete from reviewStatus where reviewId='$reviewId'");
mysql_query("delete from suggestToReview where revId='$reviewId'");
?>
<h1 align="center">Peoples got reviewed on!!!!</h1>
<hr/>
<?php
$allRevText=mysql_query("select * from review order by rId desc");
while($res=mysql_fetch_assoc($allRevText))
{
  $reviewId=$res['rId'];//id of each review//
  $userIdOfReview=$res['userId'];//userid of the user who asked for the review
  $reviewText=$res['revText'];
  $revFile=$res['revPic'];//in this case it is pic//
  $reviewTime=$res['rTime'];
  $rPriority=$res['priority'];
  if($rPriority=="")
  {  //not anoyomous
  $userDet=mysql_fetch_assoc(mysql_query("select * from user where userid='$userIdOfReview'"));
  //details of the user//
  $unameOfReview=$userDet['uname'];
  $fname=$userDet['fname'];
  $lname=$userDet['lname'];
  $genderOfRevUser=$userDet['gender'];//gender of the user who sked for review//
  echo "<h1>";
  echo $fname." ".$lname." ";
  echo "</h1>";
  echo $reviewTime;
  }
  echo "<br/>";
  echo $reviewText;
  echo "<br/>";
 //details of the user ends here//
 if($revFile==""){
 }
 else{
   echo "<img height='250px' width='500px' src='users/$genderOfRevUser/$unameOfReview/reviewFiles/$revFile' />";
 }

 //rate button parameters//
 $loveButId="loveBut".$reviewId;
 $unlikeButId="unlikeBut".$reviewId;
 $suggestButId="suggestBut".$reviewId;
 $discardButId="discardBut".$reviewId;
 //parameters ends here//

 //review stat section//
 $idForRevStatSec="reviewStatSec".$reviewId;//id of each  review section
 //review stat  section ends here//

//to check whetehet a  button is clicked or not it checks wheteher the current user has liked a particular post//
 $resForStatus=mysql_fetch_assoc(mysql_query("select * from reviewStatus where reviewId='$reviewId' and userId='$uid'"));
 $act=$resForStatus['status'];
//check ends here//


//now here starts the section for the stat of the likeor unlike etc//
 $totalVoteCount = mysql_query('SELECT COUNT(*) FROM reviewStatus WHERE reviewId = "'.$reviewId.'"');
 $totalVoteCount = mysql_result($totalVoteCount, 0);
 $upVoteCount=mysql_num_rows(mysql_query("select * from reviewStatus where reviewId='$reviewId' and status='like'"));
 $upVotePercent =($upVoteCount/$totalVoteCount)*100;
 $downVoteCount = mysql_num_rows(mysql_query("select * from reviewStatus where reviewId='$reviewId' and status='unlike'"));
 $downVotePercent = ($downVoteCount/$totalVoteCount)*100;
 //stat section ends here//

 //elements for the comment portion//
 $suggestTextId="suggestText".$reviewId;//id of the suggest text box//
 $suggestButId="suggestBut".$reviewId;// id of the review buttton//
 $suggestToggleId="suggToggle".$reviewId;//id of button uhsed to toggle the suggestion text field//
 $idForCommentSec="commentSec".$reviewId;//id for the comment section

 $toggleSecForSuggest="toggleSec".$reviewId;
 //commnet portion ends here//

 ?>
 <!--like unlike or suggest section starts here -->
<div class="butSec">
<div class="rateBut <?php if($act=="like"){echo 'loveClick' ;}   ?>" id="<?php echo $loveButId ; ?>" onclick="reviewStatus(<?php echo $reviewId; ?>,'like')">Love It</div>
<div class="rateBut <?php if($act=="unlike"){echo 'unlikeClick' ;}   ?>" id="<?php echo  $unlikeButId ; ?>" onclick="reviewStatus(<?php echo $reviewId; ?>,'unlike')">Unlike It</div>
<div class="rateBut" id="<?php echo $suggestToggleId; ?>" onclick="showSuggest(<?php echo $reviewId; ?>)">Suggest Something</div>
<div id="<?php echo  $toggleSecForSuggest; ?>" >
<input type="text" class="suggestText"  id="<?php echo $suggestTextId; ?>" placeholder="Suggest Something..."/>
<!--button for suggesting having div otherwise the button will refresh the page again and again-->
<div  class="suggestBut"  id="<?php echo  $suggestButId; ?>" onclick="postSuggest(<?php echo $reviewId; ?>)">Suggest</div>
</div>
<div id="<?php echo $discardButId; ?>" class="rateBut" onclick="delReview(<?php echo $reviewId; ?>)">Discard</div>
</div>

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

<div id="<?php echo $idForCommentSec; ?>" class="commentarea">
<?php
$revQ=mysql_query("select * from suggestToReview where revId='$reviewId'");
$noOfReviews=mysql_num_rows($revQ);
?>
Suggestion:<span>(</span><?php echo $noOfReviews; ?> <span>)</span>
<br/>
<?php
while($listSugg=mysql_fetch_assoc($revQ)){
//user details of who gave the suggestion//
$suggId=$listSugg['suggId'];
$userIdOfSugg=$listSugg['userId'];//userid of the user who gave the suggestion//
//user details ends here//
$userDetOfSugg=mysql_fetch_assoc(mysql_query("select fname,lname from user where userid='$userIdOfSugg'"));
echo $userDetOfSugg['fname']." ".$userDetOfSugg['lname']." :";
echo $listSugg['suggText'];  ///text corresponding to suggestion

//id for the each suggestin delete button//
$delSuggestButId="delSuggestBut".$suggId;
echo "<br/>";
if($userIdOfSugg==$uid||$userIdOfReview==$uid)
{
?>
<div id="<?php echo $delSuggestButId; ?>" class="delSuggestBut" onclick="delSuggestion(<?php echo $reviewId; ?>,<?php echo $suggId; ?>)">Delete</div>
<?php
}
}
?>
</div>
<!-- like or unlike or suggest section ends here -->
<?php
echo "<br/>";
echo "<br/>";
echo "<br/>";
echo "<br/>";
echo "<br/>";
echo "<br/>";
echo "<br/>";echo "<br/>";
 }
?>
