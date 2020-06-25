<?php
function printOrders($startDate,$endDate,$conn,$type){
  if(isset($startDate) && isset($endDate)){
    $query1= "";
    if($type=="online"){
        $query1= "SELECT CNAME,ADDRESS,CPHONE,CEMAILID,ORDERID,ORDERTYPE,PAYMENTMODE,ORDERAMOUNT,ORDERDATEANDTIME,PAYMENTDATEANDTIME,CARDID,CARDVENDOR,CARDTYPE FROM CUSTOMER NATURAL JOIN (SELECT A.ORDERID,CNAME,ORDERTYPE,PAYMENTMODE,ORDERAMOUNT,ORDERDATEANDTIME,PAYMENTDATEANDTIME,CARDID,CARDVENDOR,CARDTYPE FROM (SELECT * FROM ORDERS WHERE ORDERDATEANDTIME>='$startDate 00:00:00' AND  ORDERDATEANDTIME<='$endDate 23:59:59' AND ORDERTYPE='ONLINE') AS A LEFT JOIN ORDERSPAIDONCARD ON ORDERSPAIDONCARD.ORDERID=A.ORDERID) AS B ORDER BY ORDERDATEANDTIME";
    }
    else if($type=="offline"){
      $query1="SELECT AID,AGNAME,CNAME,ADDRESS,CPHONE,CEMAILID,ORDERID,ORDERTYPE,PAYMENTMODE,ORDERAMOUNT,ORDERDATEANDTIME,PAYMENTDATEANDTIME,CARDID,CARDVENDOR,CARDTYPE FROM (SELECT ORDERID,AID,AGNAME FROM AGENT NATURAL JOIN OFFLINEORDERS) AS D NATURAL JOIN (SELECT * FROM CUSTOMER NATURAL JOIN (SELECT A.ORDERID,CNAME,ORDERTYPE,PAYMENTMODE,ORDERAMOUNT,ORDERDATEANDTIME,PAYMENTDATEANDTIME,CARDID,CARDVENDOR,CARDTYPE FROM (SELECT * FROM ORDERS WHERE ORDERDATEANDTIME>='$startDate 00:00:00' AND ORDERDATEANDTIME<='$endDate 23:59:59' AND ORDERTYPE='OFFLINE') AS A LEFT JOIN ORDERSPAIDONCARD ON ORDERSPAIDONCARD.ORDERID=A.ORDERID) AS B) AS C ORDER BY ORDERDATEANDTIME";
    }
    $result1= $conn->query($query1);
    if($result1->num_rows>0){
      if($type=="online"){
        echo "<h1>Online Orders</h1>";
      }
      else{
        echo "<div class='Offline'>
        <h1>Offline Orders</h1>\n";
      }
      while($row1= $result1->fetch_assoc()){
        echo "<h2>Order Number - {$row1['ORDERID']} </h2>
        <div class='orderContents'>
          <div class='orderBasicDetails'>
            <div class='CustomerDetails'>
              <span class='point'>Customer Details</span><br/>
              {$row1['CNAME']} <br>
              {$row1['ADDRESS']} <br>
              {$row1['CEMAILID']} <br>
              {$row1['CPHONE']} <br>
            </div>
            <div class='OrderDetails'>
              <span class='point'>Order Number</span> {$row1['ORDERID']} <br/>
              <span class='point'>Order Type</span> {$row1['ORDERTYPE']} <br/>
              <span class='point'>Order Date And Time</span> {$row1['ORDERDATEANDTIME']} <br/>
              <span class='point'>Payment Mode</span> {$row1['PAYMENTMODE']} <br/>
              <span class='point'>Payment Date and time</span> {$row1['PAYMENTDATEANDTIME']} <br/>
            </div>
          </div>";
          $query2= "SELECT ARTWORKID,TITLE,COST,YEAR,TYPE,ANAME FROM ARTWORK WHERE ORDERID= {$row1['ORDERID']} ORDER BY ANAME";
          $result2= $conn->query($query2);
          if($result2->num_rows>0){
            echo "<div class='orderContents2'>
              <table border='1'>
                <caption>Details Of Items in the Order</caption>
                <thead>
                  <tr>
                    <th>Artwork Details</th>
                    <th>Price</th>
                  </tr>
                </thead>
                <tbody>";
                while($row2= $result2->fetch_assoc()){
                  $query3="SELECT GROUPNAME FROM ARTWORKBELONGS WHERE ARTWORKID={$row2['ARTWORKID']}";
                  $genre="";
                  $result3= $conn->query($query3);
                  if($result3->num_rows>0){
                    while($row3=$result3->fetch_assoc()){
                      $genre.= $row3['GROUPNAME'].", ";
                    }
                    $genre= substr($genre,0,-2);
                  }
                  mysqli_free_result($result3);
                    echo "<tr>
                      <td>
                        <div class='title'>{$row2['TITLE']}</div>
                        <div class='otherDetails'>Year- {$row2['YEAR']} Artist - {$row2['ANAME']}</div>
                        <div class='otherDetails'>Genre - $genre</div>
                      </td>
                      <td>
                        <span class='cost'>&#8377; {$row2['COST']}</span>
                      </td>
                    </tr>";
                }
                echo "<tfoot>
                  <tr>
                    <td>
                      Total
                    </td>
                    <td>
                      &#8377; {$row1['ORDERAMOUNT']}
                    </td>
                  </tr>
                </tfoot>
              </table>
              </div>";
          }
          mysqli_free_result($result2);
          if(($type=='offline') || ($row1['PAYMENTMODE']=='CARD')){
            echo "<h3>Some other Details</h3>
            <div class='secondaryDetails'>";
            if($type=='offline'){
              echo "<div class='agentDetails'>
              		<span class='point'>Agent Id:- </span>{$row1['AID']}<br/>
            		<span class='point'>Agent Name:- </span>{$row1['AGNAME']}
            	</div>";
            }
            if($row1['PAYMENTMODE']=='CARD'){
              echo "<div class='cardDetails'>
                <span class='point'>Card Id:-</span> {$row1['CARDID']}<br/>
                <span class='point'>Card Type:-</span>{$row1['CARDTYPE']}<br/>
                <span class='point'>Card Vendor:- </span> {$row1['CARDVENDOR']}
              </div>";
            }
            echo "</div>";
          }
          echo "</div>";
      }
      if($type=='offline'){
        echo "</div>";
      }
    }
    mysqli_free_result($result1);
  }
}
?>
