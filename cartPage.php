<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="product.css">
    <link rel="stylesheet" href="dashboardStyleSheet.css">
    <title>Databaste - Cart Page</title>
  </head>
  <body>
    <?php
      include 'config.php';
      session_start();
      $flag= false;
      if(isset($_SESSION['uType']) && isset($_SESSION['cname']) && ($_SESSION['uType']=="customer")){
        $flag= true;
        $cname= $_SESSION['cname'];
        $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
        if($_SERVER["REQUEST_METHOD"]=="POST"){
          if(isset($_POST['add'])){
            if(isset($_SESSION["shopping_cart"])){
              $item_array_id = array_column($_SESSION["shopping_cart"], "title");
              if(!in_array($_POST['title'], $item_array_id))
              {
                   $count = count($_SESSION["shopping_cart"]);
                   $item_array = array(
                        'title' =>$_POST['title'],
                        'cost' => $_POST['cost']
                   );
                   $_SESSION["shopping_cart"][$count] = $item_array;
              }
            }
            else{
              $item_array= array(
                'title' => $_POST['title'],
                'cost' =>$_POST['cost']
              );
              $_SESSION['shopping_cart'][0]= $item_array;
            }
          }
          elseif (isset($_POST['remove'])) {
            //$item_array_id2 = array_column($_SESSION["shopping_cart"], "title");
            //if(in_array($_POST['title'], $item_array_id2))
            //{
              foreach($_SESSION["shopping_cart"] as $keys => $values)
              {
                   if($values['title'] == $_POST['title'])
                   {
                        unset($_SESSION["shopping_cart"][$keys]);
                   }
              }
            //}
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
       <div class="orderItems" id= "orderItems">
       <?php
          if($flag==true){
            $addressToBePost= htmlspecialchars($_SERVER["PHP_SELF"]);
            $query= "SELECT TITLE,COST,YEAR,TYPE,ANAME FROM ARTWORK WHERE ORDERID IS NULL";
            $result= $conn->query($query);
            if($result->num_rows>0){
              while($row= $result->fetch_assoc()){
                echo "<div class='item'>
                  <span class= 'ProductName'>{$row['TITLE']}  </span>
                  <span class= 'ProductPrice'>Rs. {$row['COST']} </span>
                  <br/>
                  <span class='artistName'>
                    <span class='points'>Artist Name </span>{$row['ANAME']}
                  </span>
                  <div class='otherDetails'>
                    <span class='points'>Year </span> {$row{'YEAR'}} <span class='points'>Type <span class='points'> {$row{'TYPE'}}
                  </div>
                  <div class='buttons'>
                    <form class='' action='$addressToBePost' method='post'>
                      <input type='hidden' name='title' value='{$row['TITLE']}'>
                      <input type='hidden' name='cost' value='{$row['COST']}'>
                      <input type='submit' name='add' value='Add To Cart'>
                      <input type='submit' name='remove' value='remove from cart'>
                    </form>
                  </div>
                </div>";
              }
            }
            ?>
            </div>
            <div class="cart">
            <?php
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
              echo "<br>
              <a href='buyItems.php'>Move to Buy</a>";
            }
          }
           ?>
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
     </script>
  </body>
</html>
