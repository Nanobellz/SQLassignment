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
    <title>Add Friend</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>

    <?php
    if (isset($_GET['confirm']) && !empty($_GET['id'])){
      if(flagFriend($_SESSION['current_user']['id'], $_GET['id']))
      {
        echo "<div class = 'row'>
                <div class = 'offset1'>
                  <h3>Friend Added</h3>
                  <p>Your friend request has been sent.  The user must confirm the request before you will be able to view their profile.</p>
                  <a href = 'main_menu.php' class = 'btn btn-large btn-primary'>Return to menu</a>";
      }

    }
    else if (isset($_GET) && !empty($_GET['id'])){
      //$contact = getContact($_GET['id']);
      $id = $_GET['id'];
      echo"<div class = 'row'>
            <div class='offset1'>
              <h3>Add Friend</h3>
              <p>Would you like to send a friend request to the following contact?</p>
            </div>
          </div>";
      previewContact($id);
      echo "<div class = 'row'>
              <div class = 'span4 offset1'>
                <br>";
      echo "<a href='add_friend.php?id=$id&confirm=1' class='btn btn-primary btn-large'>Confirm</a>";
      echo "<a href='main_menu.php' class = 'btn btn-large pull-right'>Back to Menu</a>";
      echo "  </div>
            </div>";
    }
    ?>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>