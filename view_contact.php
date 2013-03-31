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
    <div class = 'row-fluid'>
      <div class = 'span5 offset1'>

    <?php
    if(isset($_GET['id']) && !empty($_GET['id']))
    {
      $id = $_GET['id'];
      echo "<h3 class = 'text-center'>Displaying contact</h3>
          </div>
        </div>
        <div class = 'row-fluid'>
          <div class = 'span6 offset1'>";
      displayContact($id);
      if (getContact($id))
        $successDisplay = true;
      
    }
    else
    {
      echo "<h1 class = 'text-center'>No Contact Info</h1>
          </div>
        </div>
        <div class = 'row-fluid'>
          <div class = 'span6 offset1>
            <p class = 'alert><strong>Error!</strong> There is no contact that matches!</p>
          </div>
        </div>";
    }
    ?>
    <br />
    
        <a href='main_menu.php' class='btn btn-primary'>Return to main menu</a>
        <?php
        $friends = getmyFriends($_SESSION['current_user']['id']);
        if (in_array($id, $friends)){
          echo "<button type = 'button' onclick = 'removeFriend($id)' class = 'btn btn-warning'>Unfriend</button>";
        }
        ?>
      </div>
    </div>
    <script type='text/javascript'>
      function removeFriend(friendid)
      {
        var location = "remove_friend.php?id=" + friendid;
        window.location = location;
      }
    </script>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>