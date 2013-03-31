<?php
//create_contact.php
session_start();
include "functions.php";
if (!isset($_SESSION["new_user"]) || $_SESSION["new_user"] != true)
{
  header("Location: login.php");
  exit();
}
$matchbad = false;
if (isset($_POST)){
  if (isset($_POST['newpass']) && isset($_POST['confirmpass'])){
    if ($_POST['newpass'] === $_POST['confirmpass']){
      $_SESSION['password'] = md5($_POST['newpass']);
    }
    else
    {
      $matchbad = true;
    }
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Create Contact</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>
    
    <?php
    $newcontact = array();
    foreach ($_SESSION as $key => $value) {
      if ($key != "logged_in" && $key != "user_n" && $key != "new_user")
      {
        $newcontact[$key] = $value;
      }
    }
    echo "<br>";
    
    
    $title=$newcontact['title'];
    
    if (isset($newcontact["firstName"]) && !empty($newcontact["firstName"]))
    { 
      if (isset($newcontact['password']) && !empty($newcontact['password']))
      {
        global $db;
        
        if($db->connect_errno > 0){
          die('Unable to connect to database [' . $db->connect_error . ']');
        }
        //print_r( $newcontact['type']);
        if (isset($_SESSION['current_user']['type']) && $_SESSION['current_user']['type'] == 'admin'){
        $query = $db->prepare("INSERT INTO `assignment2`.`members` (`id`, `type`, `title`, `firstName`, `lastName`, `email`, `password`, `webaddr`, `home_phone`, `work_phone`, `mobile_phone`, `twitter`, `facebook`, `image`, `comment`, `status`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $newcontact['status'] = "active";
        $query->bind_param('sssssssssssssss', $newcontact['type'], $newcontact['title'], $newcontact['firstName'], $newcontact['lastName'], $newcontact['email'], $newcontact['password'], $newcontact['webaddr'], $newcontact['home_phone'], $newcontact['work_phone'], $newcontact['mobile_phone'], $newcontact['twitter'], $newcontact['facebook'], $newcontact['image'], $newcontact['comment'], $newcontact['status']);
        }else{   
        $query = $db->prepare("INSERT INTO `assignment2`.`members` (`id`, `title`, `firstName`, `lastName`, `email`, `password`, `webaddr`, `home_phone`, `work_phone`, `mobile_phone`, `twitter`, `facebook`, `image`, `comment`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param('sssssssssssss', $newcontact['title'], $newcontact['firstName'], $newcontact['lastName'], $newcontact['email'], $newcontact['password'], $newcontact['webaddr'], $newcontact['home_phone'], $newcontact['work_phone'], $newcontact['mobile_phone'], $newcontact['twitter'], $newcontact['facebook'], $newcontact['image'], $newcontact['comment']);
        }
        $query->execute();
        $query->close();
        
        echo "<div class='row'>
                <div class = 'span10 offset1'>
                  <h1>Contact Created Successfully</h1>";
                  if (!isset($_SESSION['current_user']['type']) || $_SESSION['current_user']['type'] != 'admin'){
                    echo "<p>This account must be verified by an admin before it can be activated.  Thank you for applying.</p>";
                  }

                  if (getLast()){
      
                    displayContact(getLast());
                  }
                  
                  unset($newcontact);
                  clearContact();
                echo"</div>
              </div>
              <div class = 'row'>
                <div class = 'offset1'>
                  <br />
                  <a href='main_menu.php' class='btn btn-primary'>Go Back</a>
                </div>
              </div>";
      }
      else
      {
        echo "<div class='row'>
                <div class = 'span10 offset1'>
                  <form action = 'create_contact.php' method = 'post'>
                    <fieldset>
                      <legend>Choose A Password</legend>";
                    if ($matchbad == true)
                    {
                      echo "<div class = 'control-group error'>
                              <label>Enter new password</label>
                              <div class = 'controls'>
                                <input type = 'password' name='newpass'>
                                <span class = 'help-inline'>The passwords do not match</span>
                              </div>
                              <label>Confirm new password</label>
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
                    <label>Confirm new password</label>
                    <input type='password' name='confirmpass'>";
                    }
                    echo "<br><button class='btn btn-large btn-primary' type='submit'>Submit</button>
                          </fieldset>
                        </form>
                      </div>
                    </div>
                    ";

                
      }
    }
    
    

    ?>
    
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>