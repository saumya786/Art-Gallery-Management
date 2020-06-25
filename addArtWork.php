<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Databast&egrave; - Add Artwork</title>
    <link rel="stylesheet" href="addArtist.css">
    <link rel="stylesheet" href="addArtworkExtra.css">
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
    $title= "";
    $lastId= 0;
    if(isset($_SESSION['uType']) && isset($_SESSION['cname']) && ($_SESSION['uType']=="admin")){
      $flag= true;
      $cname= $_SESSION['cname'];
      $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
      if(!$conn){
        die("Connection Failed ".$conn->connect_error);
      }
      if($_SERVER["REQUEST_METHOD"]=="POST")
      {
        if(isset($_POST['submit1'])){
          if(isset($_POST['aname']) && isset($_POST['title']) && isset($_POST['year']) && isset($_POST['cost']) && isset($_POST['about']) && isset($_POST['type'])){
            $aname= test_input($_POST['aname']);
            $about= test_input($_POST['about']);
            $title= test_input($_POST['title']);
            $year= test_input($_POST['year']);
            $cost= test_input($_POST['cost']);
            $type= test_input($_POST['type']);
            $query= "INSERT INTO ARTWORK(TITLE,COST,YEAR,TYPE,ANAME,ARTWORKDESC)  VALUES('$title',$cost,$year,'$type','$aname','$about');";
            if($conn->query($query)){
              $flag2= 1;
              $lastId= $conn->insert_id;
            }
            else{
              $flag2= 2;
              echo "<h1>Error:- ".$conn->error."</h1>";
            }
          }
        }
        else if(isset($_POST['submit2'])){
          if(isset($_POST['group']) && isset($_POST['artworkid'])){
            $artworkid= test_input($_POST['artworkid']);
            $query3= "INSERT INTO ARTWORKBELONGS VALUES";
            foreach ($_POST['group'] as $group) {
              $query3.= " (".$artworkid.",'".$group."'),";
            }
            $query3= substr($query3,0,-1);
            /*echo $query3;*/
            if($conn->query($query3)){
                $flag2= 4;
            }
            else {
              $flag2= 2;
            }
          }
        }
      }
    }
     ?>
     <header>
       <span class="logo">DataBast&egrave;</span>
       <div class="ErrorMessage2" id="ErrorMessage2" style="display:none;">
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
      <h2>Add Artwork</h2>
      <div class="ErrorMessage" id="ErrorMessage" style="display: none;">
        <h1>No artist record</h1>
        <p>Please add some artist records to continue</p><br>
        <button type="button" name="button" onclick="window.location.href= '/dataBaste/addArtist.php'">Add artist record</button>
      </div>
        <div class="formContents" id="formContents">
          <h3>Please fill in the following details to add a artwork</h3>
          <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <label for="title">Enter title of the artwork</label>
            <input type="text" name="title" value="" id="title" required/><br/>
            <label for="year">Enter the year when the artwork is created</label>
            <input type="number" name="year" value="" max="<?php echo date("Y"); ?>">
            <label for="cost">Enter the cost of the image</label>
            <input type="number" name="cost" value="" id="cost"/><br/>
            <label for="type">Enter the type of artwork</label>
            <input type="text" name="type" value="" id="type" required/><br/>
            <label for="aname">Select Artist Name</label>
            <select class="" name="aname" id="aname">
              <?php
                if($flag2!=1){
                $query2= "SELECT ANAME FROM ARTIST";
                $result= $conn->query($query2);
                if($result->num_rows>0){
                  while($row = $result->fetch_assoc()){
                    echo '<option value="'.$row["ANAME"].'">'.$row["ANAME"].'</option>';
                  }
                  mysqli_free_result($result);
                }
                else{
                  $flag2= 3;
                }
              }
               ?>
            </select><br/><br/>
            <label for="about">Enter Some Details about the artwork</label>
            <textarea name="about" rows="6" cols="80" placeholder="Enter some information about the artwork" id="about" required></textarea><br/>
            <input type="submit" name="submit1" value="Submit Details"/>
            <input type="reset" name="" value="Clear Details">
          </form>
    </div>
    <div class="formContents2" id="formContents2" style="display: none;">
      </form>
      <?php
        if($flag2==1){
          echo "<h2>Please select artgroup of the artwork $title</h2>";
          echo '<form class="" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">';
          echo "\n";
          echo "<input type='hidden' name='artworkid' value='$lastId'/>";
          $query3= "SELECT * FROM GROUPS";
          $result2= $conn->query($query3);
          if($result2->num_rows>0){
            while ($row = $result2->fetch_assoc()) {
              echo "<input type='checkbox' name='group[]' value='{$row['GROUPNAME']}'/>{$row['GROUPNAME']}<br/>\n";
              echo "<p>{$row['GROUPDESC']}</p>";
            }
          }
          echo '<input type="submit" name="submit2" value="Submit Group Preference"/>';
          echo "\n";
          echo "</form>\n";
          mysqli_free_result($result2);
        }
        mysqli_close($conn);
       ?>
    </div>
    </main>
    <footer>
      <p>Created by Group 3</p>
    </footer>
    <script type="text/javascript">
    <?php
      //echo $flag2;
      if($flag2==2){
        echo "document.getElementById('ErrorMessage2').style.display='';\n";
      }
      else if($flag2==1){
        echo "document.getElementById('formContents').style.display='none';\n";
        echo "document.getElementById('formContents2').style.display='';\n";
      }
      else if($flag2==3){
        echo "document.getElementById('formContents').style.display='none';\n";
        echo "document.getElementById('ErrorMessage').style.display='';\n";
      }
      else if($flag2==4){
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
