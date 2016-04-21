<?php
session_start();
include('connection.php');
$uid=$_SESSION['uid'];
$e=$_SESSION['user'];
extract($_REQUEST);
$reviewId=$revId;
$suggId=$suggId;
mysql_query("delete from suggestToReview where suggId='$suggId'");
$idForCommentSec="commentSec".$reviewId;//id for the comment section
//details of the user  corresponding to the reviw having reviewId//
$detOfUser=mysql_fetch_assoc(mysql_query("select userId from review where rId='$reviewId'"));
$userIdOfReview=$detOfUser['userId'];
//details of the user ends here//
?>

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
