<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Databast&egrave; - Logout</title>
    <link rel="stylesheet" href="logout.css">
  </head>
  <body>
    <?php
      include 'config.php';
      $flag= false;
      session_start();
      if(isset($_SESSION['uType']) && isset($_SESSION['cname'])){
        $flag= true;
      }
      session_destroy();
      $conn= mysqli_connect($hostname,$userName,$dbPassword);
      if(!$conn){
        echo "Error";
      }
      $query2= "DROP DATABASE GROUP32";
      if (!$conn->query($query2)) {
        echo "Error";
      }
      mysqli_close($conn);
     ?>
    <header>
      <span class="logo">DataBast&egrave;</span>
    </header>
    <main>
      <div id="successfulTermination">
        <h1>You have successfully logged out</h1>
        <h2>For Safety please close this page</h2>
      </div>
      <div class="ErrorMessage" id="ErrorMessage" style="display: none;">
        <img src="/dataBaste/warning.jpg" alt="" id="warningImg"/>
        <h1>You Don't have access to this page</h1>
        <h4>It seems you have wrongly accessed this page</h4>
        <h4>Move to login page to access your account</h4>
        <button type="button" name="button">Go to Login Page</button>
      </div>
    </main>
    <footer>
      <p>Created by Group 3</p>
    </footer>
    <script type="text/javascript">
      <?php
        if($flag==false){
          echo "document.getElementById('successfulTermination').style.display='none';\n";
          echo "document.getElementById('ErrorMessage').style.display='';\n";
        }
       ?>
    </script>
  </body>
</html>
