<?php
//view_all.php
session_start();
include "functions.php";
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true || !isset($_SESSION['current_user']['type'])  || $_SESSION['current_user']['type'] != 'admin')
{
  header("Location: login.php");
  exit();
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>View All Users</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <?php
      $result = getContacts();
    ?>
     <div class = "row-fluid">
      <div class = "span6 offset1">
        <h3>Pending Users</h3>
        <table class = "table table-striped">
          <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th colspan ="1">Admin Functions</th>
            <th></th>
            <th></th>
          </tr>
          <?php
          while($contact = $result->fetch_assoc()){
            $id = $contact['id'];
                if ($contact['status'] == 'pending' && $contact['type'] != 'admin'){
                   echo "
                        <tr>
                          <td>{$contact['firstName']}</td>
                          <td>{$contact['lastName']}</td>
                          <td><button type = 'button' 
                               onclick     = 'viewContact({$contact['id']})' 
                               class       = 'btn btn-primary'>View</button></td>
                            <td><button type    = 'button' 
                                        onclick = 'adminAllow($id, &#39;{$contact['status']}&#39;)' 
                                        class   = 'btn btn-success'>Accept User</button></td>";
                }   
               echo "</tr>";
            }
          ?>
        </table>
      </div>
    </div>
    <br />
    <div class = 'row-fluid'>
      <div class = 'span6 offset1'>
        <a href='main_menu.php' class='btn btn-primary'>Return to main menu</a>
      </div>
    </div>
    <script type="text/javascript">
      // Some functions to activate the buttons abov
      function adminAllow(id, allow)
      {
        var location = "admin_allow.php?id=" + id + "&allow=" + allow;
        window.location = location;
      }
      function viewContact(id)
      {
        var location = "view_contact.php?id=" + id;
        window.location = location;
      }
    </script>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>