<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <script src="/dataBaste/Chart.bundle.min.js"></script>
  <title>Sales Chart</title>
</head>
<body>
  <?php
    include 'config.php';
    $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
    $query= "SELECT GROUPNAME,IFNULL(SUM(COST),0) TOTALCOST FROM (SELECT GROUPS.GROUPNAME,COST FROM GROUPS LEFT JOIN (SELECT GROUPNAME,COST FROM ARTWORKBELONGS NATURAL JOIN (SELECT ARTWORKID,COST FROM ARTWORK WHERE ORDERID IS NOT NULL) AS A) AS B ON GROUPS.GROUPNAME=B.GROUPNAME) AS C GROUP BY GROUPNAME ORDER BY TOTALCOST DESC";
    $lables= "";
    $totalCosts="";
    $result= $conn->query($query);
    if($result->num_rows>0){
      while($row=$result->fetch_assoc()){
        $lables.="'{$row['GROUPNAME']}', ";
        $cost= strval($row['TOTALCOST']);
        $totalCosts.= $cost.", ";
      }
      $lables= substr($lables,0,-2);
      $totalCosts= substr($totalCosts,0,-2);
    }
    mysqli_free_result($result);
    mysqli_close($conn);
   ?>
  <div class="container">
    <canvas id="myChart"></canvas>
  </div>

  <script>
    let myChart = document.getElementById('myChart').getContext('2d');

    // Global Options
    Chart.defaults.global.defaultFontFamily = 'Lato';
    Chart.defaults.global.defaultFontSize = 12;
    Chart.defaults.global.defaultFontColor = '#777';

    let massPopChart = new Chart(myChart, {
      type:'bar', // bar, horizontalBar, pie, line, doughnut, radar, polarArea
      data:{
        labels:[<?php echo $lables;?>],
        datasets:[{
          label:'Amount',
          data:[
            <?php echo $totalCosts; ?>
          ],
          //backgroundColor:'green',
          backgroundColor:[
            'rgba(255, 99, 132, 0.6)',
            'rgba(54, 162, 235, 0.6)',
            'rgba(255, 206, 86, 0.6)',
            'rgba(75, 192, 192, 0.6)',
            'rgba(153, 102, 255, 0.6)',
            'rgba(255, 159, 64, 0.6)',
            'rgba(255, 99, 132, 0.6)',
            'rgba(255, 99, 132, 0.6)'
          ],
          borderWidth:1,
          borderColor:'#777',
          hoverBorderWidth:3,
          hoverBorderColor:'#000'
        }]
      },
      options:{
        title:{
          display:true,
          text:'Chart For Sales of Painting Genre Wise',
          fontSize:25
        },
        legend:{
          display:true,
          position:'right',
          labels:{
            fontColor:'#000'
          }
        },
        layout:{
          padding:{
            left:50,
            right:0,
            bottom:0,
            top:0
          }
        },
        tooltips:{
          enabled:true
        }
      }
    });
  </script>
</body>
</html>
