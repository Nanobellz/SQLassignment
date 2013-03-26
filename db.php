<?php
$link = mysqli_connect('localhost', 'admin','pass','wk11');

if(!$link){
   die('Connect Error (' . $mysqli->connect_errno . ')' . $mysqli->connect_error);
}


/**
 * gets all products from table
 */
function get_products(){
   global $link;
   if($result = mysqli_query($link, "SELECT * FROM products")){
      return $result;
   }else{
      return array();
   }   
}

/**
 * adds a new product to the table
 */
function add_product($name, $price){
   global $link;
   $query = mysqli_prepare($link, "INSERT INTO `wk11`.`products` (`id`, `name`, `price`) VALUES (NULL, ?, ?)");
   mysqli_stmt_bind_param($query,'si', $name, $price);
   mysqli_stmt_execute($query);
   mysqli_stmt_close($query);
}

/**
 * removes product from table
 */
function remove_product($id){
   global $link;
   $query = mysqli_prepare($link, "DELETE FROM `wk11`.`products` WHERE `products`.`id` = ?");
   mysqli_stmt_bind_param($query, 'i', $id);
   mysqli_stmt_execute($query);
   mysqli_stmt_close($query);
}

/**
 * updates entry
 */
function update_product($id, $name, $price){
   global $link;
   $query = mysqli_prepare($link, "UPDATE  `wk11`.`products` SET  `name` = ?, `price` = ? WHERE `products`.`id` = ?");
   mysqli_stmt_bind_param($query, 'sii', $name, $price, $id);
   mysqli_stmt_execute($query);
   mysqli_stmt_close($query);
}
/**
 * finds a single product by id
 */
function find_product($id){
   global $link;
   $prod = array();
   
   $query = mysqli_prepare($link, "SELECT * FROM products WHERE `products`.`id` = ?");
   mysqli_stmt_bind_param($query, 'i', $id);
   mysqli_stmt_execute($query);
   
   mysqli_stmt_bind_result($query, $id, $name, $price);
   while(mysqli_stmt_fetch($query)){
      $prod['id'] = $id;
      $prod['name'] = $name;
      $prod['price'] = $price;
   }
   
   mysqli_stmt_close($query);
   
   return $prod;
   
}