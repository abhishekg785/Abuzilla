<?php
session_start();
include('connection.php');
extract($_POST);
$e=$_SESSION['user'];//uname of the user logged in//
$uid=$_SESSION['uid'];//id of the user currently logged in//
$res=mysql_fetch_assoc(mysql_query("select gender from user where uname='$e'"));
$gen=$res['gender'];
if(!file_exists("users/$gen/$e/voteFiles"))
{
  mkdir("users/$gen/$e/voteFiles");
}
if(isset($subVote))
{
$voteText=$voteIssueText;//text of the vote issue
$upFileName=$_FILES['file']['name'];//name of the file uploaded//
$tagText=$tagText;
$voteTime=$date;
mysql_query("insert into userVote values('','$uid','$voteText','$upFileName','$tagText','$voteTime')");
move_uploaded_file($_FILES['file']['tmp_name'],"users/$gen/$e/voteFiles/".$_FILES['file']['name']);
}
?>
<html>
  <head>
	<title>Vote</title>
  <link rel="stylesheet" type="text/css" href="css/like&unlike.css">
  <style>

  #outer
  {
    height: 100%;
    width:100%;
    overflow: auto;
  }

  #voteSec
  {
    width: 100%;
    height: 30%;
    position: absolute;
    top:30px;
    text-align: center;
    font-family: arial;
  }

  #voteIssueText
  {
    height: 100px;
    width: 400px;
    font-size: 15px;
  }

  #viewAllVotes
  {
    height: 50px;
    width: 100%;
    top:40px;
    position: absolute;
    top:250px;
  }

 .voteSec
 {
   height:300px;
   width:500px;
   border:2px solid black;
   margin: 5px auto;
   overflow: auto;
 }

 .butSec
  {
  height:30px;
  width:100px;
  border:1px solid black;
  cursor: pointer;
  float: left;
  text-align: center;
  }

 .voteStatSec
 {
  position:relative;
  top:100px;
 }

.voteStatSec p
{
  position: absolute;
  left:40px;
  padding: 1px;
  margin:1px;
}

