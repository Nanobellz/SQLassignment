<?php
//delete_contact.php
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
    <title>Delete Contact</title>
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
      if (isset($_GET['delete']) && $_GET['delete'] == true)
      {
        delete($_GET['id']);
      }
      else
      {
        echo "<h3 class = 'text-center'>Displaying contact #{$_GET['id']}</h3>
            </div>
          </div>";
        displayContact($_GET['id']);
      }
    }
    else
    {
      echo "<h1 class = 'text-center'>No Contact Info</h1>
          </div>
        </div>
        <div class = 'row'>
          <div class = 'span6 offset1>
            <p class = 'alert><strong>Error!</strong> There is no contact with that ID.</p>
            <a href = 'main_menu.php' class = 'btn'>Return to main menu</a>
          </div>
        </div>";
    }

    

    ?>
    <br />
    <div class = 'row'>
      <div class = 'span8 offset1'>
        <p>Are you sure you want to delete this contact?</p>
        <p class = 'text-error'>This cannot be undone!</p>
      </div>
    </div>
    <div class = 'row'>
      <div class = 'span5 offset1'>
        <a href = <?php echo "delete_contact.php?delete=true&id={$_GET['id']}";?> class = 'btn btn-danger'>Delete</button>
        <a href='main_menu.php' class='btn'>Return to main menu</a>
      </div>
    </div>
    <script type="text/javascript">
      function delete(pageid)
      {
        var location = "delete_contact.php?delete=true&id=" + pageid;
        window.location = location;
      }
    </script>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>