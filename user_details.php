<?php
session_start();
error_reporting(0);
extract($_POST);
include('connection.php');
/*if(!isset($_SESSION['user'])){
    header('location:index.php');
}*/
$e=$_SESSION['user'];
$que=mysql_query("select fname from user where uname='$e'");
$res=mysql_fetch_assoc($que);
$fname=$res['fname'];
if(isset($forward))
{
header('location:user.php');
}

if(isset($save))
{
  $que=mysql_query("select gender from user where uname='$e'");
  $gen=mysql_fetch_assoc($que);
  $g=$gen['gender'];
 //for male//
  if(!file_exists("users/$g/$e")){
  mkdir("users/$g/$e");
  mkdir("users/$g/$e/profile_pic");
}
//uploading the pic//

$que=mysql_fetch_assoc(mysql_query("select userid from user where uname='$e'"));
$uid=$que['userid'];
$q=mysql_fetch_assoc(mysql_query("select prof_pic from user_details where user_id='$uid'"));
$pname=$q['prof_pic'];
if(file_exists("users/$g/$e/profile_pic/$pname"))
{
  $del=mysql_query("delete from user_details where user_id='$uid'");
  unlink("users/$g/$e/profile_pic/$pname");
  $pn=$_FILES['pic']['name'];
  mysql_query("insert into user_details values('$uid','$pn')");
  move_uploaded_file($_FILES['pic']['tmp_name'],"users/$g /$e/profile_pic/".$_FILES['pic']['name']);
  echo "<h1 style='color:green'>Details submitted successfully</h1>";
}
//insert pic into user_details//
else
{
 $pn=$_FILES['pic']['name'];
 echo $pn;
 mysql_query("insert into user_details values('$uid','$pn')");
 move_uploaded_file($_FILES['pic']['tmp_name'],"users/$g/$e/profile_pic/".$_FILES['pic']['name']);
 echo "<h1 style='color:green'>file submitted successfully</h1>";
}
}
 ?>
 <html>
 <head>
 <title><?php echo $fname ; ?></title>
 </head>
 <body>
 <form method="post" enctype="multipart/form-data">
 <table>
 <tr>
    <td>Profile pic</td>
    <td><input type="file" name="pic"/></td>
 </tr>
 <tr>
    <td>
    Course:
    <select name="course">
    <option>B-Tech</option>
    <option>M-Tech</option>
    </select>
    </td>
  </tr>
  <tr>
    <td>
        Branch:
       <select name="course">
        <option value="Information Technology">Information Technology</option>
        <option value="Civil">Civil Engg</option>
        <option value="Mechanical">Mechanical Engg</option>
        <option value="Electronics">Electronics</option>
        <option value="Electrical">Electrical</option>
        <option value="Production Engg">Production Engg</option>
      </select>
    </td>
     <td>
       <select name="gradYear">
         <option value="2015">2015</option>
         <option value="2016">2016</option>
         <option value="2017">2017</option>
         <option value="2018">2018</option>
         <option value="2019">2019</option>
         <option value="2020">2020</option>
        </select>
     </td>
    </tr>
    <tr>
      <td><input type="text" name="state" placeholder="State"/></td>
      <td><input type="text" name="country" placeholder="city"/></td>
    </tr>
    <tr>
      <td>Intrested in:</td>
      <td>
       Programming:<input type="checkbox" name="interest[]" value="Programming"/>
       Devlopment:<input type="checkbox" name="interest[]" value="Devlopment"/>
       Art:<input type="checkbox" name="interest[]" value="Art"/>
       Graphics and Designing:<input type="checkbox" name="interest[]" value="Graphics_and_Designing"/>
       Web:<input type="checkbox" name="interest[]" value="Web"/>
       Dance:<input type="checkbox" name="interest[]" value="Dance"/>
      </td>
    <tr>
      <td><input type="submit" name='save' value="submit"/></td>
      <td><input type="submit" name="forward" value="Enter the world ->  "></td>
    </tr>
 </table>
 </form>
 </body>
 </html>
