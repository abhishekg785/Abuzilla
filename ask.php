<?php
session_start();
error_reporting(0);
include('connection.php');
extract($_POST);
$e=$_SESSION['user'];
$uid=$_SESSION['uid'];
$res=mysql_fetch_assoc(mysql_query("select gender from user where uname='$e'"));
$gen=$res['gender'];
if(!file_exists("users/$gen/$e/askQuesFiles"))
{
  mkdir("users/$gen/$e/askQuesFiles");
}
if(isset($sub))
{
$user_id=$uid;
$ques_text=$ques_text;
$relation=$related;
$text=$ques_text;
$priority=$anom;
$ques_pic=$_FILES['img']['name'];
$time=$date;
$tagsText=$tagsText;
mysql_query("insert into ask_ques values('','$uid','$ques_text','$ques_pic','$time','$relation','$priority','$tagsText')");
move_uploaded_file($_FILES['img']['tmp_name'],"users/$gen/$e/askQuesFiles/".$_FILES['img']['name']);
}

//delete code//

if(isset($del))
{
$qid=$qid;
$rt=mysql_fetch_assoc(mysql_query("select ques_pic from ask_ques where ques_id='$qid'"));
$pic=$rt['ques_pic'];
unlink("users/$gen/$e/post/$pic");
$qy=mysql_query("select * from ans_ques where ques_id='$qid'");
if(mysql_num_rows($qy))
{
  while($res=mysql_fetch_assoc($qy))
  {
    $aId=$res['ans_id'];
    mysql_query("delete from ans_status where ansId='$aId'");
  }
}
mysql_query("delete from ask_ques where ques_id='$qid'");
mysql_query("delete from ans_ques where ques_id='$qid'");
mysql_query("delete from quesStatus where quesId='$qid'");
}

//answer section ends here/
if(isset($del_ans))
{
  $aId=$ansId;
  mysql_query("delete from ans_ques where ans_id='$aId'");
  mysql_query("delete from ans_status where ansId='$aId'");
}
?>
<html>
<head>
<title>Ask</title>
<link rel="stylesheet" type="text/css" href="css/like&unlike.css">
<style>
  body,html
  {
    margin: 0px;
    padding: 0px;
  }

  table
  {
  padding:30px;
  }

  #askarea
  {
    font-size: 30px;
    height:150px;
    width:500px;
  }

  #sub
  {
    height:30px;
    width:100px;
    border:2px solid black;
  }

  #cont
  {
    width:75%;
    height:300px;
    background-color: lightblue;
    background-color: rgba(5,5,5,0.3);
    left:12%;
    padding: 0px;
    position: absolute;
  }

  #cont2
  {
    width:75%;
    height:100%;
    background-color:blue;
    background-color: rgba(50,58,50,0.2);
    left:12%;
    top:350px;
    padding: 0px;
    overflow: auto;
    position: absolute;
  }

  .box
  {
    border:2px solid black;
    padding:10px;
    margin:10px;
  }

  a
  {
  text-decoration: none;
  }

  .like_click
  {
    color: green;
  }

  .unlike_click
  {
    color: red;
  }

  .upvote_click
  {
    color:green;
  }

  .downvote_click
  {
    color:red;
  }

  .ansSec
  {
    border:1px solid black;
  }

  .deleteButton
  {
    height:18px;
    width: 50px;
    border: 1px solid black;
    text-align: center;
    padding: 1px;
    cursor: pointer;
  }

  .deleteButton:hover
  {
    background-color: lightgreen;
  }
  </style>
  <script>
  function getDate()
  {
    d = new Date();
       mon = d.getMonth()+1;
       time = d.getDate()+"-"+mon+"-"+d.getFullYear()+" "+d.getHours()+":"+d.getMinutes();
       myform.date.value=time;
  }
