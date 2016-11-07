<?php
error_reporting(0);
session_start();
include('connection.php');
extract($_POST);
$e=$_SESSION['user'];
$que=mysql_query("select * from user where uname='$e'");
$res=mysql_fetch_assoc($que);
$uid=$res['userid'];
$gen=$res['gender'];
if(!file_exists("users/$gen/$e/post"))
{
  mkdir("users/$gen/$e/post");
}
if(isset($sub))
{
  $post_txt=$ptext;
  $post_pic=$_FILES['file']['name'];
  $post_time=$date;
  $q=mysql_query("insert into user_post values('','$uid','$post_txt','$post_pic','$post_time')");
  move_uploaded_file($_FILES['file']['tmp_name'],"users/$gen/$e/post/".$_FILES['file']['name']);
}


//delete post//
if(isset($del_post))
{
  $pid=$pid;
  $qt=mysql_fetch_assoc(mysql_query("select post_pic from user_post where post_id='$pid'"));
  $pic=$qt['post_pic'];
  unlink("users/$gen/$e/post/$pic");
  mysql_query("delete from post_status where post_id='$pid'");
  mysql_query("delete from user_post where post_id='$pid'");
  //deletes all commments and all replies corresponding
  //mysql_fetch_assoc(mysql_query("select "))
  mysql_query("delete from user_comment where post_id='$pid'");

}
//del_post//
?>
<html>
<head>
  <link rel="stylesheet" type="text/css" href="css/like&unlike.css">
  <style>
  body,html
  {
    padding: 0px;
    margin:0px;
  }
  #mid
  {
    margin:0px auto;
    width:85%;
    height:100%;
    background-color: lightgreen;
    overflow:auto;
  }
  table
  {
  margin:100px auto;
  }
  #area
  {
   height:130px;
   width:500px;
   font-size: 30px;
   }
   #parea
    {
    width:75%;
    height:50px;
    margin:0px auto;
    }

    .like_click{
      color:green;
    }
    .unlike_click{
      color:red;
    }
    .but{
      cursor: pointer;
    }
    .delComBut{
      height: 20px;
      width:100px;
      border:1px solid black;
      text-align: center;
      padding: 1px ;
      margin: 1px;
    }
    .delComBut:hover{
      background-color:lightblue;
      cursor: pointer;
      border: 2px solid black;
    }
    .commSec{
      border: 1px solid black;
    }

    .relplyAttr
    {
      float:left;
    }

    #replyBut
    {
      height:22px;
      width:80px;
      border: 2px solid black;
      text-align: center;
      cursor: pointer;
    }

    #replyBut:hover
    {
      background-color: blue;
    }

    .delReplyBut
    {
      height:20px;
      text-align: center;
      width:80px;
      border:1px solid black;
    }

    .delReplyBut:hover
    {

      background-color: blue;
      cursor: pointer;
    }
  </style>
  <script src="js/date.js"></script>
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
   <section id="mid">
   <form method="post" enctype="multipart/form-data" name="myform">
   <input type="hidden" name="date"/>
   <table>
   <tr>
      <td><textarea id="area" placeholder="post here" name="ptext"></textarea></td>
   </tr>
   <tr>
      <td><input type="file" name="file"/></td>
   </tr>
   <tr>
      <td><input type="submit" name="sub" value="post" onClick="getDate()"/></td>
   </tr>
   </table>
   </form>
   <hr/>
    <section id="parea">
   <?php
    $que=mysql_query("select * from user where uname='$e'");
    $res=mysql_fetch_assoc($que);
    $uid=$res['userid'];
    $gen=$res['gender'];
    $qut=mysql_query("select * from user_post order by post_id desc");
    while($res=mysql_fetch_assoc($qut)){
           $pid=$res['post_id'];
            $upid=$res['user_id'];//user of particular post//
            $pic=$res['post_pic'];
            $qte=mysql_query("select * from  user where userid='$upid'");
            $rt=mysql_fetch_assoc($qte);
            $fn=$rt['fname'];
            $ln=$rt['lname'];
            $cn=$fn." ".$ln;
            $gen=$rt['gender'];
            $un=$rt['uname'];
            $p_time=$res['post_time'];
            echo "<h1 align=\"left\">$cn</h1>";
            echo "<h1 align=\"right\">$p_time</h1>";
            if($pic==""){
            }
             else{
            echo "<img align=\"center\" height=\"500px\"  width=\"500px\" src=\"users/$gen/$un/post/$pic\" />";
             }
            echo "<br/>";
            echo $res['post_txt'];
            if($upid==$uid)
            {
              ?>
            <form method="post">
            <input type="submit" name="del_post" value="Delete Post" />
            <input type="hidden" name="pid" value="<?php echo $pid ;?>" />
            </form>
            <?php
            }

          //like section//

            $likeid="like".$pid;//id of each like button//
            $unlikeid="unlike".$pid;//id of each unlike button//

            $postLikeStatId="postStatId".$pid;

            $pStatusQ=mysql_query("select * from post_status where post_id='$pid' and user_id='$uid'");
            $res_status=mysql_fetch_assoc($pStatusQ);
            $action=$res_status['status'];
            //like and ulike section starts here//
            $rate_all_count = mysql_query('SELECT COUNT(*) FROM post_status WHERE post_id = "'.$pid.'"');
            $rate_all_count = mysql_result($rate_all_count, 0);
            $rate_like_count =mysql_num_rows(mysql_query("select * from post_status where post_id='$pid' and status='like'"));
            $rate_like_percent =($rate_like_count/$rate_all_count)*100;


            $rate_dislike_count = mysql_num_rows(mysql_query("select * from post_status where post_id='$pid' and status='unlike'"));
            $rate_dislike_percent = ($rate_dislike_count/$rate_all_count)*100;


            //like and unlike section ends here//


        ?>

           <div class="tab-tr">
           <div id="<?php echo $likeid; ?>"   class="but like-btn <?php if($action=="like"){echo 'like_click' ;} ?>"  onClick="like(<?php echo $pid; ?>,'likePost')">Like</div>
           <div id="<?php echo $unlikeid; ?>"  class=" but dislike-btn <?php if($action=="unlike"){echo 'unlike_click';} ?>"   onClick="like(<?php echo $pid; ?>,'unlikePost')">Unlike</div>


           <div  class="stat-cnt">
           <div id="<?php echo $postLikeStatId; ?>" >
           <div  class="rate-count"><?php echo $rate_all_count; ?></div>
           <div  class="stat-bar">
           <div  class="bg-green" style="width:<?php echo $rate_like_percent; ?>%;"></div>
           <div  class="bg-red" style="width:<?php echo $rate_dislike_percent; ?>%"></div>
           </div><!-- stat-bar -->
           <div  class="dislike-count"><?php echo $rate_dislike_count; ?></div>
           <div  class="like-count"><?php echo $rate_like_count; ?></div>
           </div>
           </div><!-- /stat-cnt -->
           </div>

          <?php
           $commTextId="commText".$pid;
           $commSec="commSec".$pid;//section of the comment area//
           $query=mysql_query("select * from user_comment where post_id='$pid' order by comment_id");
           $countComm=mysql_num_rows($query);//no of users corresponding to a particular post//
           ?>
          <input type="text" id="<?php echo $commTextId; ?>" name="com_txt"  placeholder="enter your comment here..." />
          <button onclick="postCom(<?php echo $pid; ?>)">Comment</button>
          <br/>
          <div class="commSec" id="<?php echo $commSec; ?>">
          Comments:<?php echo $countComm; ?>
          <br/>
          <?php
      while($res=mysql_fetch_assoc($query)){
      $commentId=$res['comment_id'];
      $userIdOfComment=$res['user_id'];
      $userDet=mysql_query("select fname,lname from user where userid='$userIdOfComment'");
      $userRes=mysql_fetch_assoc($userDet);
      echo $userRes['fname']." ".$userRes['lname'].":".$res['comm_text'];
      //attributes of the replysection//
      $replySecDivId="replySecDiv".$commentId;
      $replyTextId="replyText".$commentId;
      ?>
      <br/>
      <input type="text" id="<?php echo $replyTextId; ?>" class="relplyAttr" placeholder="Enter your reply..." /><div  class="relplyAttr" id="replyBut" onclick="postReplyToCommment(<?php echo $commentId; ?>)" >Reply</div><br/>
      <br/>
      <div id="<?php echo $replySecDivId; ?>">
      <?php
      $replyQ=mysql_query("select * from replyTocommentOfPost where commentId='$commentId'");
      $countOfReplies=mysql_num_rows($replyQ);
      echo "Replies".":".$countOfReplies;
      echo "<br/>";
      while($res=mysql_fetch_assoc($replyQ))
      {
      $replyId=$res['replyId'];
      $commentId=$res['commentId'];
      $userOfReplyId=$res['userId'];
      $replyText=$res['replyText'];
      $userOfReply=mysql_fetch_assoc(mysql_query("select fname,lname from user where userid='$userOfReplyId'"));
      echo $userOfReply['fname']." ".$userOfReply['lname'].":".$replyText;

      $delReplyOfPostButId="delReplyOfPostBut".$replyId;//this is for deleting the reply to the comment of the post//
      if($userOfReplyId==$uid||$userIdOfComment==$uid)
      {
      ?>
      <!--section fr deeting the reply on comment of the post-->
      <div class="delReplyBut" id="<?php echo $delReplyOfPostButId; ?>" onclick="deleteReplyOfPost(<?php echo $replyId; ?>,<?php echo $commentId;?>)">Delete</div>
      <?php
      }
      echo "<br/>";
      }

      ?>
      </div>
      <?php
      echo "<br/>";

      //delete option comes only if the post belongs to the user or the comment belongs to the user on different user post//
      if($upid==$uid || $userIdOfComment==$uid )
      {
      $delComButId="delComBut".$commentId;//id for ech delete button for the comment//
      ?>
      <!--delete button for deleteing the comment -->
      <div class="delComBut" id="<?php echo $delComButId; ?>"  onclick="deleteComment(<?php echo $commentId; ?>,<?php echo $pid; ?>)">Delete Comment</div>
      <!--delete portion ends here -->
      <?php
      }
      }
      ?>
  </div>
  <?php
  echo "<hr/>";
  }
  ?>
  </section>
  </section>
  <section id="right"></section>
 <script src="js/postLikeSystem.js"></script>
 <script src="js/delete.js"></script>
 <script>
 function deleteReplyOfPost(replyId,commentId)
 {
   if(window.XMLHttpRequest){
     xmlhttp=new XMLHttpRequest();
   }
   else{
     xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
   }
   xmlhttp.onreadystatechange=function(){

                if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                  document.getElementById("replySecDiv"+commentId).innerHTML=xmlhttp.responseText;
               }
              }
              xmlhttp.open("REQUEST","deleteReplyToCommentOfPost.php?commentId="+commentId+"&replyId="+replyId,true);
       xmlhttp.send();
 }
 function postReplyToCommment(commentId)
 {
 //var id=document.getElementById("replySecDiv"+commentId);
 var replyTextId=document.getElementById("replyText"+commentId);
 var text=replyTextId.value;
 if(replyTextId.value=="")
 {
  alert("All fields required");
 }
 else
 {
if(window.XMLHttpRequest){
  xmlhttp=new XMLHttpRequest();
}
else{
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
replyTextId.value="";
xmlhttp.onreadystatechange=function(){

             if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
               document.getElementById("replySecDiv"+commentId).innerHTML=xmlhttp.responseText;

             }
           }
           xmlhttp.open("REQUEST","postReplyToCommentOfPost.php?commentId="+commentId+"&replyText="+text,true);
    xmlhttp.send();
  }
}

function postCom(cId){
var commTextId=document.getElementById("commText"+cId);//id of thecomment text box
var commSec=document.getElementById("commSec"+cId);
text=commTextId.value;
if(text==""){
  alert("this field is necessary");
}
else{
if(window.XMLHttpRequest){
  xmlhttp=new XMLHttpRequest();
}
else{
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
commTextId.value="";
xmlhttp.onreadystatechange=function(){

             if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
               document.getElementById("commSec"+cId).innerHTML=xmlhttp.responseText;

             }
           }
           xmlhttp.open("REQUEST","postComment.php?postId="+cId+"&commText="+text,true);
    xmlhttp.send();
}
}
 </script>
</body>
</html>
