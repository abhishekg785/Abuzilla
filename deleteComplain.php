<?php
session_start();
include('connection.php');
$uid=$_SESSION['uid'];
extract($_REQUEST);
$cId=$cId;
mysql_query("delete from userComplain where cId='$cId'");
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
