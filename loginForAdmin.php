<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Databast&egrave; - Login Page For Administrator</title>
  </head>
  <body>
    <?php
      include 'config.php';
      if($_SERVER["REQUEST_METHOD"]=="POST")
      {
        if(isset($_POST['Password']))
        {
          $password= test_input($_POST['Password']);
          $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
          if(!$conn){
            die("Connection Failed ".$conn->connect_error);
          }
          $query1= "SELECT PASSWORD FROM ADMIN WHERE ID=1 LIMIT 1";
          $result= $conn->query($query1);
          if($result->num_rows>0)
          {
            $row= $result->fetch_array();
            if($row['PASSWORD']==$password)
            {
              mysqli_close($conn);
              session_start();
              $_SESSION["uType"]= "admin";
              $_SESSION["uid"]= 01;
              header("location:/dataBaste/dashboard.php");
            }
            else {
              echo "<h2>Wrong Password</h2>";
              mysqli_close($conn);
            }
          }
        }
      }
      function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }
     ?>
    <h1>Admin Login</h1>
    <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
      <label for="Password">Password </label>
      <input type="password" name="Password" value="" id="Password" placeholder="Please enter your Password" required/><br/><br/>
      <input type="submit" name="" value="Login">
    </form>
  </body>
</html>
