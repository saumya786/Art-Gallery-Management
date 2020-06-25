<!DOCTYPE html>
<html lang='en' dir='ltr'>
  <head>
    <meta charset='utf-8'>
    <title></title>
    <link rel="stylesheet" href="dashboardStyleSheet.css">
    <link rel='stylesheet' href='permissionGrantPage.css'>
  </head>
  <body>
    <body>
      <?php
        include 'config.php';
        session_start();
        $flag= false;
        $cname= "";
        if(isset($_SESSION['uType']) && isset($_SESSION['cname']) && ($_SESSION['uType']=="admin")){
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
    </div>
    <main>
      <div class="returnToDashboard">
        <a href="/dataBaste/dashBoard.php"><strong>&larr;</strong> Go Back to Dashboard</a>
      </div>
    <div class='permissions'>
      <h1>Customer's Permission</h1>
      <form method='post' action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
          <?php
            if($flag==true){
              $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
              if(!$conn){
                die("Connection Error");
              }
              if($_SERVER["REQUEST_METHOD"]=="POST"){
                if(isset($_POST['submit1'])||isset($_POST['submit2'])){
                    $arr1= array();
                    if(isset($_POST['viewArt'])){
                      $arr1['viewArtWorks']= true;
                    }
                    else {
                      $arr1['viewArtWorks']= false;
                    }
                    if(isset($_POST['viewOrder'])){
                      $arr1['viewOrders']= true;
                    }
                    else{
                      $arr1['viewOrders']= false;
                    }
                  }
                  $data= serialize($arr1);
                  $data= $conn->real_escape_string($data);
                  $quer2= "";
                  if(isset($_POST['submit1'])){
                      $query2= "UPDATE PERMISSIONS SET ACCESS='$data',LASTCHANGE=NOW() WHERE ROLE='CUSTOMER'";
                  }
                  else{
                    $query2= "UPDATE PERMISSIONS SET ACCESS='$data',LASTCHANGE=NOW() WHERE ROLE='AGENT'";
                  }
                  if(!$conn->query($query2)){
                    echo "<h2>Updation Unsuccessful</h2>";
                  }
                }
              $query= "SELECT * FROM PERMISSIONS WHERE ROLE='CUSTOMER'";
              $result= $conn->query($query);
              if($result->num_rows>0){
                $row= $result->fetch_assoc();
                echo "<h2> Last permissions Altered On".$row['LASTCHANGE']."</h2>\n";
                echo "<ul>";
                $array= unserialize($row['ACCESS']);
                if($array["viewArtWorks"]==true){
                  echo "<li><span class='point'>View Artworks</span><input type='checkbox' name='viewArt' value='viewartwork' checked/></li>\n";
                }
                else{
                  echo "<li><span class='point'>View Artworks</span><input type='checkbox' name='viewArt' value='viewartwork'/></li>\n";
                }
                if($array["viewOrders"]==true){
                  echo "<li><span class='point'>View Orders</span><input type='checkbox' name='viewOrder' value='viewOrders' checked/></li>\n";
                }
                else{
                  echo "<li><span class='point'>View Orders</span><input type='checkbox' name='viewOrder' value='viewOrders'/></li>\n";
                }
                echo "</ul>";
              }
              mysqli_free_result($result);
            }
           ?>
        </ul>
        <input type='submit' name='submit1' value='Save Changes'>
      </form>
    </div>
    <div class='permissions'>
      <h1>Agent's Permission</h1>
      <form method='post'>
        <?php
          if($flag==true){
            $query2= "SELECT * FROM PERMISSIONS WHERE ROLE='AGENT'";
            $result2= $conn->query($query2);
            if($result2->num_rows>0){
              $row2= $result2->fetch_assoc();
              echo "<h2> Last permissions Altered On".$row2['LASTCHANGE']."</h2>\n";
              echo "<ul>";
              $array2= unserialize($row2['ACCESS']);
              if($array2["viewArtWorks"]==true){
                echo "<li><span class='point'>View Artworks</span><input type='checkbox' name='viewArt' value='viewartwork' checked/></li>\n";
              }
              else{
                echo "<li><span class='point'>View Artworks</span><input type='checkbox' name='viewArt' value='viewartwork'/></li>\n";
              }
              if($array2["viewOrders"]==true){
                echo "<li><span class='point'>View Orders</span><input type='checkbox' name='viewOrder' value='viewOrders' checked/></li>\n";
              }
              else{
                echo "<li><span class='point'>View Orders</span><input type='checkbox' name='viewOrder' value='viewOrders'/></li>\n";
              }
              echo "</ul>";
            }
            mysqli_free_result($result2);
            mysqli_close($conn);
          }
         ?>
        <input type='submit' name='submit2' value='Save Changes'>
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
    echo "document.getElementById('dashBoardForAdmin').style.display='none';\n";
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
