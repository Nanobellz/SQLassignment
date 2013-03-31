<?php
//login.php
//the login layout is based on the layout sample at http://twitter.github.com/bootstrap/examples/signin.html
//the current passwords are provided in passwordsForMaziar.txt
//there is currently no way to add a new username using the web interface
session_start();
include "functions.php";
//$adminlogin = loadJson("adminpass.txt"); 
// gets a list of valid usernames and passwords as md5 hashes

if(isset($_POST['email'])){
  //check if credentials are correct
  $user = $_POST['email'];
  $pass = md5($_POST['pass']);
  //hashes the inputs so they can be compared to the stored hashes
  if(check_login($user, $pass))
  {
    $id =  getID($user);
    $_SESSION['current_user'] = getContact($id);
    if ($_SESSION['current_user']['status'] =='pending')
    {
      echo "<script type='text/javascript'>
            window.location = 'pending.php';
          </script>";
    }
      //$_SESSION['new_create'] = true;
    
    $_SESSION['user_n'] = $_SESSION['current_user']['firstName']. " " . $_SESSION['current_user']['lastName'];
    $_SESSION['logged_in'] = true;
  }
  else
  { //inform user of an error
    $err = "Invalid username or password";
  }

//check if user has clicked log out
}
else if(isset($_GET['logout']))
{
  unset($_SESSION['logged_in']);
  unset($_SESSION['user_n']);
  unset($_SESSION['current_user']);
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Sign in</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="./css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 40px;
        padding-bottom: 40px;
        background-color: #f5f5f5;
      }

      .form-inline {
        max-width: 500px;
        padding: 19px 29px 29px;
        margin: 0 auto 20px;
        background-color: #fff;
        border: 1px solid #e5e5e5;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }
      .form-inline .form-inline-heading,
      .form-inline .checkbox {
        margin-bottom: 10px;
      }
      .form-inline input[type="text"],
      .form-inline input[type="password"] {
        font-size: 16px;
        height: auto;
        margin-bottom: 15px;
        padding: 7px 9px;
      }

    </style>
    <link href="./css/bootstrap-responsive.css" rel="stylesheet">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="./js/html5shiv.js"></script>
    <![endif]-->
  </head>

  <body>
  <?php 
  if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == true)
  {
    echo "<script type='text/javascript'>
            window.location = 'main_menu.php';
          </script>";
  }
  else echo '
    <div class="container">
      <form class="form-inline" name = "login_form" action="login.php" method = "POST">
        <h2 class="form-inline-heading">Log in to FriendBook™</h2>';
        if(isset($err)){
        echo '<span style="color:red;">' . $err .'</span>';
        }
        echo '
        <input type="text" class="span3" placeholder="Email" name = "email" value = "rdecaire@georgebrown.ca">
        <input type="password" class="span2" placeholder="Password" name = "pass" value = "fnord">
        <br><button class="btn btn-large btn-primary" type="submit">Sign in</button>
        <a href="new_contact.php" class="btn btn-large">Sign up</a>
      </form>


    </div> <!-- /container -->
  '?>
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="./js/jquery.js"></script>
    <script src="./js/bootstrap-transition.js"></script>
    <script src="./js/bootstrap-alert.js"></script>
    <script src="./js/bootstrap-modal.js"></script>
    <script src="./js/bootstrap-dropdown.js"></script>
    <script src="./js/bootstrap-scrollspy.js"></script>
    <script src="./js/bootstrap-tab.js"></script>
    <script src="./js/bootstrap-tooltip.js"></script>
    <script src="./js/bootstrap-popover.js"></script>
    <script src="./js/bootstrap-button.js"></script>
    <script src="./js/bootstrap-collapse.js"></script>
    <script src="./js/bootstrap-carousel.js"></script>
    <script src="./js/bootstrap-typeahead.js"></script>

  </body>
</html>
