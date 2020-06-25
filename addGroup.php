<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Databast&egrave; - Add Group</title>
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
        if(isset($_POST['grpname']) && isset($_POST['grpDescription'])){
          $grpname= test_input($_POST['grpname']);
          $grpname= strtolower($grpname);/*It converts the name to lowercase*/
          $grpname= ucwords($grpname);/*It Capitalizes First Charecter of every Word*/
          $grpDescription= test_input($_POST['grpDescription']);
          $query= "INSERT INTO GROUPS VALUES('$grpname','$grpDescription')";
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
      <h2>Add Group</h2>
        <div class="formContents">
          <h3>Please fill in the following details to add record of a group</h3>
          <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <label for="grpname">Enter the name of the group</label><input type="text" name="grpname" value="" id="grpname" placeholder="Enter the name of the group"/><br/>
            <label for="grpDescription">Please say something about the group</label><br/>
            <textarea name="grpDescription" rows="6" cols="80" placeholder="Enter some description about the group" id="grpDescription"></textarea>
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
