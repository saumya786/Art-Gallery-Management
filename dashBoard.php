<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Databast&egrave; - Dashboard</title>
    <link rel="stylesheet" href="dashboardStyleSheet.css">
  </head>
  <body>
    <?php
      include 'config.php';
      session_start();
      $flag= false;
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
      <div class="dashBoardForAdmin" id="dashBoardForAdmin">
        <h2>So, what do you want to do today ?</h2>
        <div class="menuForAdmin">
          <a href="/dataBaste/addArtWork.php"><div class="card">
            <h3>Add Artworks</h3>
            <p>Do you want a new artwork for the gallery ?</p>
          </div></a>
          <a href="#"><div class="card">
            <h3>Remove or Modify Artworks</h3>
            <p>Do you want to remove an artwork or Modify details of it ?</p>
          </div></a>
          <a href="addGroup.php"><div class="card">
            <h3>Add Art Group</h3>
            <p>Do you want to add a new art group?</p>
          </div></a>
          <a href="#"><div class="card">
            <h3>Remove or Modify Art Group</h3>
            <p>Do you want to remove an artgroup or modify details of it ?</p>
          </div></a>
          <a href="/dataBaste/addAgent.php"><div class="card">
            <h3>Add Agent</h3>
            <p>Do you want to add an agent account?</p>
          </div></a>
          <a href="#"><div class="card">
            <h3>Remove Agent</h3>
            <p>Do you want to remove an agent account?</p>
          </div></a>
          <a href="/dataBaste/addArtist.php"><div class="card">
            <h3>Add Artist</h3>
            <p>Do you want to add an artist to the database ?</p>
          </div></a>
          <a href="#"><div class="card">
            <h3>Remove or Modify Artist Details ?</h3>
            <p>Do you want to remove an artist account or add details to it?</p>
          </div></a>
          <a href="/dataBaste/customerList.php"><div class="card">
            <h3>Show Customer Details</h3>
            <p>Do you want to see the details of the customer along with the amount spent</p>
          </div></a>
          <a href="/dataBaste/agentList.php"><div class="card">
            <h3>Show Agent Details</h3>
            <p>Do you want to see the details of agent along with the amount they have incomed</p>
          </div></a>
          <a href="/dataBaste/orderListDate.php"><div class="card">
            <h3>Show Order Details</h3>
            <p>Do you want to see the detailed report of orders ?</p>
          </div></a>
          <a href="/dataBaste/showStockDetails.php"><div class="card">
            <h3>Show Current Stock Details</h3>
            <p>Do you want to see the current stock of artworks present in the gallery ?</p>
          </div></a>
          <a href="/dataBaste/genreWiseChart.php" target="_blank"><div class="card">
            <h3>Show Genrewise amount obtained</h3>
            <p>Graph showing the amount obtained Genre Wise of the artworks</p>
          </div></a>
          <a href="/dataBaste/permissionGrantPage.php"><div class="card">
            <h3>Change permissions?</h3>
            <p>Do you want to change permissions of agents and customers ?</p>
          </div></a>
        </div>
      </div>
      <div class="dashBoardForAdmin" id="dashBoardForCustomer" style="display: none;">
        <h2>So, what do you want to do today ?</h2>
        <div class="menuForAdmin">
        <?php
          if($flag==true){
            if($_SESSION['uType']=="customer"){
              $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
              if(!$conn){
                die("Error");
              }
              $query= "SELECT ACCESS FROM PERMISSIONS WHERE ROLE='CUSTOMER'";
              $result= $conn->query($query);
              if($result->num_rows>0){
                $row= $result->fetch_assoc();
                $array= unserialize($row['ACCESS']);
                if($array['viewArtWorks']==true){
                  echo "<a href='cartPage2.php'><div class='card'>
                    <h3>View Available Artworks in the gallery</h3>
                    <p>View the Available artwork present in the gallery</p>
                  </div></a>";
                }
                if($array['viewOrders']=='true'){
                  echo "<a href='/dataBaste/myOrders.php'><div class='card'>
                    <h3>My Orders</h3>
                    <p>View the orders that you have given in the gallery</p>
                  </div></a>";
                }
              }
              mysqli_free_result($result);
              mysqli_close($conn);
              }
            }
         ?>
        </div>
      </div>
      <div class="dashBoardForAdmin" id="dashBoardForAgent" style="display: none;">
        <h2>So, what do you want to do today ?</h2>
        <div class="menuForAdmin">
          <?php
            if($flag==true){
              if($_SESSION['uType']=="agent"){
                $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
                if(!$conn){
                  die("Error");
                }
                $query= "SELECT ACCESS FROM PERMISSIONS WHERE ROLE='AGENT'";
                $result= $conn->query($query);
                if($result->num_rows>0){
                  $row= $result->fetch_assoc();
                  $array= unserialize($row['ACCESS']);
                  if($array['viewArtWorks']==true){
                    echo "<a href='cartPage2.php'><div class='card'>
                      <h3>View Available Artworks in the gallery</h3>
                      <p>View the Available artwork present in the gallery</p>
                    </div></a>";
                  }
                  if($array['viewOrders']=='true'){
                    echo "<a href='/dataBaste/myOrders.php'><div class='card'>
                      <h3>My Orders</h3>
                      <p>View the orders that you have given in the gallery</p>
                    </div></a>";
                  }
                }
                mysqli_free_result($result);
                mysqli_close($conn);
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
        else if($_SESSION['uType']=="customer"){
          echo "document.getElementById('dashBoardForAdmin').style.display='none';\n";
          echo "document.getElementById('dashBoardForCustomer').style.display='';\n";
        }
        else if($_SESSION['uType']=="agent"){
          echo "document.getElementById('dashBoardForAdmin').style.display='none';\n";
          echo "document.getElementById('dashBoardForAgent').style.display='';\n";
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
