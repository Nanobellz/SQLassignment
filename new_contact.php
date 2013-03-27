<?php
//new_contact.php
session_start();
include "functions.php";
include "regex_validate.php";
//if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true)
//{
//  header("Location: login.php");
//  exit();
//}
?>
<!DOCTYPE html>
<html>
  <head>
      <title>Create New Account</title>
      <meta name='viewport' content='width=device-width, initial-scale=1.0'>
      <!-- Bootstrap -->
      <link href='css/bootstrap.min.css' rel='stylesheet' media='screen'>
      <link rel='stylesheet' href='css/font-awesome.min.css'>
    </head>
  <body>

    <?php
    if (!empty($_GET))
    {
      foreach ($_GET as $key => $value) 
      {    
          $_SESSION[$key] = $value; 
      } 
    }

    $fields = $_SESSION;
    $validate = validateFields($fields);
    if (isset($validate['home_phone']))
      $_SESSION['home_phone'] = $validate['home_phone'];
    if (isset($validate['mobile_phone']))
      $_SESSION['mobile_phone'] = $validate['mobile_phone'];
    if (isset($validate['work_phone']))
      $_SESSION['work_phone'] = $validate['work_phone'];

    if (!empty($fields["title"]) && !empty($fields["firstName"]) && !empty($fields["lastName"]) && $validate['email'] && $validate['phone'] && $validate['work'] && $validate['mobile'] && $validate['image'])
    {
      $_SESSION['new_user'] = true;
      echo"
        <script type='text/javascript'>
          window.location = 'create_contact.php';
        </script>";
    }
    else
    {
      echo "<form action='new_contact.php' method='GET'>";
      editContact($fields, $validate);
    }
    ?>

    <script src='http://code.jquery.com/jquery.js'></script>
    <script src='js/bootstrap.min.js'></script>
  </body>
</html>