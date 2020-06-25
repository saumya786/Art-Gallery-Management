<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Databast&egrave; - My Orders</title>
    <link rel="stylesheet" href="myOrders.css">
  </head>
  <body>
    <?php
      function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
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
      if($flag==true){
        if($_SERVER["REQUEST_METHOD"]=="POST"){
          if (isset($_POST['orderId'])) {
            $orderId= test_input($_POST['orderId']);
            $_SESSION['orderId']= $orderId;
            echo "<h1>".$_SESSION['orderId']."</h1>";
            /*header("location : /dataBaste/successPage.php");*/
            header('location: /dataBaste/successPage.php');
          }
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
        <?php
          if($flag==true){
            $query1="";
            if($_SESSION['uType']=="agent"){
              $query1= "SELECT * FROM ORDERS WHERE ORDERID IN (SELECT ORDERID FROM OFFLINEORDERS WHERE AID={$_SESSION['AID']})";
            }
            elseif ($_SESSION['uType']=="customer") {
              $query1= "SELECT * FROM ORDERS WHERE CNAME='{$_SESSION['cname']}'";
            }
            $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
            $result= $conn->query($query1);
            if($result->num_rows>0){
              echo "<div class='orderList'>";
              if($_SESSION['uType']=="agent"){
                echo "<h1>List of Orders of Agent {$_SESSION['cname']} </h1>";
              }
              elseif ($_SESSION['uType']=="customer") {
                echo "<h1>{$_SESSION['cname']} , your Orders </h1>";
              }
              echo "<h3 class='genInfo'>Click on Any of the orders to get the invoice</h3>";
              while($row= $result->fetch_assoc()){
                echo "<a href='#' onclick='myFunction2({$row['ORDERID']})'><div class='orderDetails'>
                  <div class='orderAmount'>
                    &#8377; {$row['ORDERAMOUNT']}
                  </div>
                  <span class='point'>Order Id:- </span>{$row['ORDERID']} <br>
                  <span class='point'>Order Type:- </span>{$row['ORDERTYPE']} <br>
                  <span class='point'>Order Date and Time:- </span>{$row['ORDERDATEANDTIME']} <br>
                  <span class='point'>Payment Mode:- </span>{$row['PAYMENTMODE']} <br>
                  <span class='point'>Payment Date And Time:- </span>{$row['PAYMENTDATEANDTIME']} <br>";
                  if($_SESSION['uType']=="agent"){
                    echo "<span class='point'>Customer Name</span> {$row['CNAME']} <br>";
                  }
                echo "</div></a>";
              }
              echo "</div>";
            }
          }
         ?>
      </div>
      <div class="ErrorMessage" id="ErrorMessage" style="display: none;">
        <img src="/dataBaste/warning.jpg" alt="" id="warningImg"/>
        <h1>You Don't have access to this page</h1>
        <h2>It seems you have wrongly accessed this page</h2>
        <h2>Move to login page to access your account</h2>
        <button type="button" name="button" onclick="window.location.href= 'index.html'">Go to Login Page</button>
      </div>
      <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" id="form1">
        <input type="hidden" name="orderId" id="orderId" value="">
      </form>
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
    function myFunction2(orderNumber){
      document.getElementById('orderId').value= orderNumber;
      document.getElementById('form1').submit();
    }
    </script>
  </body>
  </html>
