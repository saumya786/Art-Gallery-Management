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
      if(isset($_SESSION['uType']) && isset($_SESSION['cname']) && (($_SESSION['uType']=="agent") || ($_SESSION['uType']=="customer"))){
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
                        'artWorkId'=>$_POST['artWorkId'],
                        'title' =>$_POST['title'],
                        'cost' => $_POST['cost'],
                   );
                   $_SESSION["shopping_cart"][$count] = $item_array;
              }
            }
            else{
              $item_array= array(
                'artWorkId'=>$_POST['artWorkId'],
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
         <?php
         if(!empty($_SESSION['shopping_cart']) && ($flag==true))
         {
           $total= 0;
           echo "<div class='popUpPart'>
             <button type='button' name='button' class='cartButton' onclick='viewCart()'>View Cart</button>
             <div class='popUpContents' id='dropDown' style='display: none;'>
            <h2>Cart Contents</h2>
            <div class='popUpContentsProducts'>";
           foreach ($_SESSION['shopping_cart'] as $key => $value) {
             echo "<div class='CartContent'>
                 <div class='ProductName'>{$value['title']}</div>
                 <div class='ProductPrice'>&#8377; {$value['cost']}</div>
               </div>";
             $total= $total+$value['cost'];
           }
           echo "</div>
           <div class='checkOutButton'>
             <div class='totalAmount'>
               <strong>Total </strong>&#8377; $total
             </div>
             <button type='button' name='button' onclick='function3()'>Move to check out</button>
           </div>
         </div>
       </div>";
         }
      ?>
       <div class="orderItems" id= "orderItems">
       <?php
          if($flag==true){
            $addressToBePost= htmlspecialchars($_SERVER["PHP_SELF"]);
            $query= "SELECT ARTWORKID,TITLE,COST,YEAR,TYPE,ANAME FROM ARTWORK WHERE ORDERID IS NULL";
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
                      <input type='hidden' name='artWorkId' value='{$row['ARTWORKID']}'/>
                      <input type='hidden' name='title' value='{$row['TITLE']}'>
                      <input type='hidden' name='cost' value='{$row['COST']}'>
                      <input type='submit' name='add' value='Add To Cart'>";
                      if (isset($_SESSION['shopping_cart'])) {
                        echo "<input type='submit' name='remove' value='remove from cart'>";
                      }
                    echo "</form>
                  </div>
                </div>";
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
             <button type="button" name="button" onclick="window.location.href= 'customerFront.php'">Go to Login Page</button>
           </div>
     </main>
     <footer>
       <p>Created By Group 3</p>
     </footer>
     <script type="text/javascript">
     document.getElementById('dropDownContent').style.display='none';
     function myFunction(){
       var elem= document.getElementById('dropDownContent');
       if(elem.style.display=='')
         elem.style.display= 'none';
       else {
         elem.style.display='';
       }
     }
       <?php
         if($flag==false){
           echo "document.getElementById('contents').style.display='none';\n";
           echo "document.getElementById('ErrorMessage').style.display='';\n";
         }
        ?>
       function viewCart(){
         var dropDown= document.getElementById('dropDown');
         if(dropDown.style.display==''){
           dropDown.style.display='none';
         }
         else{
           dropDown.style.display='';
         }
       }
       function function3(){
         window.location.href= "<?php
          if($_SESSION['uType']=='customer'){
            echo "/dataBaste/checkOutPage.php";
          }
          else if($_SESSION['uType']=='agent'){
            echo "/dataBaste/customerValidation.php";
          }
         ?>";
       }
     </script>
  </body>
</html>
