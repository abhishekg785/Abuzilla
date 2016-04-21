<?php
session_start();
include('connection.php');
extract($_REQUEST);
$uid=$_SESSION['uid'];
$e=$_SESSION['user'];
$gender=$_SESSION['gender'];
$uid=$_SESSION['uid'];
$cTitle=$cTitle;
$cText=$cText;
$file=$fileName;
$cTime=$cTime;
//echo $cTitle;
//echo $cText;
//echo $file;
//upload the file in the complains folder//
if(!file_exists("users/$gen/$e/complainFiles"))
{
 mkdir("users/$gender/$e/complainFiles");
}
mysql_query("insert into userComplain values('','$uid','$cTitle','$cText','$file','$cTime')");
//move_uploaded_file($_FILES['file']['tmp_name'],"users/$gender/$e/complainFiles/".$file);
?>
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
echo $nameOfComplainUser." ".$cTime;
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
