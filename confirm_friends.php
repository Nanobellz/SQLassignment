<?php
//confirm_friends.php
session_start();
include "functions.php";
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true)
{
  header("Location: login.php");
  exit();
}
$user = $_SESSION["user_n"];


?>
<!DOCTYPE html>
<html>
  <head>
    <title>Confirm Friends</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <?php
    if (isset($_GET) && !empty($_GET['confirmed'])){
      confirmFriend($_GET['confirmed'], $_SESSION['current_user']['id']);
    }

    $id = $_SESSION['current_user']['id'];
    $pending = getPending($id);
    if (count($pending) < 1){
      echo "<div class = 'row>
              <div class = 'offset 1'>
                <p>There are no more friend requests pending</p>
                <a href = 'main_menu.php' class = 'btn btn-primary'>Return to Menu</a>";
    }else{


      echo "<div class = 'row'>
              <div class = 'offset1'>
                <h3>Confirm Pending Friend Requests</h3>";
      echo "<table>
              <tr><th>First Name</th>
              <th>Last Name</th>
              <th></th></tr>";
      
      foreach ($pending as $row => $column) {
        //print_r($pending[$row]);
        $contact = getContact($pending[$row]['requesting_member']);
        echo "<tr><td>{$contact['firstName']}</td><td>{$contact['lastName']}</td><td><a href='confirm_friends.php?confirmed={$contact['id']}' class='btn btn-primary'>Confirm</a></td></tr>";
      }
      echo "    </table>
              </div>
            </div>";
    }    ?>

    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>