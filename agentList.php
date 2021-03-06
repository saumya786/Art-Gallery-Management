<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Databast&egrave; - Agent List</title>
    <link rel="stylesheet" href="myOrders.css">
  </head>
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
     <main>
       <div class="returnToDashboard">
         <a href="/dataBaste/dashBoard.php"><strong>&larr;</strong> Go Back to Dashboard</a>
       </div>
       <div class="printThisPage">
         <a href="#" onclick="window.print()">Click Here to get a print Out</a>
       </div>
       <div class="contents" id="contents">
         <?php
           if($flag==true){
             $query1="SELECT AGENT.AID,AGNAME,AGEMAILID,AGPHONE,AGADDRESS,AGGENDER,IFNULL(AMT,0) AMOUNT FROM AGENT LEFT JOIN (SELECT AID,SUM(ORDERAMOUNT) AMT FROM OFFLINEORDERS NATURAL JOIN ORDERS GROUP BY AID) AS A ON AGENT.AID=A.AID ORDER BY AMOUNT DESC";
             $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
             $result= $conn->query($query1);
             if($result->num_rows>0){
               echo "<div class='orderList'>";
               echo "<h1>List of Agents along the amount incomed for Gallery</h1>";
               while($row= $result->fetch_assoc()){
                 echo "<div class='orderDetails'>
                   <div class='orderAmount'>
                     &#8377; {$row['AMOUNT']}
                   </div>
                   <span class='point'>Agent ID</span> {$row['AID']} <br>
                   <span class='point'>Agent Name</span> {$row['AGNAME']} <br>
                   <span class='point'>Gender</span> {$row['AGGENDER']} <br>
                   <span class='point'>Address</span> {$row['AGADDRESS']} <br>
                   <span class='point'>Phone Number </span> {$row['AGPHONE']} <br>
                   <span class='point'>Email Id</span> {$row['AGEMAILID']} <br>
                 </div>";
               }
               echo "</div>";
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
         <button type="button" name="button" onclick="window.location.href= 'index.html'">Go to Login Page</button>
       </div>
     </main>
     <footer>
       <p>Created by Group 3</p>
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
