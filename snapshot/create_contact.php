<?php
//create_contact.php
session_start();
include "functions.php";
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true)
{
  header("Location: login.php");
  exit();
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Create Contact</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <div class='row'>
      <div class = "span10 offset1">
        <h1>Contact Created Successfully</h1>
      </div>
    </div>
    <?php
    $newcontact = array();
    foreach ($_SESSION as $key => $value) {
      if ($key != "logged_in" && $key != "user_n")
      {
        $newcontact[$key] = $value;
      }
    }
    echo "<br>";
    
    
    $title=$newcontact['title'];
    
    if (!empty($newcontact["firstName"]))
    { 
      global $db;
      
      if($db->connect_errno > 0){
        die('Unable to connect to database [' . $db->connect_error . ']');
      }
      


      $query = $db->prepare("INSERT INTO `assignment2`.`users` (`id`, `title`, `firstName`, `lastName`, `email`, `webaddr`, `home_phone`, `work_phone`, `mobile_phone`, `twitter`, `facebook`, `image`, `comment`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $query->bind_param('ssssssssssss', $newcontact['title'], $newcontact['firstName'], $newcontact['lastName'], $newcontact['email'], $newcontact['webaddr'], $newcontact['home_phone'], $newcontact['work_phone'], $newcontact['mobile_phone'], $newcontact['twitter'], $newcontact['facebook'], $newcontact['image'], $newcontact['comment']);

      $query->execute();
      $query->close();
     
    }
    if (getLast()){
      
      displayContact(getLast());
    }
    clearContact();
    

    ?>
    <div class = 'row'>
      <div class = 'offset1'>
        <br />
        <a href="main_menu.php" class="btn btn-primary">Return to menu</a>
      </div>
    </div>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>