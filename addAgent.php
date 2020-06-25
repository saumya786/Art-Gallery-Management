<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Databast&egrave; - Add Artist</title>
    <link rel="stylesheet" href="addArtist.css">
    <link rel="stylesheet" href="addAgentExtra.css">
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
      $agname="";
      $flag= true;
      $cname= $_SESSION['cname'];
      $errmessage="";
      $lastid= 0;
      if($_SERVER["REQUEST_METHOD"]=="POST")
      {
        if(isset($_POST['agname']) && isset($_POST['agemailid']) && isset($_POST['agphone']) && isset($_POST['agaddress']) && isset($_POST['gender'])){
          $agname= test_input($_POST['agname']);
          $agname= strtolower($agname);/*It converts the name to lowercase*/
          $agname= ucwords($agname);/*It Capitalizes First Charecter of every Word*/
          $agemailid= test_input($_POST['agemailid']);
          $agphone= test_input($_POST['agphone']);
          $agaddress= test_input($_POST['agaddress']);
          $aggender= test_input($_POST['gender']);
          if(filter_var($agemailid,FILTER_VALIDATE_EMAIL)){
            $query= "INSERT INTO AGENT(AGNAME,AGEMAILID,AGPHONE,AGADDRESS,AGGENDER) VALUES('$agname','$agemailid',$agphone,'$agaddress','$aggender');";
            $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
            if(!$conn){
              die("Connection Failed ".$conn->connect_error);
            }
            if($conn->query($query)){
              $flag2= 1;
              $lastid= $conn->insert_id;
            }
            else{
              $flag2= 2;
              $errmessage="Record cannot be added";
            }
            mysqli_close($conn);
          }
          else{
            $flag2= 2;
            $errmessage= "Invalid email ID";
          }
        }
      }
    }
     ?>
     <header>
       <span class="logo">DataBast&egrave;</span>
       <div class="ErrorMessage" id="ErrorMessage" style="display:none;">
         <strong>Error!</strong> <?php echo $errmessage."\n" ?>
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
      <h2>Add Agent</h2>
      <div class="SuccessMessage2" id="SuccessMessage" style="display: none">
        <h1>Agent Record has been added Successfully</h1>
        <?php
          if($flag2==1){
            echo "<p>Agent $agname has been added to records. And the person is having id</p><br/>\n";
            echo "<h2>$lastid</h2>\n";
          }
         ?>
        <p>The default password of the account is user123</p><br>
          <button type="button" name="button" onclick="showForm()">Add another agent record</button>
      </div>
        <div class="formContents" id="formContents">
          <h3>Please fill in the following details to add record of an artist</h3>
          <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <label for="agname">Enter Agent's Name</label><input type="text" name="agname" value="" id="agname" placeholder="Enter the name of the agent" required/><br/>
            <label for="agemailid">Enter Agent's Email Id</label>
            <input type="email" name="agemailid" value="" id="agemailid" placeholder="Enter the mail-id of agent" required/><br/>
            <label for="agphone">Enter agent's phone number (10 Digits)</label>
            <input type="number" name="agphone" value="" id="agphone" placeholder="Enter the phone number of the agent" min="1000000000" max="9999999999"/>
            <label for="agaddress">Enter Agent Address</label>
            <input type="text" name="agaddress" value=""/>
            <label for="gender">Enter Gender</label>
            <input type="radio" name="gender" value="MALE" checked/>Male
            <input type="radio" name="gender" value="FEMALE"/>Female <br/>
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
        echo "document.getElementById('formContents').style.display='none';\n";
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
      function showForm(){
        document.getElementById('formContents').style.display= '';
        document.getElementById('SuccessMessage').style.display='none';
      }
    </script>
  </body>
</html>
