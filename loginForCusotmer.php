<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Databast&egrave; - Login Page For Administrator</title>
    <link rel="stylesheet" href="LoginPageForAdmin.css">
  </head>
  <body>
    <?php
      include 'config.php';
      $flag= true;
      if($_SERVER["REQUEST_METHOD"]=="POST")
      {
        if(isset($_POST['Password']) && $_POST['cEmail'])
        {
          $password= test_input($_POST['Password']);
          $cEmail= test_input($_POST['cEmail']);
          $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
          if(!$conn){
            die("Connection Failed ".$conn->connect_error);
          }
          $query1= "SELECT CNAME,PASSWORD FROM CUSTOMER WHERE CEMAILID='$cEmail'";
          $result= $conn->query($query1);
          if($result->num_rows>0)
          {
            $row= $result->fetch_array();
            if($row['PASSWORD']==$password)
            {
              mysqli_close($conn);
              session_start();
              $_SESSION["uType"]= "customer";
              $_SESSION["cname"]= $row['CNAME'];
              header("location:/dataBaste/dashBoard.php");
            }
            else {
              $flag= false;
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
     <header>
       <div class="logo">DataBast&egrave;</div>
       <div class="ErrorMessage" id="ErrorMessage" style="display:none;">
         <strong>Error!</strong> It seems you have entered an wrong Password
          <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
       </div>
     </header>
     <main>
       <h1>Customer Login Page</h1>
       <div class="FormContents">
         <form class="" method="post">
           <label for="cEmail">Enter your email</label>
           <input type="email" name="cEmail" value="" id="cEmail" placeholder="Enter your email id" required/>
           <label for="Password">Enter Password </label>
           <input type="password" name="Password" value="" id="Password" placeholder="Please enter your Password" required/><br/><br/>
           <input type="submit" name="" value="Login">
         </form>
       </div>
     </main>
     <footer>
       <p>Created by Group 3</p>
     </footer>
     <?php
      if($flag==false)
      {
        echo "<script>document.getElementById('ErrorMessage').style.display=''</script>";
      }
      ?>
  </body>
</html>
