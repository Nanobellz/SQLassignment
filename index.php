<?php
//index.php
session_start();
if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true)
{
  header("Location: main_menu.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Welcome</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <div class = 'container'>
      <div class = 'hero-unit'>
        <h1>Contact Management</h1>
        <p>Welcome to Contact Management.</p>
        <p>To log in, please click below</p>
        <a href = "login.php" class = 'btn btn-large btn-primary'>Log in</a>
        <a href = "new_contact.php" class = 'btn btn-large'>Sign up</a>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
      </div>
    </div>
  </body>
</html>