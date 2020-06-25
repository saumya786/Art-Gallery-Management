<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="inStockDetails.css">
    <title>Databaste - In Stock Items</title>
  </head>
  <body>
    <?php
      include 'config.php';
      session_start();
      $flag= false;
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
    <main>
      <div class="contents" id="contents">
        <div class="returnToDashboard">
          <a href="/dataBaste/dashBoard.php"><strong>&larr;</strong> Go Back to Dashboard</a>
        </div>
        <div class="printThisPage">
          <a href="#" onclick="window.print()">Click Here to get a print Out</a>
        </div>
        <h1>List of Artwork which have not been purchased</h1>
        <?php
          if($flag==true){
            $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
            $query1= "SELECT SUM(COST) TOTALCOST,GROUPNAME FROM (SELECT COST,GROUPNAME FROM ARTWORKBELONGS INNER JOIN (SELECT ARTWORKID,COST FROM ARTWORK WHERE ORDERID IS NULL) AS A USING (ARTWORKID)) AS B GROUP BY GROUPNAME ORDER BY TOTALCOST DESC";
            $result= $conn->query($query1);
            if($result->num_rows>0){
              while($row=$result->fetch_assoc()){
                echo "<div class='group'>
                  <div class='groupHead'>
                    <span class='artGroupName'>{$row['GROUPNAME']}</span>
                    <span class='TotalAmount'>&#8377; {$row['TOTALCOST']}</span>
                  </div>";
                  $query2= "SELECT TITLE,ANAME,COST FROM ARTWORK WHERE ORDERID IS NULL AND ARTWORKID IN (SELECT ARTWORKID FROM ARTWORKBELONGS WHERE GROUPNAME='{$row['GROUPNAME']}') ORDER BY ANAME";
                  $result2= $conn->query($query2);
                  if($result2->num_rows>0){
                    while($row2= $result2->fetch_assoc()){
                      echo "<div class='artWorkDetail'>
                        <div class='artworkAmount'>
                          &#8377; {$row2['COST']}
                        </div>
                        <div class='artworkTitle'>
                          {$row2['TITLE']}
                        </div>
                        <div class='artist'>
                          <span class='point'>Artist:- </span>{$row2['ANAME']}
                        </div>
                      </div>";
                    }
                  }
                  echo "</div>";
                  mysqli_free_result($result2);
              }
            }
            else{
              echo "<h2>Stock Empty</h2>";
            }
            mysqli_free_result($result);
            mysqli_close($conn);
          }
         ?>
       </div>
       <div class="ErrorMessage" id="ErrorMessage" style="display: none;">
         <img src="/dataBaste/warning.jpg" alt="" id="warningImg"/>
         <h1>You Don't have access to this page</h1>
         <h2>It seems you have wrongly accessed this page</h2>
         <h2>Move to login page to access your account</h2>
         <button type="button" name="button" onclick="window.location.href= 'customerFront.html'">Go to Login Page</button>
       </div>
     </main>
     <footer>
       <p>Created By Group 3</p>
     </footer>
     <script type="text/javascript">
     <?php
       if($flag==false){
         echo "document.getElementById('contents').style.display='none';\n";
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