.butDiv
{
  margin:0px;
  padding: 0px;
}
.upvoteClick
{
  background-color: green;
}
.downVoteClick
{
  background-color: red;
}
.cantSayClick
{
  background-color: orange;
}
.delVoteButClass
{
  height: 20px;
  width:100px;
  border:1px solid black;
  text-align: center;
  padding: 1px;
  margin: 4px;
  cursor: pointer;
}
.delVoteButClass:hover
{
  background-color: lightblue;
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
  </script>
	</head>
	<body>
  <section id="outer">
  <section id="voteSec">
  <form method="post" enctype="multipart/form-data" name="myform">
  <table align="center">
  <tr>
	 <td><textarea placeholder="Enter the issue you want to vote on....." id="voteIssueText" name="voteIssueText"></textarea></td>
 </tr>
 <tr>
 <td>Add:&nbsp;&nbsp;&nbsp;<input type="file" name="file"  /></td>
</tr>
 <tr>
   <td>Eneter Tags:<input type="text" name="tagText" placeholder="Eneter related tags here"/></td>
   <td><input type="hidden" name="date"/></td>
 </tr>
 <tr>
   <td><input type="submit" name="subVote" value="Let's vote" onclick="getDate()"/></td>
 </tr>
</table>
</form>
<hr/>
 </section>
 <section id="viewAllVotes">
<?php
$query=mysql_query("select * from userVote order by voteId desc");
while($res=mysql_fetch_assoc($query))
{
$voteId=$res['voteId'];
$userOfVoteId=$res['userId'];//user who gave the issue of the vote//
$resOfUser=mysql_fetch_assoc(mysql_query("select gender,uname from user where userid='$userOfVoteId'"));
$genderOfVoter=$resOfUser['gender'];//gender of the user corresponding to a prticular vote//
$unameOfVoter=$resOfUser['uname'];//username of the user corresponing to a particular vote
$voteText=$res['voteText'];
$fileName=$res['fileName'];
$voteTime=$res['voteTime'];
//details of the user who gave the issue of the vote//
$res=mysql_fetch_assoc(mysql_query("select * from user where userid='$userOfVoteId'"));

//detils of the user ends here//
//section in which all the vote details will be displayed//
$idOfVoteSec="voteSec".$voteId;//id of ech section showing the vote details//
$userOfVote=$res['fname']." ".$res['lname'];

//ids of vote buttons//
$idOfUpvoteBut="upvoteBut".$voteId;
$idOfDownVoteBut="downVoteBut".$voteId;
$idOfCantSayBut="cantSayBut".$voteId;

//vote buttons id section ends here//

$idOfDelVoteBut="delVoteBut".$voteId;//id for the delete button//

//id for the section div conatining the stat portion//
$idOfvoteStatDiv="voteStatDiv".$voteId;
//ends here//
//calculation of the votesecId//
$totalVoteCount=mysql_query('SELECT COUNT(*) FROM voteStatus WHERE voteId = "'.$voteId.'"');
$totalVoteCount=mysql_result($totalVoteCount, 0);
$upVoteCount=mysql_num_rows(mysql_query("select * from voteStatus where voteId='$voteId' and status='upVote'"));
$downVoteCount=mysql_num_rows(mysql_query("select * from voteStatus where voteId='$voteId' and status='downVote'"));
$cantSayCount=mysql_num_rows(mysql_query("select * from voteStatus where voteId='$voteId' and status='cantSay'"));

$upVotePercent =($upVoteCount/$totalVoteCount)*100;
$downVotePercent = ($downVoteCount/$totalVoteCount)*100;
$cantSayPercent=($cantSayCount/$totalVoteCount)*100;
//calculation ends here//

//to check whether the button has been pressed//
$chkStatus=mysql_fetch_assoc(mysql_query("select * from voteStatus where voteId='$voteId' and userId='$uid'"));
$status=$chkStatus['status'];
//button check ends here//

?>
<div class="voteSec" id="<?php echo $idOfVoteSec; ?>" >
<?php
echo $userOfVote;
echo "</br>";
echo $voteTime;
?>
<h1><?php echo $voteText; ?></h1>
<br/>
<?php
if($fileName==""){
}
else
{
echo "<img height='200px' width='400px' src='users/$genderOfVoter/$unameOfVoter/voteFiles/$fileName' />";
}
if($userOfVoteId==$uid)
{
 ?>
<div class="delVoteButClass" id="<?php echo $idOfDelVoteBut; ?>" onclick="deleteVote(<?php echo $voteId; ?>)">Delete Vote</div>
<?php
}
 ?>
<div class="butDiv">
<!-- section of the upvote,downvote and nothing buttons-->
<div class="butSec  <?php if($status=="upVote"){echo 'upvoteClick' ;}  ?>" id="<?php echo $idOfUpvoteBut; ?>" onclick="postVoteStatus(<?php echo $voteId; ?>,'upVote')">UpVote</div>
<div class="butSec  <?php if($status=="downVote"){echo 'downVoteClick' ;} ?>" id="<?php echo $idOfDownVoteBut; ?>" onclick="postVoteStatus(<?php echo $voteId; ?>,'downVote')">DownVote</div>
<div class="butSec  <?php if($status=="cantSay"){echo 'cantSayClick' ;} ?>" id="<?php echo $idOfCantSayBut; ?>" onclick="postVoteStatus(<?php echo $voteId; ?>,'cantSay')">Can'T Say</div>
<!-- button section ends here -->
</div>
<div class="voteStatSec" id="<?php echo $idOfvoteStatDiv; ?>" >
<p>Total no of votes:<?php echo $totalVoteCount; ?></p><br/>
<p>No of upvotes:<?php echo $upVoteCount; ?></p><br/>
<p>No of Downvotes:<?php echo $downVoteCount; ?></p><br/>
<p>No of peoples who say nothing:<?php echo $cantSayCount; ?></p><br/>
<p>%of upvotes:<?php echo $upVotePercent; ?></p><br/>
<p>%of downvotes:<?php echo $downVotePercent; ?></p><br/>
<p>%of can't say:<?php echo $cantSayPercent; ?> </p>
</div>
</div>
<?php
}
?>
 </section>
 </section>
 <script>
 function postVoteStatus(vId,action)
 {
   var upvoteId=document.getElementById("upvoteBut"+vId);
   var downVoteId=document.getElementById("downVoteBut"+vId);
   var cantSayId=document.getElementById("cantSayBut"+vId);
  if(action=="upVote")
  {
   upvoteId.style.backgroundColor="green";
   downVoteId.style.backgroundColor="white";
   cantSayId.style.backgroundColor="white";
  }
  else if(action=="downVote")
  {
   upvoteId.style.backgroundColor="white";
   downVoteId.style.backgroundColor="red";
   cantSayId.style.backgroundColor="white";
  }
  else if(action=="cantSay")
  {
   upvoteId.style.backgroundColor="white";
   downVoteId.style.backgroundColor="white";
   cantSayId.style.backgroundColor="orange";
  }
  if(window.XMLHttpRequest)
    {
      xmlhttp=new XMLHttpRequest();
    }
    else
    {
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
               {

                 if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                  document.getElementById("voteStatDiv"+vId).innerHTML=xmlhttp.responseText;
                   }
               }
               xmlhttp.open("REQUEST","postVoteStatus.php?vId="+vId+"&action="+action,true);
        xmlhttp.send();
  }
function deleteVote(vId)
{
  //var id=document.getElementById("viewAllVotes");
  //alert(id);
  if(window.XMLHttpRequest)
    {
      xmlhttp=new XMLHttpRequest();
    }
    else
    {
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function()
               {

                 if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                  document.getElementById("viewAllVotes").innerHTML=xmlhttp.responseText;
                   }
               }
               xmlhttp.open("REQUEST","deleteVote.php?vId="+vId,true);
        xmlhttp.send();
}
 </script>
	</body>
</html>
