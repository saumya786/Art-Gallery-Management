<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Databast&egrave; - Add Artist</title>
    <link rel="stylesheet" href="addArtist.css">
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
    $flag2= 0;
    $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
    if(!$conn){
      die("Connection Failed ".$conn->connect_error);
    }
    if(isset($_SESSION['uType']) && isset($_SESSION['cname']) && ($_SESSION['uType']=="admin")){
      $flag= true;
      $cname= $_SESSION['cname'];
      if($_SERVER["REQUEST_METHOD"]=="POST")
      {
        if(isset($_POST['aname']) && isset($_POST['birthplace']) && isset($_POST['age']) && isset($_POST['artstyle']) && isset($_POST['about'])){
          $aname= test_input($_POST['aname']);
          $aname= strtolower($aname);/*It converts the name to lowercase*/
          $aname= ucwords($aname);/*It Capitalizes First Charecter of every Word*/
          $birthplace= test_input($_POST['birthplace']);
          $artstyle= test_input($_POST['artstyle']);
          $about= test_input($_POST['about']);
          $age= test_input($_POST['age']);
          $query= "INSERT INTO ARTIST VALUES('$aname','$birthplace',$age,'$artstyle','$about')";
          if($conn->query($query)){
            $flag2= 1;
          }
          else{
            $flag2= 2;
          }
          mysqli_close($conn);
        }
      }
    }
     ?>
     <header>
       <span class="logo">DataBast&egrave;</span>
       <div class="ErrorMessage2" id="ErrorMessage" style="display:none;">
         <strong>Error!</strong> This Record Cannot be added
          <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
       </div>
       <div class="SuccessMessage" id="SuccessMessage" style="display:none;">
         <strong>Success! </strong>The record has been added Successfully
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
      <h2>Add Artist</h2>
        <div class="formContents" id= "formContentsForAdmin">
          <h3>Please select the details you want to change</h3>

    </div>
    </main>
    <footer>
      <p>Created by Group 3</p>
    </footer>
    <script type="text/javascript">
    <?php
      //echo $flag2;
      if($flag2==2){
        echo "document.getElementById('ErrorMessage').style.display='';\n";
      }
      else if($flag2==1){
        echo "document.getElementById('SuccessMessage').style.display='';\n";
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
