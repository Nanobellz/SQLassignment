<?php
//view_contact.php
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
    <title>View Contact</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <div class = 'row'>
      <div class = 'span8 offset1'>

    <?php
    if(isset($_GET['id']) && !empty($_GET['id']))
    {
      $id = $_GET['id'];
      echo "<h3 class = 'text-center'>Displaying contact</h3>
          </div>
        </div>";
      displayContact($id);
      if (getContact($id))
        $successDisplay = true;
    }
    else
    {
      echo "<h1 class = 'text-center'>No Contact Info</h1>
          </div>
        </div>
        <div class = 'row'>
          <div class = 'span6 offset1>
            <p class = 'alert><strong>Error!</strong> There is no contact that matches!</p>
          </div>
        </div>";
    }
    ?>
    <br />
    <div class = 'row'>
      <div class = 'span6 offset1'>
        <a href='main_menu.php' class='btn btn-primary'>Return to main menu</a>
        <?php 

        if (isset($successDisplay) && $successDisplay == true && $_SESSION['current_user']['type'] == 'admin')
          echo"<button type = 'button' onclick = 'deleteContact($id)' class = 'btn btn-danger'>Delete</button>";
        ?>
      </div>
    </div>
    <script type='text/javascript'>
      function deleteContact(pageid)
      {
        var location = "delete_contact.php?id=" + pageid;
        window.location = location;
      }
    </script>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>