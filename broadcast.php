<?php
//this id to feature the important informations regarding fests,workshops etc...//

?>
<html>
<head>
  <title>BroadCast here</title>
  <style>

  #broadcastForm
  {
    width:100%;
    height:200px;
    border:1px solid black;
    position: absolute;
    top:30px;
  }

  #broadcastTextId
  {
    height:100px;
    width:400px;
  }

  #viewAllBroadcast
  {
    width:100%;
    border:1px solid black;
    height:100%;
    position: absolute;
    top:230px;
    overflow: auto;
  }

  .delBroadcastButton
  {
    height: 20px;
    width:80px;
    border:1px solid black;
    text-align: center;
  }

  .delBroadcastButton:hover
  {
   background-color: lightblue;
   cursor: pointer;
  }

  .replyBut
  {
  height: 20px;
  width:50px;
  border:1px solid black;
  float:left;
  margin:1px;
  cursor: pointer;
  }

  .replyBut:hover
  {
    background-color: black;
    color:white;
  }

  .replyText
  {
    height:25px;
    width:200px;
    float: left;
  }
  .replyDelBut
  {
    height: 20px;
    width:80px;
    border:1px solid black;
    text-align: center;
    cursor: pointer;
  }
  .replyDelBut:hover
  {
    background-color: black;
    color:white;
  }
  </style>
</head>
<body>
  <section id="broadcastForm">
  <table align="center">
    <tr>
      <td>Title:<input type="text" id="titleText" placeholder="Enter Title here..."/></td>
    </tr>
    <tr>
      <td><textarea id="broadcastTextId" placeholder="Enter your description..."></textarea></td>
    </tr>
    <tr>
      <td>Upload file:<input type="file" id="file" name="file" /></td>
    </tr>
    <tr>
      <td><input type="submit" value="Broadcast" onclick="postBroadcast()"/></td>
    </tr>
  </table>
