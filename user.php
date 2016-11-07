<?php
error_reporting(0);
include('connection.php');
session_start();
if($_SESSION['user']=="")
{
  echo "<script>";
  echo "window.location='index.php'";
  echo "</script>";
}
$e=$_SESSION['user'];
$query=mysql_query("select  userid,gender from user where uname='$e'");
$res=mysql_fetch_assoc($query);
$uid=$res['userid'];
$gender=$res['gender'];
$_SESSION['uid']=$uid;
$_SESSION['gender']=$gender;
?>
 <html>
 <head>
  <title><?php echo $res['fname']; ?></title>
 <style>

body,html
{
  margin: 0px;
  padding: 0px;
}

#cont
{
  width:100%;
  height:100%;
  background-color: #fcc;
}

#uinfo
{
  width:100%;
  height:50px;
  background-color: lightblue;
  margin:0px;
  padding: 0px;
  text-align: center;
  font-size: 30px;
  z-index: 300;
  position: fixed;
}

#sec
{
  height:100%;
  width:100%;
  position: absolute;
  top:30px;
}

#suggest
{
font-size: 20px;
position: absolute;
right:10px;
}

#notiBar
{
position: absolute;
height:30px;
width:50px;
border: 1px solid black;
right:20px;
margin: 0px;
padding: 0px;
text-align: center;
top:5px;
}

</style>
 </head>
 <body>
 <header id="uinfo">
 <?php echo $e; ?>
 <a  href="logout.php">Logout</a>
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <a href="user.php">Home</a>
 <input type="text"  id="text" name="Name" placeholder="search people"  autocomplete="off" onkeyup="search(this.value)"/>
 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
 <input type="text"  id="text" name="Name" placeholder="search Questions"  autocomplete="off" onkeyup="search2(this.value)" />
 <div id="notiBar">
<!--this section is for notiication of  user -->
 <?php
 //firstly find the questions asked by current user//
 $ques=mysql_query("select ques_id from ask_ques where user_id='$uid'");
 while($res=mysql_fetch_assoc($ques))
 {
 $idOfQ=$res['ques_id'];//id of particular ques//
 $ansCorresQues=mysql_query("select * from ans_ques where ques_id='$idOfQ' and user_id !='$uid'");//answers correspnding to ques
 $ct=mysql_num_rows($ansCorresQues);
 $count=$count+$ct;
 }
 echo $count;
 ?>
 <!--  notiication section of  user ends here -->
 </div>
 <div id="suggest">
 </div>
 </header>
 <section id="sec">
 <?php
 $val=$_GET['x'];
     if($val=='post')
     {
     include('post.php');
     }
     else if($val=='ask')
     {
     include('ask.php');
     }
     else if($val=='review')
     {
     include('review.php');
     }
     else if($val=='know_people')
     {
     include('know_people.php');
     }
     else if($val=='message')
     {
     include('message.php');
     }
     else if($val=='complain')
     {
     include('complain.php');
     }
     else if($val=='vote')
     {
     include('vote.php');
     }
     else if($val=='broadcast')
     {
       include('broadcast.php');
     }
     else if($val=='workTogether')
     {
       include('workTogether.php');
     }
     else if($val=='discuss')
     {
       include('discuss.php');
     }
      else
      {
    ?>
     <h1><a href="user.php?x=post">Post Something</a></h1>
     <h1><a href="user.php?x=ask">Ask Questions</a></h1>
     <h1><a href="user.php?x=review">Get Reviews</a></h1>
     <h1><a href="user.php?x=know_people">Know the people</a></h1>
     <h1><a href="user.php?x=message">Message</a></h1>
     <h1><a href="user.php?x=complain">Complain</a></h1>
     <h1><a href="user.php?x=vote">Do Voting</a></h1>
     <h1><a href="user.php?x=broadcast">Broadcast</a></h1>
     <h1><a href="user.php?x=workTogether">Work Collaboratively</a></h1>
     <h1><a href="user.php?x=workTogether">All profile at one place</a></h1>
     <h1><a href="user.php?x=discuss">Discuss</a></h1>
     <h1>Details about khokha food</h1>
     <?php
      }
      ?>
 </section>
 <script src="js/search.js"></script>
 </body>
 </html>
