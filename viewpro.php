<?php
session_start();
$uname=$_SESSION['user'];
$name=$_GET['profile'];
?>
<html>
<head>
  <style>
  body{
    margin: 0px;
    padding: 0px;
  }
  header
  {
    position: absolute;
    width:100%;
    height:50px;
     background-color:black;
     color: white;
     padding: 30px;
  }

  #sec{
    width:100%;
    height:100%;
    background-color: lightblue;
  }
  </style>
</head>
<body>
  <header>
   <?php
  echo "<h1 align='center'>hello i am $name</h1>";
    ?>
  </header>
  <section id="sec">

</section>
</body>
</html>
