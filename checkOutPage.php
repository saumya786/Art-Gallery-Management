<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="dashboardStyleSheet.css">
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="orderPage.css">
    <title>Databaste - Checkout Page</title>
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
  $total= 0;
  $flag= false;
  if(isset($_SESSION['uType']) && isset($_SESSION['cname']) && (($_SESSION['uType']=="customer") || ($_SESSION['uType']=="agent")) && isset($_SESSION['shopping_cart'])){
    if($_SESSION['uType']=="agent"){
        if(isset($_SESSION['cname2'])){
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
      $query1= "";
      if(isset($_POST['paymentMethod']) && isset($_POST['totalAmt'])){
        if($_SESSION['uType']=='agent'){
          $query1= "INSERT INTO ORDERS(ORDERTYPE,PAYMENTMODE,ORDERAMOUNT,CNAME) VALUES('OFFLINE','{$_POST['paymentMethod']}',{$_POST['totalAmt']},'{$_SESSION['cname2']}')";
        }
        else {
          $query1= "INSERT INTO ORDERS(ORDERTYPE,PAYMENTMODE,ORDERAMOUNT,CNAME) VALUES('ONLINE','{$_POST['paymentMethod']}',{$_POST['totalAmt']},'{$_SESSION['cname']}')";
        }
        $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
        if($conn->query($query1)){
          $lastId= $conn->insert_id;
          $query2= "UPDATE ARTWORK SET ORDERID=$lastId WHERE ARTWORKID IN ";
          $list1= "";
          foreach ($_SESSION['shopping_cart'] as $key => $value){
            $list1.= $value['artWorkId'].",";
          }
          $list1= substr($list1,0,-1);
          $query2.="($list1)";
          $flag2= true;
          if($_SESSION['uType']=='agent'){
            $query4= "INSERT INTO OFFLINEORDERS VALUES($lastId,{$_SESSION['AID']})";
            if(!$conn->query($query4)){
              $flag2= false;
            }
            else{
              unset($_SESSION['cname2']);
            }
          }
          if(($flag2==true)&&($conn->query($query2))){
            unset($_SESSION['shopping_cart']);
            $_SESSION['orderId']= $lastId;
            if($_POST['paymentMethod']=='CARD'){
              if(isset($_POST['cardId']) && isset($_POST['cardVendor']) && isset($_POST['cardType'])){
                $cardId= test_input($_POST['cardId']);
                $cardVendor= test_input($_POST['cardVendor']);
                $cardType= test_input($_POST['cardType']);
                $query3= "INSERT INTO ORDERSPAIDONCARD VALUES($lastId,$cardId,'$cardVendor','$cardType')";
                if($conn->query($query3)){
                  header('location: /dataBaste/successPage.php');
                }
                else{
                  echo "<h1>Some error</h1>";
                  echo "<h1>".$conn->error."</h1>";
                  echo "<h1>".$query3."</h1>";
                }
              }
            }
            else{
              header('location: /dataBaste/successPage.php');
            }
          }
          else{
            echo "<h1>Some Problem 1</h1>";
          }
        }
        else{
          echo "<h1>Some Problem 2</h1>";
          echo "<h1>".$conn->error."</h1>";
        }
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
        <div class="contents" id="contents">
          <div class="returnToDashboard">
            <a href="/dataBaste/dashBoard.php"><strong>&larr;</strong> Go Back to Dashboard</a>
          </div>
          <div class="checkOutPage">
            <h1>Checkout Page</h1>
            <h3>Contents in Cart</h3>
            <table class="productTable" border="1">
              <tr>
                <th>Artwork Title</th>
                <th>Price</th>
              </tr>
              <?php
                if($flag==true){
                  $total= 0;
                  foreach ($_SESSION['shopping_cart'] as $key => $value) {
                    echo "<tr class='product'>
                      <td class='title'>{$value['title']}</td>
                      <td class='price'>&#8377; {$value['cost']}</td>
                    </tr>";
                    $total= $total+$value['cost'];
                  }
                  echo "<tr class='footer'>
                    <td>Total Amount</td>
                    <td>$total</td>
                  </tr>
                  </table>";
                }
              ?>
              <div class='paymentOptions'>
                <h2>Please select a payement Option</h2>
                <form class='' method='post'>
                  <?php
                    echo "<input type='hidden' name='totalAmt' value='$total'/>"
                   ?>
                  <input type='radio' name='paymentMethod' value='CASH' onclick="showPaymentDetails()" id="cashPay"/><label for='Cash' checked>Cash</label> <br/><br>
                  <input type='radio' name='paymentMethod' value='CARD' id="cardPay" onclick="showPaymentDetails()"/><label for='Card'>Card</label><br/>
                  <div class="cardDetails" id="cardDetails" style="display: none;">
                    <label for="cardId">Enter the card ID</label><input type="number" name="cardId" value="" id="cardId" required disabled/><br>
                    <label for="cardVendor">Enter card Vendor</label><input type="text" name="cardVendor" value="" id="cardVendor" placeholder="Enter Card Vendor example (Visa,Rupay,Master Card)" required disabled/><br>
                    <label for="cardType">Enter the type of Card</label><br>
                    <input type="radio" name="cardType" value="DEBIT" checked/>Debit Card <br>
                    <input type="radio" name="cardType" value="CREDIT"/>Credit Card <br>
                  </div>
                  <div class='buttons'>
                    <input type='submit' name='' value='Submit'/>
                  </div>
                </form>
              </div>
        </div>
      <div class="ErrorMessage" id="ErrorMessage" style="display: none;">
        <img src="/dataBaste/warning.jpg" alt="" id="warningImg"/>
        <h1>You Don't have access to this page</h1>
        <h2>It seems you have wrongly accessed this page</h2>
        <h2>Move to login page to access your account</h2>
        <button type="button" name="button" onclick="window.location.href= 'customerFront.php'">Go to Login Page</button>
      </div>
</main>
<footer>
  <p>Created By Group 3</p>
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
function showPaymentDetails(){
  if(document.getElementById('cashPay').checked){
    document.getElementById('cardDetails').style.display='none';
    document.getElementById('cardId').disabled= true;
    document.getElementById('cardVendor').disabled= true;
  }
  else if(document.getElementById('cardPay').checked){
    document.getElementById('cardDetails').style.display='';
    document.getElementById('cardId').disabled= false;
    document.getElementById('cardVendor').disabled= false;
  }
}
</script>
</body>
</html>
