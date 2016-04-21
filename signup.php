<?php
error_reporting(0);
session_start();
include('connection.php');
extract($_POST);
if(isset($sub))
{
$fn=$fname;
$ln=$lname;
$un=$uname;
$pass=$pass;
$gen=$gen;
if($fn==""||$un==""||$pass=="")
{
  echo "<h1 style='color:red'>fill all fields</h1>";
}
  else{
  $qu=mysql_query("select * from user where uname='$un'");
  if(mysql_num_rows($qu))
  {
    echo "<h1 style='color:red'>user exists</h1>";
  }
  else{
  $que=mysql_query("insert into user values('','$fn','$ln','$un','$pass','$gen')");
  echo "record inserted";
  $e=$_SESSION['user']=$un;
  $_SESSION['gender']=$gen;
  if(!file_exists("users/$gen/$e")){
  mkdir("users/$gen/$e");
  }
  header('location:user_details.php');
}
}

}
 ?>
 <html>
 <head>
   <title>Sign Up</title>
     <style>
         #suggestBox{
             height:30px;
             width:200px;
             border:2px solid black;
             overflow: none;
             font-size: 20px;
             text-align: center;
             margin: 0px;
             padding: 0px;
         }
         #suggestBox p{
          margin: 0px;
          padding: 0px;

         }
     </style>
 </head>
 <body>
<table>
  <form method="post">
    <tr>
      <td>First Name:</td>
      <td><input type="text" name="fname"/></td>
    </tr>
    <tr>
      <td>Last Name:</td>
      <td><input type="text" name="lname"/></td>
    </tr>
    <tr>
      <td>Username</td>
      <td><input type="text" id="uname" name="uname" placeholder="some@example.com"   onblur="checkMail(this.value)"/> </td>
      <td><div id="suggestBox"></div></td>
    </tr>
    <tr>
      <td>Password</td>
      <td><input type="password" name="pass" /></td>
    </tr>
    <tr>
      <td>Gender:</td>
      <td>Male:<input type="radio" name="gen" value="male"/>Female:<input type="radio" name="gen" value="female" /></td>
    </tr>

    <tr>

      <td><input type="submit" name="sub" value="Sign up"/></td>
    </tr>
  </form>
</table>

<script>
function checkMail(val){
  if(val==""){

    document.getElementById("suggestBox").innerHTML="";
  }
  else{
  if(window.XMLHttpRequest){
    xmlhttp=new XMLHttpRequest();
  }
  else{
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function(){

               if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                 document.getElementById("suggestBox").innerHTML=xmlhttp.responseText;

               }
             }
             xmlhttp.open("REQUEST","checkExistingMail.php?val="+val,true);
      xmlhttp.send();
    }
}
</script>
 </body>
 </html>
