<?php
//edit_contact.php
session_start();
include "functions.php";
//include "regex_validate.php";
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true || !isset($_SESSION['current_user']['type'])  || $_SESSION['current_user']['type'] != 'admin')
{
  header("Location: login.php");
  exit();
}

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Suspend User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <div class = 'row'>
      <div class = 'span12 offset1'>
        <h3>Suspend User</h3>
    <?php

    if(isset($_GET['id']) && !empty($_GET['id']))
    { 
      //print_r($_GET);
      if(isset($_GET['suspend']) && $_GET['suspend'] == 'active')
      {
        if (isset($_GET['confirm']) && $_GET['confirm'] == true)
        {
          suspend($_GET['id']);
          echo "<p>User has been suspended</p>
                <a href='main_menu.php' class = 'btn btn-primary'>Return to Menu</a>";
        }
        else
        {      
          $contact = getContact($_GET['id']);
          displayContact($_GET['id']);
          echo "<p>Are you sure you want to suspend this user?</p>
                <div class = 'row'>
                  <a href = 'admin_suspend.php?id={$_GET['id']}&confirm=true&suspend=active' class = 'btn btn-warning'>Confirm</a> 
                  <a href = 'main_menu.php' class = 'btn'>Return to menu</a>";
        }
      }
      else if (isset($_GET['suspend']) && $_GET['suspend'] == 'suspended')
      {
        if (isset($_GET['confirm']) && $_GET['confirm'] == true)
        {
          unsuspend($_GET['id']);
          echo "<p>User has been restored</p>
                <a href = 'main_menu.php' class = 'btn btn-primary'>Return to Menu</a>";
        }
        else
        {      
          $contact = getContact($_GET['id']);
          displayContact($_GET['id']);
          echo "<p>Are you sure you want to restore access to this user?</p>
                <div class = 'row'>
                  <a href = 'admin_suspend.php?id={$_GET['id']}&confirm=true&suspend=suspended' class = 'btn btn-success'>Confirm</a> 
                  <a href = 'main_menu.php' class = 'btn'>Return to menu</a>";
        }
      }
      else
      {
        echo "Can't find the status for this user";
      }
    }
    else
    {
      echo "<p>No user with that ID is active in the system.</p>
            <a href = 'main_menu.php' class = 'btn btn-primary'>Return to menu</a>";
    }
    ?>

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>