</script>
</head>
<body>
  <section id="cont">
    <form method="post"  enctype="multipart/form-data"  name="myform">
    <table align="center">
     <tr>
      <td colspan="2"><textarea id="askarea" name="ques_text" placeholder="ask here"></textarea></td>
     </tr>
     <tr>
      <td>Upload image:</td>
      <td><input type="file" name="img" /></td>
     </tr>
     <tr>
      <td>Question is related :</td>
      <td>
      <select name="related" required>
        <option value="">Choose Category</option>
        <option value="Food places">Food</option>
        <option value="Places nearby">Places nearby</option>
        <option value="Hostel">Hostel</option>
        <option value="College">College</option>
        <option value="Teachers">Teachers</option>
        <option value="Student">Student</option>
        <option value="Programming">Programming</option>
        <option value="Devlopment">Devlopment</option>
        <option value="College Groups">College Groups</option>
        <option value="Companies">Companies</option>
        <option value="Socities and clubs">Socities and clubs</option>
        <option value="College Magazines">College Magazines</option>
        <option value="College Fest">College Fest</option>
      </select>
      </td>
        <input type="hidden" name="date" />
     </tr>
     <tr>
      <td>Add Tags:(For more specific search)</td>
      <td><input type="text" name="tagsText" placeholder="eg:songs,music,tone" autocomplete="off"/></td>
     </tr>
     <tr>
      <td colspan="2"><input type="submit" id="sub" name="sub" value="Ask" onClick="getDate()"/></td>
      <td>Ask Anoyoumously<input type="checkbox" name="anom" value="anoyomous" /></td>
     </tr>
    </table>
    </form>
  </section>
  <section id="cont2">
  <h1 align="center">Questions and Answers</h1>
<?php

//all questions//
$qm=mysql_query("select * from ask_ques order by ques_id desc");
while($res=mysql_fetch_assoc($qm))
{
  $ques_id=$res['ques_id'];
  $user_id=$res['user_id'];//user of particular question//
  $time=$res['q_time'];
  $pri=$res['priority'];
  $img=$res['ques_pic'];
  $rel=$res['relation'];
  $part=mysql_query("select * from user where userid='$user_id'");
  $rest=mysql_fetch_assoc($part);
  $pfname=$rest['fname'];
  $plname=$rest['lname'];
  $puname=$rest['uname'];
  $pgen=$rest['gender'];
  if($pri==""){
  echo "<h1>";
  echo $pfname." ".$plname."<br/>";  //user is not anoyomous//
  echo "</h1>";
}
  echo $rel;
  echo "<br/>";
  echo $time."<br/>";
  echo "<a href='view_ques.php?ques=$ques_id'>";
  echo $res['ques_text']."</br>";
  echo "</a>";
  if($img==""){}
  else
  {
  echo "<img height='400px' width='700px' src='users/$pgen/$puname/askQuesFiles/$img' />";
  }
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

 //like and unlike section ends here//
 ?>

<!--the like and unlike  section STARTS here -->

 <div class="tab-tr">
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
 </div>
 </div><!-- /stat-cnt -->
 </div>
<!--the like and unlike  section ENDS here -->


 <?php
//delete question//
 if($puname==$e)
 {
 ?>
 <form method='post'>
 <input type='submit' name='del' value='delete question'/>
 <input type='hidden' name='qid' value="<?php echo $ques_id; ?>"   />
 </form>
 <?php
 }
//ends here//
 $ansTextId="ansText".$ques_id;
 $idOfAnsSec="ansSec".$ques_id;//id of each answer section//
?>

<!--give answer section starts here -->
<input type="text" id="<?php echo $ansTextId; ?>" placeholder="write your answer here...." />
<input type="submit"  value="Answer"  onclick="postAnswer(<?php echo $ques_id; ?>)"/>
<br/>
<!-- answer section ends here -->
<?php
//displays answer here//
$query=mysql_query("select * from ans_ques where ques_id='$ques_id'");
$ans_count=mysql_num_rows($query);
?>
<div class="ansSec" id="<?php echo $idOfAnsSec; ?>" >
<h1>Answers:<?php echo $ans_count; ?></h1>
<?php
while($rts=mysql_fetch_assoc($query)){
  //user of particular ans//
echo "<br/>";
$ansId=$rts['ans_id'];//id of particular ans//
$puid=$rts['user_id'];//user who gave the answer//
$pname=mysql_fetch_assoc(mysql_query("select fname,lname from user where userid='$puid'"));
echo $pname['fname']." ".$pname['lname'].":";
  //ends here//
echo $rts['ans_text'];
echo "<br/>";
$idOfDeleteButton="deleteBut".$ansId;
//delete particular answer sction here//
if($puid==$uid || $user_id==$uid){
  //echo $idOfDeleteButton;
?>
<div class="deleteButton" id="<?php echo $idOfDeleteButton; ?>" onclick="deleteAnswer(<?php echo $ansId; ?>,<?php echo $ques_id; ?>)" >Delete</div>
<?php
}
$upVoteId="upVote".$ansId;//id of the upvote button//
$downVoteId="downVote".$ansId;//id of the down vote button//

$upDownVoteStatId="voteStatPor".$ansId;//id of the upvote and downvote stat portion id//


$checkVote=mysql_query("select * from ans_status where ansId='$ansId' and userId='$uid'");
$act=mysql_fetch_assoc($checkVote);
$voteAction=$act['status'];
$totalVoteCount = mysql_query('SELECT COUNT(*) FROM ans_status WHERE ansId = "'.$ansId.'"');
$totalVoteCount = mysql_result($totalVoteCount, 0);
$upVoteCount=mysql_num_rows(mysql_query("select * from ans_status where ansId='$ansId' and status='upVote'"));
$upVotePercent =($upVoteCount/$totalVoteCount)*100;
$downVoteCount = mysql_num_rows(mysql_query("select * from ans_status where ansId='$ansId' and status='downVote'"));
$downVotePercent = ($downVoteCount/$totalVoteCount)*100;

?>

<div class="tab-tr">
<div id="<?php echo $upVoteId; ?>"   class="but like-btn <?php if($voteAction=="upVote"){echo 'upvote_click'; } ?>"  onClick="vote(<?php echo $ansId; ?>,'upVote')">UpVote</div>
<div id="<?php echo $downVoteId; ?>"  class=" but dislike-btn <?php if($voteAction=="downVote"){echo 'downvote_click'; } ?>"   onClick="vote(<?php echo $ansId; ?>,'downVote')">DownVote</div>
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
<?php
}
?>
</div>
<br/>
<br/>
<br/>
<?php
}
?>
</section>
<script src="js/askLikeSystem.js"></script>
<script src="js/delete.js"></script>
<script>

