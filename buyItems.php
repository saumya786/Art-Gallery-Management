<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <?php
    include 'config.php';
    $total= 0;
    session_start();
    $flag= false;
    if(isset($_SESSION['uType']) && isset($_SESSION['cname']) && ($_SESSION['uType']=="customer") && isset($_SESSION["shopping_cart"]))){
      $flag= true;
      $cname= $_SESSION['cname'];
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
            <div class="myOrders">
              <h1>The items you have ordered:-</h1>
              <?php
                if(($flag==true)&&isset($_SESSION['shopping_cart']){
                  if(!empty($_SESSION['shopping_cart']))
                  {
                    $total= 0;
                    echo "<table border='1'><tr><th>Title</th><th>Price</th></tr>";
                    foreach ($_SESSION['shopping_cart'] as $key => $value) {
                      echo "<tr>";
                      echo "<td>".$value['title']."</td>";
                      echo "<td>".$value['cost']."</td>";
                      echo "</tr>";
                      $total= $total+$value['cost'];
                    }
                    echo "<tr><td>Total</td><td> Rs ".$total." </td></tr></table>";
                    echo "<br>";
                }
              ?>
              <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">

              </form>
            </div>
          </div>
        </main>
  </body>
</html>
