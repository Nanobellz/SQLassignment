<?php
//main_menu.php
session_start();
include "functions.php";
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true)
{
  header("Location: login.php");
  exit();
}
$user = $_SESSION["user_n"];
$confirmed = amIPending($_SESSION['current_user']['id']);
if (!$confirmed){
  unset($_SESSION);
  header("Location: pending.php");
  exit();
}

$friends = getMyFriends($_SESSION['current_user']['id']);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Remove Friend</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <?php
      echo"
        <div class= 'row-fluid'>
          <div class = 'span5 offset1'>
            <h3 class = 'text-center'>Remove Friend</h3>
          </div>
        </div>
        <div class = 'row-fluid'>
          <div class = 'span6 offset1'>";
      if (isset($_GET['id'])){
        if (isset($_GET['confirm']) && $_GET['confirm'] == true)
        {
          echo"
            <p>Friend deleted.</p>
            <a href='main_menu.php' class = 'btn'>Return to Menu</a>";
            removeFriend($_GET['id'], $_SESSION['current_user']['id']);
            unset($_GET['confirm']);
        }
        else
        {
        displayContact($_GET['id']);
        echo"
            <p>Do you want to remove this contact from your friends list?</p>
            <a href='remove_friend.php?id={$_GET['id']}&confirm=true' class = 'btn btn-warning'>Confirm</a>
            &nbsp;
            <a href='main_menu.php' class = 'btn'>Return to Menu</a>";
        }
      }else{
        echo "<p>No contact was found.</p>
              <a href='main_menu.php' class = 'btn'>Return to Menu</a>";
      }
    ?>
        </div>
      </div>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>