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
 #complainSec
 {

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
<td>Upload pic:<input type="file"  id="file"/></td>
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
echo $nameOfComplainUser."       ".$cTime;
echo "<br/>";
echo $cText;
if($userIdOfComplain==$uid)
{
?>
<!--delete section starts here -->
<div class="buttons" onclick="deleteComplain(<?php echo $cId; ?>)">Delete</div>
<!--delete complain section ends here -->
<?php
}
}
?>
</div>
</section>
<script>
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
