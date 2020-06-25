<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="orderSuccess.css">
    <title>Databaste - Cart Page</title>
  </head>
<body>
  <?php
    include 'config.php';
    $flag= false;
    session_start();
    if(isset($_SESSION['uType']) && isset($_SESSION['cname']) && (($_SESSION['uType']=="customer") ||($_SESSION['uType']=="agent")) && isset($_SESSION['orderId'])){
      $flag= true;
      $cname= $_SESSION['cname'];
    }
   ?>
  <header>
    <span class="logo">DataBast&egrave;</span>
    <span class="userOptions">
      <?php
        if($flag==true){
          echo "<a href='#' onclick='myFunction()'>Hello, {$_SESSION['cname']} &darr;</a>";
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
          <div class="message">
            <h2>Order Details</h2>
            <?php
              if($flag==true){
                $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
                $query1= "SELECT * FROM CUSTOMER NATURAL JOIN (SELECT ORDERS.ORDERID,ORDERAMOUNT,ORDERTYPE,PAYMENTMODE,ORDERDATEANDTIME,PAYMENTDATEANDTIME,CNAME,CARDID,CARDVENDOR,CARDTYPE FROM ORDERS LEFT JOIN ORDERSPAIDONCARD ON ORDERS.ORDERID = ORDERSPAIDONCARD.ORDERID WHERE ORDERS.ORDERID= {$_SESSION['orderId']}) AS A";
                /*echo "$query1";*/
                $result= $conn->query($query1);
                if($result->num_rows>0){
                  $row= $result->fetch_assoc();
                  echo "<div class='orderBasicDetails'>
                    <div class='CustomerDetails'>
                      <span class='point'>Customer Details</span><br/>
                      {$row['CNAME']} <br>
                      {$row['ADDRESS']} <br>
                      {$row['CEMAILID']} <br>
                      {$row['CPHONE']} <br>
                    </div>
                    <div class='OrderDetails'>
                      <span class='point'>Order Number</span> {$row['ORDERID']} <br/>
                      <span class='point'>Order Type</span> {$row['ORDERTYPE']} <br/>
                      <span class='point'>Order Date And Time</span> {$row['ORDERDATEANDTIME']} <br/>
                      <span class='point'>Payment Mode</span> {$row['PAYMENTMODE']} <br/>
                      <span class='point'>Payment Date and time</span> {$row['PAYMENTDATEANDTIME']} <br/>
                    </div>
                  </div><br/>";
                  $query2= "SELECT TITLE,COST,YEAR,ANAME FROM ARTWORK WHERE ORDERID={$row['ORDERID']}";
                  $result2= $conn->query($query2);
                  if($result2->num_rows>0){
                    echo "<div class='orderContents'>
                    <table border='1'>
                  		<col width='80%'>
                  		<col width='20%'>
                  		<caption>Details Of Items in the Order</caption>
                  		<thead>
                  			<tr>
                  				<th>Artwork Details</th>
                  				<th>Price</th>
                  			</tr>
                  		</thead>
                  		<tbody>";
                      while($row2= $result2->fetch_assoc()){
                        echo "<tr>
                  				<td>
              	    				<div class='title'>{$row2['TITLE']}</div>
              	    				<div class='otherDetails'>Year- {$row2['YEAR']} Artist - {$row2['ANAME']}</div>
              	    			</td>
              	    			<td>
              	    				<span class='cost'>&#8377; {$row2['COST']}</span>
              	    			</td>
                  			</tr>";
                      }
                      mysqli_free_result($result2);
                      echo "</tbody>
                  		<tfoot>
                  			<tr>
                  				<td>
                  					Total
                  				</td>
                  				<td>
                  					&#8377; {$row['ORDERAMOUNT']}
                  				</td>
                  			</tr>
                  		</tfoot>
                  	</table>
                  </div>
                  <br/>";
                  }
                  if(($row['ORDERTYPE']=='OFFLINE')||($row['PAYMENTMODE']=='CARD')){
                    echo "<h2>Some other Details</h2>
                    <div class='secondaryDetails'>";
                    if($row['ORDERTYPE']=='OFFLINE'){
                      $query3= "SELECT AGNAME,AID FROM AGENT WHERE AID IN (SELECT AID FROM OFFLINEORDERS WHERE ORDERID={$_SESSION['orderId']})";
                      $result3= $conn->query($query3);
                      if($result3->num_rows>0){
                        $row3= $result3->fetch_assoc();
                        echo "<div class='agentDetails'>
                          <span class='point'>Agent Id:- </span>{$row3['AID']}<br/>
                      		<span class='point'>Agent Name:- </span>{$row3['AGNAME']}<br/>
                      	</div>";
                      }
                    }
                    if ($row['PAYMENTMODE']=='CARD') {
                      echo "<div class='cardDetails'>
                    		<span class='point'>Card Id:-</span> {$row['CARDID']}<br/>
                    		<span class='point'>Card Type:-</span> {$row['CARDTYPE']}<br/>
                    		<span class='point'>Card Vendor:- </span> {$row['CARDVENDOR']}
                    	</div>";
                    }
                    echo "</div>";
                  }
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
            <button type="button" name="button" onclick="window.location.href= 'customerFront.php'">Go to Login Page</button>
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
