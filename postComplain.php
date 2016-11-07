<?php
session_start();
include('connection.php');
extract($_REQUEST);
$uid=$_SESSION['uid'];
$e=$_SESSION['user'];
$gender=$_SESSION['gender'];
$uid=$_SESSION['uid'];
$cTitle=$cTitle;
$cText=$cText;
$file=$fileName;
$cTime=$cTime;
//echo $cTitle;
//echo $cText;
//echo $file;
//upload the file in the complains folder//
if(!file_exists("users/$gen/$e/complainFiles"))
{
 mkdir("users/$gender/$e/complainFiles");
}
mysql_query("insert into userComplain values('','$uid','$cTitle','$cText','$file','$cTime')");
//move_uploaded_file($_FILES['file']['tmp_name'],"users/$gender/$e/complainFiles/".$file);
?>
<?php
$query=mysql_query("select * from userComplain order by cId desc");
while($res=mysql_fetch_assoc($query))
{
  $cId=$res['cId'];
  $userIdOfComplain=$res['userId'];//id of the user who registered the complain
  $cTitle=$res['cTitle'];//title of the complain
  $cText=$res['cText'];//text of the complain
  $fileName=$res['fileName'];//name of the file uploaded
  $cTime=$res['cTime'];//time of the complain
  //details of the user who complained//
  $usDet=mysql_fetch_assoc(mysql_query("select fname,lname from user where userid='$userIdOfComplain'"));
  $nameOfComplainUser=$usDet['fname']." ".$usDet['lname'];
  //detils of the user ends here//
  //displaying details//
  echo "<h1>";
  echo $cTitle;
  echo "</h1>";
  echo $cText;
  echo "<br/>";
  echo $nameOfComplainUser." ".$cTime;
  if($userIdOfComplain==$uid)
  {
  ?>
  <!--delete section starts here -->
  <div class="buttons" onclick="deleteComplain(<?php echo $cId; ?>)">Delete</div>
  <!--delete complain section ends here -->
  <?php
  }
  //id for the reply section attributes//
  $commentTextId="commentText".$cId;
  $commentButtonId="commentButton".$cId;
  $showAllCommentDivId="showAllCommentDiv".$cId;

  $commentQuery=mysql_query("select * from commentToComplain where complainId='$cId'");
  $countOfComment=mysql_num_rows($commentQuery);
  ?>
  <input type="text" id="<?php echo $commentTextId; ?>" class="replySec" name="" placeholder="Enter your reply here.." /><div id="<?php echo $commentButtonId; ?>" class="replySec buttons"  name="" onclick="postCommentToComplain(<?php echo $cId; ?>)">Comment</div>
  <br/>
  <br/>
  <div id="<?php echo $showAllCommentDivId; ?>">
  Comments:<?php echo $countOfComment; ?>
  <br/>
  <?php
  while($res=mysql_fetch_assoc($commentQuery))
  {
    $commentId=$res['commentId'];
    $userOfCommentId=$res['userId'];
    $commentText=$res['commentText'];
    $res=mysql_fetch_assoc(mysql_query("select fname,lname from user where userid='$userOfCommentId'"));
    $userOfCommentName=$res['fname']." ".$res['lname'];
    echo $userOfCommentName.":";
    echo $commentText;
    //id for the button for deleting the comments//
    $deleteCommentButId="deleteCommentBut".$commentId;
    if($userOfCommentId==$uid||$userIdOfComplain==$uid)
    {
    ?>
   <div class="deleteComment" id="<?php echo $deleteCommentButId; ?>" onclick="deleteCommentOfComplain(<?php echo $cId; ?>,<?php echo $commentId; ?>)">Delete Comment</div>
    <?php
    }
    echo "<br/>";
   }
   ?>
  </div>
  <hr/>
  <?php
   }
  ?>
