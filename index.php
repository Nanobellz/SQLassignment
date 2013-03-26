<?php
require 'db.php';

if(isset($_POST['name'])){
   add_product($_POST['name'], $_POST['price']);
}else if(isset($_GET['delete']) && is_numeric($_GET['delete'])){
   remove_product($_GET['delete']);
}


?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8">
      <title>Week 11</title>
      <style>
         th{
            border-bottom: 1px solid black;
            text-align: left;
         }
         td{
            padding-right:10px;
         }
      </style>
   </head>
   <body>
      <h1>Products</h1>
      <table>
         <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Actions</th>
         </tr>
      <?php
      $result = get_products();
      while($prod = mysqli_fetch_assoc($result)){
         echo '<tr>';
            echo '<td>' . $prod['name'] . '</td>';
            echo '<td>$' . $prod['price'] . '</td>';
            echo '<td><a href="?delete=' . $prod['id']  .'">Delete</a></td>&nbsp;';
            echo '<td><a href="modify.php?id=' . $prod['id']  .'">Modify</a></td>';
         echo '</tr>';
      }
      ?>
      </table>
      <hr>
      <h3>Add more products</h3>
      <form method="POST" action="index.php">
         Product Name: <input type="text" name="name"><br>
         Product Price:<input type="text" name="price"><br>
         <input type="submit" value="Add Product">
      </form>      
   </body>   
</html>