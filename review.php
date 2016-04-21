<?php
include('connection.php');
extract($_POST);
$e=$_SESSION['user'];
$uid=$_SESSION['uid'];
$genRes=mysql_fetch_assoc(mysql_query("select gender from user where uname='$e'"));
$gender=$genRes['gender'];
if(!file_exists("users/$gender/$e/reviewFiles")){    //to create the directtory to contain all the files correspnding to review//
  mkdir("users/$gender/$e/reviewFiles");
}
if(isset($subReview)){
$textOfReview=$reviewText;
$nameOfUploadFile=$_FILES['uploadFile']['name'];
$tagText=$tagText;
$priority=$priority;
$timeOfPost=$time;
mysql_query("insert into review values('','$uid','$textOfReview','$nameOfUploadFile','$timeOfPost','$priority','$tagText')");
move_uploaded_file($_FILES['uploadFile']['tmp_name'],"users/$gender/$e/reviewFiles/".$_FILES['uploadFile']['name']);
}
?>
 <html>
 <head>
   <title>Get Reviews</title>
  <link rel="stylesheet" type="text/css" href="css/like&unlike.css">
     <style>
         body{
             font-family:arial;
             position: relative;
         }
         #reviewText{
             height:100px;
             width:400px;
             font-family:30px;
             font-family:arial;
          }
         #headSection{
             width:100%;
            position: absolute;

         }
         #reviewSec{
           position: absolute;
           width: 85%;
           text-align: center;
           top:290px;
           overflow: auto;
           left:85px;
          }
          .rateBut{
            cursor: pointer;
            float: left;
            margin:10px;
            padding:6px;
            height:20px;
            width:100px;
            border:2px solid black;
           }
         .suggestText{
           float: left;

         }
          .butSec{
          float: left;
            margin: 0px auto;
            width:85%;
           }
           .loveClick{
             background-color: green;
           }
           .unlikeClick{
             background-color: red;
           }

          .commentarea
          {

            width:500px;
            height:100px;
            position: relative;
           top:50px;
            margin: 0px;
            padding: 0px;
            overflow: auto;
          }

          .suggestBut
          {
            height:20px;
            width:60px;
            border:2px solid black;
            float: left;
            cursor: pointer;
          }

          .delSuggestBut
          {
            height: 20px;
            width:100px;
            border:1px solid black;
            text-align: center;
            cursor: pointer;
          }
          .delSuggestBut:hover
          {
            background-color: lightblue;
          }

     </style>
     <script src="js/jquery.js"></script>
     <script>
     function getDate()
     {

          d = new Date();
          mon = d.getMonth()+1;
          time = d.getDate()+"-"+mon+"-"+d.getFullYear()+" "+d.getHours()+":"+d.getMinutes();
          myform.time.value=time;

     }
     </script>
  </head>
  <body>
  <section id="headSection">
  <h1 align="center">Get review</h1>
  <form method="post" name="myform" enctype="multipart/form-data">
  <table align="center">
  <tr>
  <td>
  <textarea name="reviewText" id="reviewText" placeholder="I want review on...."></textarea>
  </td>
  </tr>
   <tr>
   <td>Upload file:&nbsp;&nbsp;&nbsp;<input type="file" name="uploadFile"/></td>
   </tr>
   <tr>
   <td>
   Add Tags:
   <input type="text" name="tagText" autocomplete="off" placeholder="Add your tags here"/>
   </td>
   <td><input type="hidden" name="time" /></td>
   </tr>
   <tr>
    <td><input type="submit" name="subReview" value="Get Review" onclick="getDate()"/></td>
    <td>Ask Anoyomously:<input type="checkbox" name="priority" value="anoymous" /></td>
    </tr>
    </table>
  </form>
    </section>
    <section id="reviewSec">
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
    </section>
    <script>
    function delReview(revId)
    {

      if(window.XMLHttpRequest){
          xmlhttp=new XMLHttpRequest();
        }
        else{
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange=function(){

                     if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                     document.getElementById("reviewSec").innerHTML=xmlhttp.responseText;

                     }
                   }
                   xmlhttp.open("REQUEST","deleteReview.php?revId="+revId,true);
            xmlhttp.send();
    }
    function delSuggestion(revId,sId)
    {
      //var id=document.getElementById("commentSec"+sId);//id for each comment section//

      if(window.XMLHttpRequest){
          xmlhttp=new XMLHttpRequest();
        }
        else{
          xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange=function(){

                     if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                     document.getElementById("commentSec"+revId).innerHTML=xmlhttp.responseText;

                     }
                   }
                   xmlhttp.open("REQUEST","delSuggestion.php?revId="+revId+"&suggId="+sId,true);
            xmlhttp.send();
    }

    function postSuggest(revId){
    var suggestTextId=document.getElementById("suggestText"+revId);       //id of each suggest text//
    var suggestText=suggestTextId.value;
    //var its=document.getElementById("commentSec"+revId);
    if(suggestText==""){
      alert("This field cannot be empty");
    }
    else{
    if(window.XMLHttpRequest){
        xmlhttp=new XMLHttpRequest();
      }
      else{
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
      suggestTextId.value="";
      xmlhttp.onreadystatechange=function(){

                   if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                     document.getElementById("commentSec"+revId).innerHTML=xmlhttp.responseText;

                   }
                 }
                 xmlhttp.open("REQUEST","postSuggestToReview.php?revId="+revId+"&suggestText="+suggestText,true);
          xmlhttp.send();
    }
  }

    function reviewStatus(revId,action)
    {
    var idOfLoveBut=document.getElementById("loveBut"+revId);
    var idOfUnlikeBut=document.getElementById("unlikeBut"+revId);
    var idOfStatSec=document.getElementById("reviewStatSec"+revId);
    if(action=="like"){
    idOfLoveBut.style.backgroundColor="green";
    idOfUnlikeBut.style.backgroundColor="white";
    }
    else if(action=="unlike"){
    idOfUnlikeBut.style.backgroundColor="red";
    idOfLoveBut.style.backgroundColor="white";
    }
    if(window.XMLHttpRequest){
        xmlhttp=new XMLHttpRequest();
      }
      else{
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp.onreadystatechange=function(){

                   if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                     document.getElementById("reviewStatSec"+revId).innerHTML=xmlhttp.responseText;

                   }
                 }
                 xmlhttp.open("REQUEST","postReviewStatus.php?revId="+revId+"&action="+action,true);
          xmlhttp.send();
    }
    </script>
 </body>
 </html>
