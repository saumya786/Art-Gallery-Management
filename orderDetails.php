<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <form class="" method="post" action="/dataBaste/orderDetails.php">
      Enter Starting date <input type="date" name="startDate" value="" required/> <br/>
      Enter Ending date <input type="date" name="endDate" value="" required/> <br/>
      <input type="submit" name="" value="submit">
    </form>
    <?php
      include 'config.php';
      //echo "Okay";
      if($_SERVER["REQUEST_METHOD"]=="POST"){
        if(isset($_POST["startDate"]) && isset($_POST["endDate"])){
          //echo "Okay2";
          $query1= "SELECT ORDERID,BIN(ORDERTYPE) ORDERTYPE,PAYMENTMODE,ORDERAMOUNT,ORDERDATEANDTIME,PAYMENTDATEANDTIME,CNAME FROM ORDERS WHERE ORDERDATEANDTIME>'{$_POST['startDate']}' AND ORDERDATEANDTIME<'{$_POST['endDate']}'";
          //echo $query1;
          $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
          if(!$conn){
            die("error");
          }
          $result= $conn->query($query1);
          if($result->num_rows>0){
            while ($row= $result->fetch_assoc()) {
                $query2= "SELECT TITLE,COST,ANAME FROM ARTWORK WHERE ORDERID=".$row['ORDERID']." ORDER BY ANAME";
                echo "$query2";
                echo "<br>order number:- ".$row['ORDERID'];
                if($row['ORDERTYPE']=='0'){
                  echo "<br>Order is online placed";
                }
                else{
                  echo "<br>Order is offline placed";
                }
                echo "<br>OrderAmount ".$row['ORDERAMOUNT'];
                echo "<br>ORDERDATEANDTIME".$row["ORDERDATEANDTIME"];
                echo "<br>CNAME".$row["CNAME"];
                $result2= $conn->query($query2);
                if($result2->num_rows>0){
                  echo "<br>This order contains artwork <br>";
                  while($row2= $result2->fetch_assoc()){
                    echo "<br>artwork:- ".$row2['TITLE']." COST:- ".$row2['COST']." aRTIST nAME:- ".$row2['ANAME'];
                  }
                }
                mysqli_free_result($result2);
            }
          }
          mysqli_free_result($result);
        }
      }
     ?>
  </body>
</html>
