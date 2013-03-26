<?php
require 'db.php';
$prod = false;


if(isset($_POST['id']) && is_numeric($_POST['id'])){
   update_product($_POST['id'],$_POST['name'],$_POST['price']);
   header("Location: index.php");
}

if(isset($_GET['id']) && is_numeric($_GET['id'])){
   $prod = find_product($_GET['id']);
}

?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Week 11</title>
   </head>
   <body>
      <?php if(is_array($prod)):?>
      <h3>Modify Product</h3>
      <form method="POST" action="modify.php">
         Product Name: <input type="text" name="name" value="<?php echo $prod['name'];?>"><br>
         Product Price:<input type="text" name="price" value="<?php echo $prod['price'];?>"><br>
         <input type="hidden" name="id" value="<?php echo $prod['id'];?>">
         <input type="submit" value="Modify Product">
      </form>
      <?php else:?>
         <h3> Could not find product </h3>
      <?php endif;?>
   </body>   
</html>