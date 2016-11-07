<?php
session_start();
$e=$_SESSION['user'];//name of the user//
$uid=$_SESSION['uid'];//userId of the user logged in//
?>
<html>
<head>
<title>Complain</title>
<style>
 #registerComplain
 {
   height:300px;
   width:100%;
   border:2px solid black;
   position: relative;
   top:30px;
   text-align: center;
  }

 .buttons
 {
   height:20px;
   width:80px;
   border: 2px solid black;
   text-align: center;
   cursor: pointer;
 }

 .buttons:hover
 {
  background-color: lightblue;
 }

 #complainText
 {
   height:100px;
   width:350px;
 }

 #showComplainsSec
 {
   margin:1px;
   height:100%;
   width:100%;
   padding: 8px;
 }

 #allComplains
 {
   width:100%:
   height:100%;
 }

.replySec
{
  float:left;
}

.deleteComment
{
  height: 20px;
  width:150px;
  border:1px solid black;
  text-align: center;
  cursor: pointer;
}
</style>
</head>
<body>
<section id="registerComplain">
<div id="complainSec">
<table align="center">
<tr>
  <td>Title:<input type="text" id="complainTitle" placeholder="Your Complain on..." name="complainTitle"/></td>
</tr>
<tr>
<td><textarea id="complainText" height="200px" width="500px" placeholder="Write your complain here..."></textarea></td>
</tr>
<tr>
<td>Upload pic:<input type="file" id="file"/></td>
</tr>
<tr>
<td><div id="subButton" class="buttons" onclick="postComplain()">Submit</div></td>
</tr>
</table>
</div>
</section>
<section id="showComplainsSec">
<h1 align="center">Previous Complains</h1>
<div id="allComplains">
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
  $countOfComments=mysql_num_rows($commentQuery);
  ?>
  <input type="text" id="<?php echo $commentTextId; ?>" class="replySec" name="" placeholder="Enter your reply here.." /><div id="<?php echo $commentButtonId; ?>" class="replySec buttons"  name="" onclick="postCommentToComplain(<?php echo $cId; ?>)">Comment</div>
  <br/>
  <br/>
  <div id="<?php echo $showAllCommentDivId; ?>">
  Comments:<?php echo $countOfComments; ?>
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
 </div>
 </section>
 <script>
function deleteCommentOfComplain(complainId,commentId)
{
  if(window.XMLHttpRequest){
      xmlhttp=new XMLHttpRequest();
    }
    else{
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
   xmlhttp.onreadystatechange=function(){

                 if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                   document.getElementById("showAllCommentDiv"+complainId).innerHTML=xmlhttp.responseText;

                 }
               }
              xmlhttp.open("REQUEST","deleteCommentOfComplain.php?complainId="+complainId+"&commentId="+commentId,true);
        xmlhttp.send();
}
function postCommentToComplain(cId)
{
var commentTextId=document.getElementById("commentText"+cId);
//var id=document.getElementById("showAllCommentDiv"+cId);
var cText=commentTextId.value;
if(cText=="")
{
  alert("All fields are required");
}
else
{
if(window.XMLHttpRequest){
    xmlhttp=new XMLHttpRequest();
  }
  else{
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
commentTextId.value="";
 xmlhttp.onreadystatechange=function(){

               if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                 document.getElementById("showAllCommentDiv"+cId).innerHTML=xmlhttp.responseText;

               }
             }
            xmlhttp.open("REQUEST","postCommentToComplain.php?cId="+cId+"&commentText="+cText,true);
      xmlhttp.send();
}
}
function postComplain()
{
//var id=document.getElementById("showComplains");
var cTitle=document.getElementById("complainTitle").value;
var cText=document.getElementById("complainText").value;
var file=document.getElementById("file").value;
d = new Date();
mon = d.getMonth()+1;
time = d.getDate()+"-"+mon+"-"+d.getFullYear()+" "+d.getHours()+":"+d.getMinutes();
if(cTitle==""||cText=="")
{
  alert("fields are required");
}
else
{
if(window.XMLHttpRequest){
    xmlhttp=new XMLHttpRequest();
  }
  else{
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
document.getElementById("complainTitle").value="";
document.getElementById("complainText").value="";
 xmlhttp.onreadystatechange=function(){

               if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                 document.getElementById("allComplains").innerHTML=xmlhttp.responseText;

               }
             }
             xmlhttp.open("REQUEST","postComplain.php?cTitle="+cTitle+"&cText="+cText+"&fileName="+file+"&cTime="+time,true);
      xmlhttp.send();
}
}
function deleteComplain(cId)
{
  if(window.XMLHttpRequest){
      xmlhttp=new XMLHttpRequest();
    }
    else{
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
   xmlhttp.onreadystatechange=function(){

                 if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                   document.getElementById("allComplains").innerHTML=xmlhttp.responseText;

                 }
               }
               xmlhttp.open("REQUEST","deleteComplain.php?cId="+cId,true);
        xmlhttp.send();
}
</script>
</body>
</html>