</section>
<!--this section is for displaying all the broadcasts -->
<section id="viewAllBroadcast">
<h1 style="text-align:center">Previous queries</h1>
<!-- this div is for showing the all the previous broadcasts -->
<div id="idForViewBroadcastDiv">
<?php
$query=mysql_query("Select * from userBroadcast order by bId desc");
while($res=mysql_fetch_assoc($query))
{
  $bId=$res['bId'];
  $userOfBroadcastId=$res['userId'];
  $bTitle=$res['bTitle'];
  $bText=$res['broadcastText'];
  $fileName=$res['fileName'];
  $time=$res['time'];

  //id for each div corresponding to a particular broadcast//
  $broadcastDivId="broadcastDiv".$bId;
  $deleteButtonId="deleteBut".$bId;

  //ids for query section buttons//
  $queryTextId="queryText".$bId;//id for the each  query text box//
  $submitQueryButId="submitQueryBut".$bId;
  $showAllQueriesDivId="showAllQueriesDiv".$bId;
  //query section ends here//

?>
<div id="<?php echo $broadcastDivId; ?>" class="classForEachBroadcast">
<h1><?php echo $bTitle; ?></h1>
<?php echo $bText; ?><br/>
<?php echo $time; ?>
<br/>
<!--query section starts here -->
<input type="text" placeholder="Enter your query here..." id="<?php echo $queryTextId; ?>"/>
<input type="submit" value="Submit Query" onclick="postQuery(<?php echo $bId; ?>)" id="<?php echo $submitQueryButId; ?>" />
<!--query section ends here -->
<?php
if($userOfBroadcastId==$uid)
{
 ?>
<div class="delBroadcastButton" id="<?php echo $deleteButtonId; ?>" onclick="deleteBroadcast(<?php echo $bId;  ?>)">Delete</div>
<?php
}
 ?>
<!--showing previous queries -->
<div id="<?php echo $showAllQueriesDivId; ?>" class="showAllQueries">
<?php
$queries=mysql_query("select * from broadcastQuery where bId='$bId'");
$countOfQueries=mysql_num_rows($queries);
?>
<p>QUERIES:<?php echo $countOfQueries; ?></p>
<?php
while($res=mysql_fetch_assoc($queries))
{
  $queryId=$res['queryId'];//id of the query
  $qText=$res['qText'];
  $qTime=$res['qTime'];
  $userIdOfQuery=$res['userId'];
  $userDetOfQuery=mysql_fetch_assoc(mysql_query("select fname,lname from user where userid='$userIdOfQuery'"));//details of the user who asked the query//
  $queryUser=$userDetOfQuery['fname']." ".$userDetOfQuery['lname'];
  echo "<h1>$qText</h1>";
  echo $queryUser;
  echo $qTime;
  $replyTextId="replyText".$queryId;
  $replyButId="replyBut".$queryId;
  $replyDelButId="replyDelBut".$queryId;
  $replySecDivId="replySecDiv".$queryId;
?>
<br/>
<input class="replyText" id="<?php echo $replyTextId; ?>" type="text" placeholder="Write reply..." />
<div class="replyBut" onclick="postReplyToQuery(<?php echo $queryId; ?>)" id="<?php echo $replyButId; ?>">Reply</div>
<!--delete button should appear if the the user of the query is the current user or the current user is the user of the broadcast -->
<?php
if($userIdOfQuery==$uid || $userOfBroadcastId==$uid)
{
?>
<div class="replyBut" id="<?php echo $replyDelButId; ?>" onclick="deleteQuery(<?php echo $bId; ?>,<?php echo $queryId; ?>)">Delete</div>
<?php
}
?>
<br/>
<br/>
<div id="<?php echo $replySecDivId; ?>" class="showAllRepliesToQuery">
<?php
$query=mysql_query("select * from replyToQueryOfBroadcast where qId='$queryId'");
$countReplies=mysql_num_rows($query);
 ?>
 Replies:<?php echo $countReplies; ?>
 <br/>
 <?php
while($res=mysql_fetch_assoc($query))
{
$rId=$res['rId'];
$userId=$res['userId'];
$replyText=$res['replyText'];
$uname=mysql_fetch_assoc(mysql_query("select fname,lname from user where userid='$userId'"));
$name=$uname['fname']." ".$uname['lname'].":";
echo $name;
echo $replyText;
//id for the delete button for deleting the reply to  a query//
$delReplyButId="delReplyBut".$rId;
if($userId==$uid || $userOfBroadcastId==$uid)
{
?>
<div class="replyDelBut" id="<?php echo $rId; ?>" onclick="delReplyOfQueryOfBroadcast(<?php echo $rId; ?>,<?php echo $queryId;?>)">Delete</div>
<?php
}
echo "<br/>";
}
?>
</div>
<?php
echo "<br/>";
}
?>
</div>
<!-- showing all queries ende here -->
</div>
<?php
echo "<br/>";

}
?>
</div>
</section>
<script>
function delReplyOfQueryOfBroadcast(replyId,queryId)
{
 //var id=document.getElementById("replySecDiv"+queryId);
  if(window.XMLHttpRequest){
      xmlhttp=new XMLHttpRequest();
    }
    else{
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
   xmlhttp.onreadystatechange=function(){

                 if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                  document.getElementById("replySecDiv"+queryId).innerHTML=xmlhttp.responseText;

                 }
               }
               xmlhttp.open("REQUEST","delReplyOfQueryOfBroadcast.php?replyId="+replyId+"&queryId="+queryId,true);
        xmlhttp.send();
}
function deleteQuery(bId,qId)
{
//var id=document.getElementById("showAllQueriesDiv"+bId);
//alert(id);
  if(window.XMLHttpRequest){
      xmlhttp=new XMLHttpRequest();
    }
    else{
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
   xmlhttp.onreadystatechange=function(){

                 if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                   document.getElementById("showAllQueriesDiv"+bId).innerHTML=xmlhttp.responseText;

                 }
               }
               xmlhttp.open("REQUEST","deleteQueryOfBroadcast.php?qId="+qId+"&bId="+bId,true);
        xmlhttp.send();
}
function postReplyToQuery(qId)
{
//var id=document.getElementById("replySecDiv"+qId);
var replyTextId=document.getElementById("replyText"+qId);
var replyText=replyTextId.value;
if(replyText=="")
{
  alert("required field");
}
  if(window.XMLHttpRequest){
      xmlhttp=new XMLHttpRequest();
    }
    else{
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    replyTextId.value="";
   xmlhttp.onreadystatechange=function(){

                 if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                  document.getElementById("replySecDiv"+qId).innerHTML=xmlhttp.responseText;

                 }
               }
               xmlhttp.open("REQUEST","postReplyToQueryOfBroadcast.php?qId="+qId+"&replyText="+replyText,true);
        xmlhttp.send();
}
function postQuery(bId)
{
  var queryTextId=document.getElementById("queryText"+bId);
  qText=queryTextId.value;
  d = new Date();
    mon = d.getMonth()+1;
  time = d.getDate()+"-"+mon+"-"+d.getFullYear()+" "+d.getHours()+":"+d.getMinutes();
  if(window.XMLHttpRequest){
      xmlhttp=new XMLHttpRequest();
    }
    else{
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    queryTextId.value="";
   xmlhttp.onreadystatechange=function(){

                 if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                   document.getElementById("showAllQueriesDiv"+bId).innerHTML=xmlhttp.responseText;

                 }
               }
               xmlhttp.open("REQUEST","postQueryOfBroadcast.php?bId="+bId+"&queryText="+qText+"&qTime="+time,true);
        xmlhttp.send();
}
function postBroadcast()
{
var titleTextId=document.getElementById("titleText");
var broadcastTextId=document.getElementById("broadcastTextId");
var fileNameId=document.getElementById("file");
var titleText=titleTextId.value;
var broadcastText=broadcastTextId.value;
var fileName=fileNameId.value;
d = new Date();
mon = d.getMonth()+1;
time = d.getDate()+"-"+mon+"-"+d.getFullYear()+" "+d.getHours()+":"+d.getMinutes();
if(titleText==""||broadcastText=="")
{
  alert("all fields are required");
}
else
{
if(window.XMLHttpRequest){
    xmlhttp=new XMLHttpRequest();
  }
  else{
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
titleTextId.value="";
broadcastTextId.value="";
fileNameId.value="";
 xmlhttp.onreadystatechange=function(){

               if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                 document.getElementById("viewAllBroadcast").innerHTML=xmlhttp.responseText;

               }
             }
             xmlhttp.open("REQUEST","postBroadcast.php?titleText="+titleText+"&broadcastText="+broadcastText+"&fileName="+fileName+"&time="+time,true);
      xmlhttp.send();
    }
}
function deleteBroadcast(bId)
{
  if(window.XMLHttpRequest){
      xmlhttp=new XMLHttpRequest();
    }
    else{
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
   xmlhttp.onreadystatechange=function(){

                 if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                   document.getElementById("viewAllBroadcast").innerHTML=xmlhttp.responseText;

                 }
               }
               xmlhttp.open("REQUEST","deleteBroadcast.php?bId="+bId,true);
        xmlhttp.send();
}
</script>
</body>

</html>
