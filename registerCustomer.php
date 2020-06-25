<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Databast&egrave; - Add Artist</title>
    <link rel="stylesheet" href="addArtist.css">
    <link rel="stylesheet" href="registerPageExtra.css">
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
    $flag= false;
    $flag2= 0;
    $errmessage= "";
    $cname= "";
    $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
    if(!$conn){
      die("Connection Failed ".$conn->connect_error);
    }
      if($_SERVER["REQUEST_METHOD"]=="POST")
      {
        if(isset($_POST['submit1'])){
            if(isset($_POST['cname']) && isset($_POST['address']) && isset($_POST['cphone']) && isset($_POST['cemailid']) && isset($_POST['password'])){
              $cname= test_input($_POST['cname']);
              $cname= strtolower($cname);/*It converts the name to lowercase*/
              $cname= ucwords($cname);/*It Capitalizes First Charecter of every Word*/
              $address= test_input($_POST['address']);
              $cphone= test_input($_POST['cphone']);
              $cemailid= test_input($_POST['cemailid']);
              $password= test_input($_POST['password']);
              if(filter_var($cemailid,FILTER_VALIDATE_EMAIL)){
                $query= "INSERT INTO CUSTOMER VALUES('$cname','$address',$cphone,'$cemailid','$password');";
                if($conn->query($query)){
                  $flag2= 1;
                }
                else{
                  $flag2= 2;
                  $errmessage= "record cannot be inserted";
                }
              }
            else {
              $flag2= 2;
              $errmessage= "Invalid Email Id";
            }
          }
      }
      if(isset($_POST['submit2'])){
        if(isset($_POST['cname'])&&isset($_POST['group'])&&isset($_POST['artist'])){
          $query4= "INSERT INTO CUSTFACINATES VALUES";
          $query5= "INSERT INTO CUSTLIKES VALUES";
          $cname= test_input($_POST['cname']);
          foreach($_POST['group'] as $group){
            $query4.= " ('".$cname."','".$group."'),";
          }
          foreach ($_POST['artist'] as $artist) {
            $query5.= " ('".$cname."','".$artist."'),";
          }
          $query4= substr($query4,0,-1);
          $query5= substr($query5,0,-1);
          if($conn->query($query4)){
              if($conn->query($query5)){
                mysqli_close($conn);
                header('location: /dataBaste/loginForCusotmer.php');
              }
              else{
                $flag2= 2;
                $errmessage= "artist preference cannot be stored";
              }
          }
          else{
            $flag2= 2;
            $errmessage= "group preference cannot be stored";
          }
        }
      }
    }
     ?>
     <header>
       <span class="logo">DataBast&egrave;</span>
       <div class="ErrorMessage" id="ErrorMessage" style="display:none;">
         <strong>Error! </strong><?php if($flag2==2){echo $errmessage;} ?>
          <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
       </div>
       <div class="SuccessMessage" id="SuccessMessage" style="display:none;">
         <strong>Success! </strong>The record has been added Successfully
          <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
       </div>
     </header>
    <main>
      <div class="returnToDashboard">
        <a href="/dataBaste/index.html"><strong>&larr;</strong> Go Back to home page</a>
      </div>
      <h2>Registration Page</h2>
        <div class="formContents" id="formContents1">
          <h3>Please fill in the following details</h3>
          <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
            <label for="cname">Enter Your Name</label>
            <input type="text" name="cname" value="" placeholder="Enter Your Name" maxlength="50"/>
            <label for="address">Adress</label>
            <input type="text" name="address" value="" placeholder="Enter Your Address" id="address" maxlength="100"/>
            <label for="cphone">Enter Your Contact Number</label>
            <input type="number" name="cphone" value="" id="cphone" placeholder="Enter the phone number of the agent" min="1000000000" max="9999999999"/>
            <label for="cemailid">Enter Your Mail Id</label>
            <input type="email" name="cemailid" value="" id="cemailid" placeholder="Please enter your email id" maxlength="90" required/>
            <label for="password">ENter your password</label>
            <input type="password" name="password" id="password" value="" placeholder="Enter your password" maxlength="16"/><br/>
            <input type="submit" name="submit1" value="Submit Details"/>
            <input type="reset" name="" value="Clear Details">
          </form>
    </div>
    <div class="formContents2" id=formContents2 style="display:none;">
      <form class="" action="" method="post">
        <div class="splitContents">
          <div class="split left">
            <h1>Select the artists who you like</h1>
            <ol class="list">
              <?php
                if($flag2==1){
                  $query2= "SELECT ANAME,ARTSTYLE,ABOUT FROM ARTIST";
                  $result2= $conn->query($query2);
                  if($result2->num_rows>0){
                    while($row= $result2->fetch_assoc()){
                      echo "<li><input type='checkbox' name='artist[]' value='{$row['ANAME']}'><h4>{$row['ANAME']}</h4>\n";
                      echo "<h4>ArtStyle:- {$row['ARTSTYLE']}</h4>\n";
                      echo "<p>{$row['ABOUT']}</p></li>\n";
                    }
                  }
                  mysqli_free_result($result2);
                }
               ?>
            </ol>
          </div>
          <div class="split right">
            <h1>Select the art group which facinates you</h1>
            <ol class="list">
              <?php
                if($flag2==1){
                  $query3= "SELECT * FROM GROUPS;";
                  $result3= $conn->query($query3);
                  if($result3->num_rows>0){
                    while($row= $result3->fetch_assoc()){
                      echo "<li><input type='checkbox' name='group[]' value='{$row['GROUPNAME']}'><h4>{$row['GROUPNAME']}</h4><br/>\n";
                      echo "<p>{$row['GROUPDESC']}</p></li>\n";
                    }
                  }
                  mysqli_free_result($result3);
                }
                mysqli_close($conn);
               ?>
            </ol>
          </div>
        </div>
        <div class="buttons">
          <?php
            if($flag2==1){
              echo "<input type='hidden' name='cname' value='$cname'/>\n";
            }
          ?>
          <input type="submit" name="submit2" value="Submit"/>
          <input type="reset" name="reset2" value="Reset"/>
        </div>
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
        echo "document.getElementById('formContents1').style.display='none';\n";
        echo "document.getElementById('formContents2').style.display='';\n";
      }
      else if($flag2==3){
        echo "document.getElementById('SuccessMessage').style.display='';\n";
      }
    ?>
    </script>
  </body>
</html>