function likeQues(qid,action){
var likeButtonId=document.getElementById("like"+qid);
var unlikeButtonId=document.getElementById("unlike"+qid);
if(action=="likeQues"){
	likeButtonId.style.color="green";
	unlikeButtonId.style.color="black";
}
else if(action=="unlikeQues"){
	likeButtonId.style.color="black";
	unlikeButtonId.style.color="red";
}
if(window.XMLHttpRequest){
    xmlhttp=new XMLHttpRequest();
  }
  else{
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function(){

               if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                 document.getElementById("likestatPor"+qid).innerHTML=xmlhttp.responseText;

               }
             }
             xmlhttp.open("REQUEST",action+".php?val="+qid,true);
      xmlhttp.send();
}
//answer section STARTS here//
//in this section we will be dealing with the rating system of the answers given by the user//
function vote(ansId,action){
var upvoteButtonId=document.getElementById("upVote"+ansId);
var downVoteButtonId=document.getElementById("downVote"+ansId);
if(action=="upVote"){
	upvoteButtonId.style.color="green";
	downVoteButtonId.style.color="black";
}
else if(action=="downVote"){
	upvoteButtonId.style.color="black";
	downVoteButtonId.style.color="red";
}
if(window.XMLHttpRequest){
    xmlhttp=new XMLHttpRequest();
  }
  else{
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function(){

               if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                 document.getElementById("voteStatPor"+ansId).innerHTML=xmlhttp.responseText;

               }
             }
             xmlhttp.open("REQUEST",action+".php?val="+ansId,true);
      xmlhttp.send();
}

function postAnswer(qid)
{
  //var ansSecId=document.getElementById("ansSec"+qid);
  var ansTextId=document.getElementById("ansText"+qid);
  var ansText=ansTextId.value;
  if(ansText==""){
    alert("this section is required..");
  }
  else{
  if(window.XMLHttpRequest){
      xmlhttp=new XMLHttpRequest();
    }
    else{
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
  ansTextId.value="";
    xmlhttp.onreadystatechange=function()
    {

                 if(xmlhttp.readyState == 4 && xmlhttp.status == 200)
                 {
                  document.getElementById("ansSec"+qid).innerHTML=xmlhttp.responseText;
                 }
    }
               xmlhttp.open("REQUEST","postAnswer.php?quesId="+qid+"&ansText="+ansText,true);
        xmlhttp.send();

}
}
</script>
</body>
</html>
