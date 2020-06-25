<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Databast&egrave; - Order List</title>
    <link rel="stylesheet" href="orderList.css">
  </head>
  <body>
    <?php
      include 'config.php';
      session_start();
      include 'orderListGenreation.php';
      $flag= false;
      $flag2= false;
      $cname= "";
      if(isset($_SESSION['uType']) && isset($_SESSION['cname'])){
        if($_SESSION['uType']=="agent"){
          if(isset($_SESSION['AID'])){
            $flag= true;
            $cname= $_SESSION['cname'];
          }
        }
        else{
          $flag= true;
          $cname= $_SESSION['cname'];
        }
      }
     ?>
    <header>
      <span class="logo">DataBast&egrave;</span>
        <span class="userOptions">
          <?php
            if($flag==true){
              echo '<a href="#" onclick="myFunction()">Hello, '.$cname.' &darr;</a>';
            }
           ?>
        </span>
    </header>
    <div class="dropDownContent" id="dropDownContent">
      <a href="#">Change Personal Information</a>
      <a href="logoutPage.php">Logout</a>
    </div>
    <main>
      <div class="returnToDashboard">
        <a href="/dataBaste/dashBoard.php"><strong>&larr;</strong> Go Back to Dashboard</a>
      </div>
      <div class="printThisPage">
        <a href="#" onclick="window.print()">Click Here to get a print Out</a>
      </div>
      <div class="contents" id="contents">
        <div class="formContents">
          <form class="" method="post">
            <h3>Enter Following Details</h3>
            <label for="startDate">Enter Starting Date</label>
            <input type="date" name="startDate" value="" id="startDate" placeholder="Enter starting date" max="<?php echo date("Y-m-d"); ?>" required/><br/>
            <label for="endDate">Enter Ending Date</label>
            <input type="date" name="endDate" value="" id="endDate" placeholder="Enter ending date" max="<?php echo date("Y-m-d"); ?>" required/><br/>
            <input type="submit" name="" value="Submit"/>
          </form>
        </div>
        <div class="orderList" id="orderList">
          <?php
            if($flag==true){
              if($_SERVER["REQUEST_METHOD"]=="POST"){
                if(isset($_POST['startDate']) && isset($_POST['endDate'])){
                  echo "<h1> Orders Of ".$_POST['startDate']." to ".$_POST['endDate']."</h1>";
                  $conn2= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
                  if(!$conn2){
                    die("Connection Failed ".$conn->connect_error);
                  }
                  printOrders($_POST['startDate'],$_POST['endDate'],$conn2,'online');
                  printOrders($_POST['startDate'],$_POST['endDate'],$conn2,'offline');
                  mysqli_close($conn2);
                }
              }
            }
           ?>
        </div>
      </div>
      <div class="ErrorMessage" id="ErrorMessage" style="display: none;">
        <img src="/dataBaste/warning.jpg" alt="" id="warningImg"/>
        <h1>You Don't have access to this page</h1>
        <h2>It seems you have wrongly accessed this page</h2>
        <h2>Move to login page to access your account</h2>
        <button type="button" name="button" onclick="window.location.href= 'index.html'">Go to Login Page</button>
      </div>
    </main>
    <footer>
      <p>Created by Group 3</p>
    </footer>
    <script type="text/javascript">
    <?php
      if($flag==false){
        echo "document.getElementById('contents').style.display='none';\n";
        echo "document.getElementById('ErrorMessage').style.display='';\n";
      }
     ?>
    document.getElementById('dropDownContent').style.display='none';
    function myFunction(){
      var elem= document.getElementById('dropDownContent');
      if(elem.style.display=='')
        elem.style.display= 'none';
      else {
        elem.style.display='';
      }
    }
    document.getElementById('endDate').oninput= function() {
      document.getElementById('startDate').max= document.getElementById('endDate').value;
    }
    document.getElementById('startDate').oninput= function() {
      document.getElementById('endDate').min= document.getElementById('startDate').value;
    }
    </script>
  </body>
</html>
