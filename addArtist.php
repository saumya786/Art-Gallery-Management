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
          $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
          if(!$conn){
            die("Connection Failed ".$conn->connect_error);
          }
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
        <div class="formContents">
          <h3>Please fill in the following details to add record of an artist</h3>
          <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <label for="aname">Artist Name </label><input type="text" name="aname" value="" id="aname" placeholder="Enter the name of the artist" required/><br/>
            <label for="birthplace">Enter Brithplace </label><input type="text" name="birthplace" value="" placeholder="Enter the brithplace of the artist" id="birthplace" required/><br/>
            <label for="age">Enter Age of the artist </label><input type="number" name="age" value="" placeholder="Enter the age of the artist" id="age" required/><br/>
            <label for="artstyle">Enter artstyle </label><input type="text" name="artstyle" value="" placeholder="Enter artstyle i.e. photography,sculpture, painting or etc." id="artstyle" required/><br/>
            <label for="about">Enter some information about Artist </label>
            <textarea name="about" rows="6" cols="80" placeholder="Enter some information about the artist" id="about" required></textarea><br/>
            <input type="submit" name="" value="Submit Details"/>
            <input type="reset" name="" value="Clear Details">
          </form>
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
