<?php
//change_pass.php
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
    <title>Change Password</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
    <?php
    $oldbad = false;
    $matchbad = false;
    $user = $_SESSION['current_user']['email'];
    //echo "<br> ID:{$_SESSION['current_user']['id']}<br>";
    $pass = getPassword($_SESSION['current_user']['id']);
    //print_r($pass);
    //print_r($_SESSION);
    //print_r($_POST);
    if (isset($_SESSION['current_user']['email']) && !empty($_SESSION['current_user']['email']))
    {

      if (isset($_POST) && isset($_POST['oldpass']) && isset($_POST['newpass']) && isset($_POST['confirmpass']))
      {

        
        //print_r($pass);
        //$adminlogin = loadJson("adminpass.txt");
        $oldpass = md5($_POST['oldpass']);
        $newpass = md5($_POST['newpass']);
        $confirmpass = md5($_POST['confirmpass']);
        if ($oldpass == $pass)
        {
          $oldbad = false;
          if ($newpass == $confirmpass)
          {
            $matchbad = false;
            
            //saveJson("adminpass.txt", $adminlogin);
            setPassword($user, $newpass);
            $id = getID($user);
            $_SESSION['current_user'] = getContact($id);
            echo"
              <script type='text/javascript'>
                window.location = 'passconfirm.php';
              </script>";

          }
          else
          {
            $matchbad = true;
          }
        }
        else
        {
          $oldbad = true;
        }
      }

      $user = $_SESSION['user_n'];
      echo "<div class='row'>
              <div class = 'span6 offset1'>
                <form action='change_pass.php' method = 'post'>
                  <fieldset>
                    <legend>Change Password for '$user'</legend>
                    <label>Enter old password</label>";
                    
                    if ($oldbad == true)
                    {
                      echo "<div class = 'control-group error'>
                              <div class = 'controls'>
                                <input type = 'password' name='oldpass'>
                                <span class = 'help-inline'>The old password is not correct.</span>
                              </div>
                            </div>
                      ";
                    }
                    else
                    {
                      echo "<input type='password' name='oldpass'>";
                    }
                    if ($matchbad == true)
                    {
                      echo "<div class = 'control-group error'>
                              <label>Enter new password</label>
                              <div class = 'controls'>
                                <input type = 'password' name='newpass'>
                                <span class = 'help-inline'>The passwords do not match</span>
                              </div>
                              <label>Re-enter new password</label>
                              <div class = 'controls'>
                                <input type = 'password' name = 'confirmpass'>
                                <span class = 'help-inline'>Please try again</span>
                              </div>
                            </div>
                      ";
                    }
                    else
                    {   
                    echo" 
                    <label>Enter new password</label>
                    <input type='password' name='newpass'>
                    <label>Re-enter new password</label>
                    <input type='password' name='confirmpass'>";
                    }
                    echo "<br><button class='btn btn-large btn-primary' type='submit'>Confirm</button>
                          </fieldset>
                        </form>
                      </div>
                    </div>
                    ";     
    }
    else
    {
      echo "Can't determine the logged in user.  <a href='login.php?logout=true'>Please log in again.</a>";
    }
    ?>
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>