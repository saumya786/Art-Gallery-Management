<?php
  echo "<h1>Prices of each Genre</h1>";
  include 'config.php';
  $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
  $query2= "SELECT SUM(COST) TOTALAMOUNT,GROUPNAME FROM (SELECT COST,GROUPNAME FROM ARTWORK INNER JOIN ARTWORKBELONGS ON ARTWORK.ARTWORKID=ARTWORKBELONGS.ARTWORKID WHERE ARTWORK.ORDERID IS NOT NULL) AS A GROUP BY GROUPNAME";
  $result= $conn->query($query2);
  if($result->num_rows>0){
    echo "<table border='1'><tr><th>Group Name</th><th>Total Price</th></tr>";
    while($row= $result->fetch_assoc()){
      echo "<tr><td>{$row['GROUPNAME']}</td><td>{$row['TOTALAMOUNT']}</tr>";
    }
    echo "</table>";
  }
 ?>
