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
        <h3>Your Friends</h3>
        <table class = "table table-striped">
          <tr>
            
            <th>First Name</th>
            <th>Last Name</th>
            <th colspan = '2'>Admin Functions</th>
            <th></th>
            <th></th>
          </tr>
          <?php
          while($contact = $result->fetch_assoc()){
            $id = $contact['id'];
                        
              echo "
              <tr>
                <td>{$contact['firstName']}</td>
                <td>{$contact['lastName']}</td>
                <td><button type = 'button' onclick = 'viewContact({$contact['id']})' class = 'btn btn-primary'>View</button></td>
                <td><button type = 'button' onclick = 'adminEdit({$contact['id']})' class = 'btn btn-primary'>Edit User</button></td>";
                if ($contact['status'] == 'active' && $contact['type'] != 'admin'){
                  echo "<td><button type = 'button' onclick = 'adminSuspend($id, &#39;{$contact['status']}&#39;)' class = 'btn btn-warning'>Suspend User</button></td>";
                }
                else if ($contact['status'] == 'suspended'){
                  echo "<td><button type = 'button' onclick = 'adminSuspend($id, &#39;{$contact['status']}&#39;)' class = 'btn btn-success'>Unsuspend User</button></td>";
                }
                else{
                  echo "<td><p class='text-info text-center'>&nbsp;<i class='icon-user'></i>&nbsp;Admin&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p></td>";
                }
                echo"
                <td><button type = 'button' onclick = 'deleteContact({$contact['id']})' class = 'btn btn-danger'>Delete</button></td>";
              echo "</tr>";

            }
          ?>
        </table>
      </div>
    </div>
    <script type="text/javascript">
      // Some functions to activate the buttons above
      function viewContact(id)
      {
        var location = "view_contact.php?id=" + id;
        window.location = location;
      }
      function addFriend(id)
      {
        var location = "add_friend.php?id=" + id;
        window.location = location;
      }
      function adminEdit(id)
      {
        var location = "edit_contact.php?id=" + id;
        window.location = location;
      }
      function adminSuspend(id, suspend)
      {
        var location = "admin_suspend.php?id=" + id + "&suspend=" + suspend;
        window.location = location;
      }
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