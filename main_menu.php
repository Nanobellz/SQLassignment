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
$pending = isPending($_SESSION['current_user']['id']);
$user_pending = isUserPending();
$friends = getMyFriends($_SESSION['current_user']['id']);
$result = getContacts();
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Main Menu</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <br />
    <div class = 'row-fluid'>
      <div class = 'span6 offset1'>
        <h3>Welcome "<?php echo "$user"?>" to FriendBook™</h3>
      </div>
      <div class = 'span2'>
        <a href="login.php?logout=true" class = 'btn'>Log out</a>
      </div>
    </div>
    <div class = "row-fluid">
      <div class = "span4 offset1">
        <h3>Your Friends</h3>
        <table class = "table table-striped">
          <tr>
            
            <th>First Name</th>
            <th>Last Name</th>
            <th></th>
            
          </tr>
          <?php
          
          while($contact = $result->fetch_assoc()){
            $id = $contact['id'];
            if (in_array($id, $friends)){
              
              echo "<tr>
                
                <td>{$contact['firstName']}</td>
                <td>{$contact['lastName']}</td>
                <td><button type = 'button' onclick = 'viewContact($id)' class = 'btn btn-primary'>View</button></td>
                
              </tr>";

            }

          }
          
          
          ?>
        </table>
      </div>
    </div>
    <?php
    if ($pending){
      echo "
        <div class = 'row-fluid'>
          <div class = 'offset1'>
            <a href='confirm_friends.php' class = 'btn btn-info btn-large'>You have friend requests</a> &nbsp;
          </div>
        </div>";
    }
    if ($user_pending && $_SESSION['current_user']['type'] == 'admin')
    {
      echo "
        <div class = 'row-fluid'>
          <div class = 'offset1'>
            <a href='user_pending.php' class = 'btn btn-info btn-large'>There are new user requests</a> &nbsp;
          </div>
        </div>";
    }
    if ($_SESSION['current_user']['type'] == 'admin')
    {
      echo "
      <br>
      <div class = 'row-fluid'>
        <div class = 'span1 offset1'>
          <a href = 'new_contact.php' class = 'btn'>Create a user</a>
        </div>
        <div class = 'span1'>
          <a href = 'view_all.php' class = 'btn'>View all users</a>
        </div>
      </div>";
    }
    // search field
    
      echo "<br>
        <div class = 'row-fluid'>
          <div class = 'offset1'>
            <form class='form-search' action = 'search.php' method = 'get'>
              <div class='input-append'>
                <input type='text' name = 'search' class='input-medium search-query' placeholder='Search for Users'>
                <button type='submit' class='btn'>Search</button>
              </div>
            </form>
          </div>
        </div>";
    
    ?>
    <div class = 'row-fluid'>
      <div class = 'offset1'>
        <p><a href="change_pass.php">Click to change your password</a></p>
      </div>
    </div>

    
    <script type="text/javascript">
      // Some functions to activate the buttons above
      function viewContact(pageid)
      {
        var location = "view_contact.php?id=" + pageid;
        window.location = location;
      }

      function editContact(pageid)
      {
        var location = "edit_contact.php?id=" + pageid;
        window.location = location;
      }
    </script>

  </body>
</html>