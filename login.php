<?php
error_reporting(0);
session_start();
include('connection.php');
extract($_POST);
if(isset($sub))
{
if($uname==""||$pass=="")
{
  echo "<h1 style='color:red'>fill all fields</h1>";
}
else{

  $un=$uname;
  $pass=$pass;
$qu=mysql_query("select * from user where uname='$un' and password='$pass'");
if(mysql_num_rows($qu))//if user exists//
{
 $res=mysql_fetch_assoc($qu);
 $uid=$res['userid'];
 $_SESSION['uid']=$uid;
 $_SESSION['user']=$uname;
 header('location:user.php');
}
else{
  echo "<h1 style='color:red'>user does not exist</h1>";
}
}

}



 ?>
<html>
<head>
  <title>
    Login Page
  </title>
  <style>
  #checkMail{

    height:20px;
    width:100px;
    margin: 0px;
    padding: 0px;
    font-size:15px;
  }
  </style>
</head>
<body>
  <table>
    <form method="post">

      <tr>
        <td>User Name:</td>
        <td><input type="email" name="uname" onblur="checkMail(this.value)"/></td>
        <td><div id="checkMail"></div></td>
      </tr>
      <tr>
        <td>Password:</td>
        <td><input type="password" name="pass"/></td>
      </tr>
     <td><input type="submit" name="sub" value="Log In"/></td>
      </tr>
    </form>
  </table>
  <script src="js/jquery-1.6.2.min.js"></script>
  <script>
function checkMail(eId){
  if(window.XMLHttpRequest){
      xmlhttp=new XMLHttpRequest();
    }
    else{
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange=function(){

                 if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                   document.getElementById("checkMail").innerHTML=xmlhttp.responseText;

                 }
               }
               xmlhttp.open("REQUEST","checkUserAtLogin.php?val="+eId,true);
        xmlhttp.send();
}

  </script>
</body>
</html>
