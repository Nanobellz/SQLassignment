<?php
//edit_contact.php
session_start();
include "functions.php";
include "regex_validate.php";
if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] != true)
{
  header("Location: login.php");
  exit();
}
if (!isset ($_SESSION['edited']))
  $_SESSION['edited'] = false;
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Edit Contact</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel='stylesheet' href='css/font-awesome.min.css'>
  </head>
  <body>
    <div class = 'row'>
      <div class = 'span12'>
    <?php
    
    if(isset($_GET['id']) && !empty($_GET['id']))
    {
      
        
      $contact = getContact($_GET['id']);
      foreach ($contact as $key => $value) 
      {
        if (!isset($_GET[$key])) 
          $_GET[$key] = $contact[$key];
      }
      $validate = validateFields($_GET);
      
      
      echo "<h3 class = 'text-center'>Editing contact #{$_GET['id']}</h3>
          </div>
        </div>";
        if (!empty($_GET["title"]) && !empty($_GET["firstName"]) && !empty($_GET["lastName"]) 
          && $validate['email'] && $validate['phone'] && $validate['work'] && $validate['mobile'] && $validate['image']
          && $_SESSION['edited'])
        {
          $_SESSION['edited']=false;
          foreach ($_GET as $key => $value) 
          {
            $contact[$key] = $_GET[$key];
          }
          if (isset($validate['home_phone']))
            $contact['home_phone'] = $validate['home_phone'];
          if (isset($validate['mobile_phone']))
            $contact['mobile_phone'] = $validate['mobile_phone'];
          if (isset($validate['work_phone']))
            $contact['work_phone'] = $validate['work_phone'];
          
          modifyContact($contact);
          /*if (file_exists("contactlist.txt"))
          {
            $contact_list = loadJson("contactlist.txt");
          }
          $contact_list[$_GET['id']] = $contact;
          

          saveJson("contactlist.txt", $contact_list);*/
          displayContact($_GET['id']);
          clearContact();
          echo"
          <br />
          <div class = 'row'>
            <div class = 'span3 offset1'>
              <a href='main_menu.php' class='btn btn-primary'>Return to main menu</a>
            </div>
          </div>";
        }
        
        else
        {
          echo"
        <form action='edit_contact.php' method='GET'>
          <input type='hidden' name='id' value='{$contact['id']}'>";
          $_SESSION['edited'] = true;
          editContact($_GET, $validate);
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
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>