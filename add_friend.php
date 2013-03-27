<!DOCTYPE html>
<html>
  <head>
    <title>Bootstrap 101 Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
  </head>
  <body>

    <?php
    if ($_GET['confirm']){

    }
    else if (isset($_GET) && !empty($_GET['id'])){
      //$contact = getContact($_GET['id']);
      
      echo"<div class = 'row'>
            <div class='offset1'>
              <h3>Add Friend</h3>
              <p>Would you like to send a friend request to te following contact?</p>
            </div>
          </div>";
      previewContact($_GET['id']);
    }
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>