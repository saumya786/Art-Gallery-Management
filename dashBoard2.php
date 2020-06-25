<?php
  session_start();
  if(isset($_SESSION['uType']) && isset($_SESSION['cFname']) && isset($_SESSION['cLname'])){
    echo "<h1>User Type:- ".$_SESSION['uType']."</h1>";
    echo "<h3>User First Name:- ".$_SESSION['cFname']."<h3>";
    echo "<h3>User Last Name:- ".$_SESSION['cLname']."<h3>";
  }
 ?>
