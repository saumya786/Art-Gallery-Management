<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Databast&egrave; - Login Page For Agent</title>
    <link rel="stylesheet" href="LoginPageForAdmin.css">
  </head>
  <body>
    <?php
      include 'config.php';
      $flag= true;
      if($_SERVER["REQUEST_METHOD"]=="POST")
      {
        if(isset($_POST['Password']) && $_POST['agEmail'])
        {
          $password= test_input($_POST['Password']);
          $agEmail= test_input($_POST['agEmail']);
          $conn= mysqli_connect($hostname,$userName,$dbPassword,$dbName);
          if(!$conn){
            die("Connection Failed ".$conn->connect_error);
          }
          $query1= "SELECT AID,AGNAME,PASSWORD FROM AGENT WHERE AGEMAILID='{$_POST['agEmail']}'";
          $result= $conn->query($query1);
          if($result->num_rows>0)
          {
            $row= $result->fetch_array();
            if($row['PASSWORD']==$password)
            {
              mysqli_close($conn);
              session_start();
              $_SESSION["uType"]= "agent";
              $_SESSION["cname"]= $row['AGNAME'];
              $_SESSION["AID"]= $row['AID'];
              header("location:/dataBaste/dashBoard.php");
            }
            else {
              $flag= false;
            }
          }
          else {
            $flag= false;
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
         <strong>Error!</strong> It seems you have entered wrong credentials
          <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
       </div>
     </header>
     <main>
       <h1>Agent Login Page</h1>
       <div class="FormContents">
         <form class="" method="post">
           <label for="agEmail">Enter your mail id</label>
           <input type="email" name="agEmail" value="" id="agEmail" placeholder="Enter Your mail-id" maxlength="90" required/>
           <label for="Password">Enter Password </label>
           <input type="password" name="Password" value="" id="Password" placeholder="Please enter your Password" maxlength="16" required/><br/><br/>
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
