<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Databast&egrave; - Dashboard</title>
    <link rel="stylesheet" href="customerValidation.css">
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
      $flag= false;
      $flag1= true;
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
        if($_SERVER["REQUEST_METHOD"]=="POST")
        {
          if(isset($_POST['Password']) && $_POST['cEmail'])
          {
            $password= test_input($_POST['Password']);
            $cEmail= test_input($_POST['cEmail']);
            $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
            if(!$conn){
              die("Connection Failed ".$conn->connect_error);
            }
            $query1= "SELECT CNAME,PASSWORD FROM CUSTOMER WHERE CEMAILID='$cEmail'";
            $result= $conn->query($query1);
            if($result->num_rows>0)
            {
              $row= $result->fetch_array();
              if($row['PASSWORD']==$password)
              {
                mysqli_close($conn);
                $_SESSION["cname2"]= $row['CNAME'];
                header("location:/dataBaste/checkOutPage.php");
              }
              else {
                $flag1= false;
              }
            }
            else {
              $flag1= false;
            }
          }
        }
      }
     ?>
    <header>
      <span class="logo">DataBast&egrave;</span>
      <div class="ErrorMessage2" id="ErrorMessage2" style="display:none;">
        <strong>Error!</strong> It seems you have entered an wrong Password
         <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
      </div>
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
      <h1>Customer Login Page</h1>
      <div class="FormContents" id="FormContents">
        <form class="" method="post">
          <label for="cEmail">Enter customer Email</label>
          <input type="email" name="cEmail" value="" id="cName" placeholder="Enter Customer Email" required/>
          <label for="Password">Enter Password </label>
          <input type="password" name="Password" value="" id="Password" placeholder="Please enter your Password" required/><br/><br/>
          <input type="submit" name="" value="Login">
        </form>
      </div>
      <div class="ErrorMessage" id="ErrorMessage" style="display: none;">
        <img src="/dataBaste/warning.jpg" alt="" id="warningImg"/>
        <h1>You Don't have access to this page</h1>
        <h2>It seems you have wrongly accessed this page</h2>
        <h2>Move to login page to access your account</h2>
        <button type="button" name="button">Go to Login Page</button>
      </div>
    </main>
    <footer>
      <p>Created by Group 3</p>
    </footer>
    <script type="text/javascript">
      <?php
      if($flag==false){
        echo "document.getElementById('FormContents').style.display='none';\n";
        echo "document.getElementById('ErrorMessage').style.display='';\n";
      }
      else if($flag1==false){
        echo "document.getElementById('ErrorMessage2').style.display='';";
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
