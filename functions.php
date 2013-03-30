<?php
//functions.php

$db = new mysqli('localhost', 'admin', 'pass', 'assignment2');
//$db = new mysqli('http://pteam13.gblearn.com:3306', 'pteam13_admin', '+inm]GV#3SJ*', 'pteam13_comp1230');
/*function saveJson ($filename, $save_object)
{
	$json_string = json_encode($save_object);
	$file_write = fopen($filename, "w");
	fwrite($file_write, $json_string);
	fclose($file_write);
}

function loadJson ($filename)
{
	$json_string = "";
	$open = fopen($filename, "r") or die("$filename is not a valid file.");
	while (!feof($open))
	{
		$line = fgets($open);
		$json_string = $json_string . $line;
	}
	fclose($open);
	$json_object = json_decode($json_string, true);
	return $json_object;
}*/
/*
function getID ()
{
	$file_name = 'ids.txt';
	if(!file_exists($file_name))
	{
		touch($file_name);
		$handle = fopen($file_name, 'r+');
		$id = 0;
	}
	else
	{
		$handle = fopen($file_name, 'r+');
		$id = fread($handle, filesize($file_name));
		settype($id, 'integer');
	}
	rewind($handle);
	fwrite($handle, ++$id);
	fclose($handle);
	return $id;
}

function decrementID()
{
	$file_name = 'ids.txt';
	$handle = fopen($file_name, 'r+');
	$id = fread($handle, filesize($file_name));
	settype($id, 'integer');
	rewind($handle);
	fwrite($handle, --$id);
	fclose($handle);
}*/


//fetch_array(MYSQLI_ASSOC)

function getContact ($id){
	global $db;
	$result = array();
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$query = $db->prepare("SELECT * FROM `members` WHERE `id` = ?");
	$query->bind_param('i', $id);
	$query->execute();
	//$result = $query->execute();  //doesn't work.  Can't get fetch_assoc() to work with bound parameters
	//$array = $result->fetch_assoc();
	$query->bind_result($id, $type, $title, $firstName, $lastName, $email, $password, $webaddr, $home_phone, $work_phone, $mobile_phone, $twitter, $facebook, $image, $comment, $status);
	while($query->fetch()){
		$result['id']=$id;
		$result['type']=$type;
		$result['title']=$title;
		$result['firstName']=$firstName;
		$result['lastName']=$lastName;
		$result['email']=$email;
		$result['password']=$password;
		$result['webaddr']=$webaddr;
		$result['home_phone']=$home_phone;
		$result['work_phone']=$work_phone;
		$result['mobile_phone']=$mobile_phone;
		$result['twitter']=$twitter;
		$result['facebook']=$facebook;
		$result['image']=$image;
		$result['comment']=$comment;
		$result['status']=$status;
	}
	$query->close();
	return $result;
}

function check_login($email, $pass){
   global $db;
   if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
   $goodLogin = false;
   $query = $db->prepare("SELECT `password` FROM `members` WHERE `email` = ?");
   $query->bind_param('s', $email);
   $query->execute();
   
   $query->bind_result($password);
   while($query->fetch()){
     if ($password === $pass)
     	$goodLogin=true;
   }
   
   $query->close();
   
   return $goodLogin;
   
}

function getPassword($id){
	global $db;
  	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
   	$query = $db->prepare("SELECT `password` FROM `members` WHERE `id` = ?");
   	$query->bind_param('i', $id);
   	$query->execute();
   	$query->bind_result($password);
   	$query->fetch();
   	$query->close();
   	return $password;
}

function isPending($id){
	global $db;
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	if($result = $db->query("SELECT * FROM friends WHERE status = 'pending' AND requested_member = $id")){
      	if ($result->num_rows > 0){
      		return true;
      	}else{
    		return false;
      	}
   	}else{
      	return false;
   	}   
}

function getPending($id){
	global $db;
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$pending = array();
	if($result = $db->query("SELECT * FROM friends WHERE status = 'pending' AND requested_member = $id")){
		while($row = $result->fetch_assoc()){
			$pending[] = $row;
		}
	}
	return $pending;
}

function amIPending($id){
	global $db;
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	if($result = $db->query("SELECT * FROM members WHERE status != 'pending' AND id = $id")){
		if ($result->num_rows > 0){
			return true;
	    }else{
	      	return false;
	    }
	}else{
		return false;
   	}   

}

function confirmUser($id){
	
}

function confirmFriend($requesting_member, $requested_member){
	global $db;
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$query = $db->prepare("UPDATE `friends` SET `status` = 'approved' WHERE `requesting_member` = ? AND `requested_member` = ?");
	$query->bind_param('ss', $requesting_member, $requested_member);
	$query->execute();
	$query->close();
}

function isUserPending(){
	global $db;
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	if($result = $db->query("SELECT * FROM members WHERE status = 'pending'")){
		if ($result->num_rows > 0){
			return true;
	    }else{
	      	return false;
	    }
	}else{
		return false;
   	}   
}



function getID($email){
	global $db;
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$query = $db->prepare("SELECT `id` FROM `members` WHERE `email` = ?");
	$query->bind_param('s', $email);
	$query->execute();
	$query->bind_result($id);
	$query->fetch();
	$query->close();
	return $id;
}

function searchUsers($string){
	$ids = array();
	global $db;
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	} 
	$query = $db->prepare("SELECT `id` FROM `members` WHERE `firstName` LIKE CONCAT('%', ?, '%') OR `lastName` LIKE CONCAT('%', ?, '%')");
	$query->bind_param('ss', $string, $string);
	$query->execute();
	$query->bind_result($id);
	while($query->fetch())
	{
		$ids[] = $id;
	}
	$query->close();
	return $ids;
}

function setPassword($email, $password){
	//echo $email. " " .$password;
	global $db;
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$query = $db->prepare("UPDATE `members` SET `password` = ? WHERE `email` = ?");
	$query->bind_param('ss', $password, $email);
	$query->execute();
	$query->close();
}

function modifyContact($contact){
	global $db;
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$query = $db->prepare("UPDATE `members` SET `title` = ?, `firstName`=?, `lastName`=?, `email`=?, `webaddr`=?, `home_phone`=?, `work_phone`=?, `mobile_phone`=?, `twitter`=?, `facebook`=?, `image`=?, `comment`=? WHERE `id` = ?");
    $query->bind_param('ssssssssssssi', $contact['title'], $contact['firstName'], $contact['lastName'], $contact['email'], $contact['webaddr'], $contact['home_phone'], $contact['work_phone'], $contact['mobile_phone'], $contact['twitter'], $contact['facebook'], $contact['image'], $contact['comment'], $contact['id']);

    $query->execute();
    $query->close();
}

function flagFriend($requesting_member, $requested_member){
	global $db;
	$thisworked = false;
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	$query = $db->prepare("INSERT INTO `friends` (`id`, `requesting_member`, `requested_member`) VALUES (NULL, ?, ?)");
    $query->bind_param('ii', $requesting_member, $requested_member);
    $query->execute();
    if ($db->affected_rows>0){
    	$thisworked = true;
    }
    $query->close();
    return $thisworked;
}

function getLast (){
	global $db;
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	if($result = $db->query("SELECT max(id) FROM members")){
      //$query = $db->prepare("SELECT max(id) FROM users");
      //$query->execute();
      //$query->bind_result($id);
      $id = $result->fetch_row();
      //while($query->fetch()){
      //	$result = $id;
     // }
      return $id[0];
   }else{
      return 0;
   }   
}

function getMyFriends($id){
	global $db;
	$friends = array();
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	if ($result = $db->query("SELECT * FROM `friends` WHERE (requesting_member = $id OR requested_member = $id) AND status = 'approved'")){
		while($row = $result->fetch_assoc()){
    		if (!in_array($row['requesting_member'], $friends) && $row['requesting_member'] != $id)
    			$friends[] = $row['requesting_member'];
    		if (!in_array($row['requested_member'], $friends) && $row['requested_member'] != $id)
    			$friends[] = $row['requested_member'];
		}
		
		return $friends;
	}else{
		return array();
	}
}

function getContacts(){
   global $db;
   if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
   if($result = $db->query("SELECT * FROM members ORDER BY lastName ASC")){
      return $result;
   }else{
      return array();
   }   
}

function delete($id)
{
    global $db;
    if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
    $query = $db->prepare("DELETE FROM `members` WHERE `id` = ?");
    $query->bind_param('i', $id);
    $query->execute();

    echo"<script type='text/javascript'>
      window.location = 'main_menu.php';
    </script>";
  
}

function displayContact ($id)
{
	$contact = getContact($id);
	
	if (!empty ($contact["image"]))
	{
		$image = $contact["image"];
	}
	else
	{
		$image = "http://placekitten.com/200/200";
	}
	echo "
	<div class = 'row'>
		<div class = 'span3 offset1'>
			<img src = $image alt = 'Contact Image' class='img-rounded' height = '200' width = '200'>
		</div>
		<div class = 'span8'>
		<dl class='dl-horizontal'>
		<dt>Name</dt><dd>{$contact['title']} {$contact['firstName']} {$contact['lastName']}</dd>";
		if (isset($contact['email']) || isset($contact['webaddr']))
		{
			echo "<dt>Email and Website</dt>";
		
			if (isset($contact['email']) && !empty($contact['email']))
			{
				echo "<dd>{$contact['email']}</dd>";
			}
			else
			{
				echo "<dd class='muted'>No Email Address</dd>";
			}
			if (isset($contact['webaddr']) && !empty($contact['webaddr']))
			{
				echo "<dd>{$contact['webaddr']}</dd>";
			}
			else 
			{
				echo "<dd class ='muted'>No Web Address</dd>";
			}
		}
		if (isset($contact['home_phone']) || isset($contact['work_phone']) || isset($contact['mobile_phone']))
		{
			echo "<dt>Phone</dt>";
			if (isset($contact['home_phone']) && !empty($contact['home_phone']))
			{
				echo "<dd>Home: {$contact['home_phone']}</dd>";
			}
			if (isset($contact['work_phone']) && !empty($contact['work_phone']))
			{
				echo "<dd>Work: {$contact['work_phone']}</dd>";
			}
			if (isset($contact['mobile_phone'])&& !empty($contact['mobile_phone']))
			{
				echo "<dd>Mobile: {$contact['mobile_phone']}</dd>";
			}
			if (!((isset($contact['home_phone']) && !empty($contact['home_phone']))
				|| (isset($contact['work_phone']) && !empty($contact['work_phone']))
				|| (isset($contact['mobile_phone'])&& !empty($contact['mobile_phone']))))
					echo "<dd class = 'muted'>No phone number.</dd>";
			
		}
		if (isset($contact['twitter']) && !empty($contact['twitter']) || isset($contact['facebook']) && !empty($contact['facebook']))
		{
			echo "<dt>Social Networks</dt>";
			if (isset($contact['facebook']) && !empty($contact['facebook']))
			{
				echo "<dd>Facebook: {$contact['facebook']}</dd>";
			}
			if (isset($contact['twitter']) && !empty($contact['twitter']))
			{
				echo "<dd>Twitter: {$contact['twitter']}</dd>";
			}
		}
		if (isset($contact['comment']) && !empty($contact['comment']))
		{
			echo "<dt>Comments</dt>
					<dd>{$contact['comment']}</dd>";

		}
		echo "</div>";
	echo"</div>";
	
	
}

function previewContact($id){
	$contact = getContact($id);
	if (!empty ($contact["image"]))
	{
		$image = $contact["image"];
	}
	else
	{
		$image = "http://placekitten.com/200/200";
	}
	echo "
	<div class = 'row'>
		<div class = 'span3 offset1'>
			<img src = $image alt = 'Contact Image' class='img-rounded' height = '200' width = '200'>
		</div>
		<div class = 'span8'>
		<dl class='dl-horizontal'>
		<dt>Name</dt><dd>{$contact['title']} {$contact['firstName']} {$contact['lastName']}</dd>";
		
		echo "</div>";
	echo"</div>";
}



function editContact($contact, $validate)
{
  echo"
  <div class = 'row'>
    <div class = 'span12'>
      <h3 class = 'text-center'>Please Enter Contact Information</h3>
    </div>
  </div>
  <div class = 'row'>
    <div class = 'span6'>
	  <div class='control-group' style='margin-bottom:10px'>
	    <label class='control-label' for='title' style='width:160px; float:left; margin-right: 20px; margin-top:5px; text-align:right'>Title</label>
	    <div class='controls' style='margin-left: 180px'>
	      <select name = 'title'>
	        <option value='Mr.'";
	        if (isset($contact["title"]) && ($contact["title"] == "Mr.")) echo " selected = 'selected'";
	        echo ">Mr.</option>
	        <option value='Mrs.'";
	        if (isset($contact["title"]) && ($contact["title"] == "Mrs.")) echo " selected = 'selected'";
	        echo ">Mrs.</option>
	        <option value='Ms.'";
	        if (isset($contact["title"]) && ($contact["title"] == "Ms.")) echo " selected = 'selected'";
	        echo ">Ms.</option>
	        <option value='Miss'";
	        if (isset($contact["title"]) && ($contact["title"] == "Miss")) echo " selected = 'selected'";
	        echo ">Miss</option>
	        <option value='Dr.'";
	        if (isset($contact["title"]) && ($contact["title"] == "Dr.")) echo "selected = 'selected'";
	        echo ">Dr.</option>
	      </select>
	    </div>
	  </div>";
	  
	  echo"
	  <div class='control-group' style='margin-bottom:20px'>
	    <label class='control-label' for='firstName' style='width:160px; float:left; margin-right: 20px; margin-top:5px; text-align:right'>First Name</label>
	    <div class='controls' style='margin-left: 180px; '>
	      <div class='input-prepend' style='display:inline'>
	        <span class='add-on'><i class='icon-user'";
	    if (isset($contact["firstName"]) && empty($contact["firstName"]))
	    {
	      echo "style = 'color:red'";
	    }
	    echo "></i></span>
	        <input class='span2' id='firstName' type='text' name = 'firstName'";
	        if (isset($contact["firstName"]) )
	          {
	            $firstName = $contact["firstName"];
	            echo "value = '$firstName'";
	          }
	        echo ">
	      </div>          
	    </div>
	  </div>

	  <div class='control-group' style='margin-bottom:20px'>
	    <label class='control-label' for='lastName' style='width:160px; float:left; margin-right: 20px; margin-top:5px; text-align:right'>Last Name</label>
	    <div class='controls' style='margin-left: 180px; '>
	      <div class='input-prepend' style='display:inline'>
	        <span class='add-on'><i class='icon-user'";
	        if (isset($contact["lastName"]) && empty($contact["lastName"]))
	    {
	      echo "style = 'color:red'";
	    }
	    echo "></i></span>
	        <input class='span2' id='lastName' type='text' name = 'lastName'";
	        if (isset($contact["lastName"]) )
	          {
	            $lastName = $contact["lastName"];
	            echo "value = '$lastName'";
	          }
	        echo ">
	      </div>          
	    </div>
	  </div>      

	  <div class='control-group' style='margin-bottom:20px'>
	    <label class='control-label' for='email' style='width:160px; float:left; margin-right: 20px; margin-top:5px; text-align:right'>Email address</label>
	    <div class='controls' style='margin-left: 180px'>
	      <div class='input-prepend'>
	        <span class='add-on'><i class='icon-envelope-alt'";
	        if ((isset($contact["email"]) && empty($contact["email"])) || (isset($validate['email']) && ($validate['email'] == 'invalid' || $validate['email'] =='duplicate')))
	          {
	            echo"style = 'color:red'";
	          }
	        echo "></i></span>
	        <input class='span3' id='email' type='text' name = 'email'";
	        if (isset($contact["email"]) )
	          {
	            $email = $contact["email"];
	            echo "value = '$email'";
	          }
	        echo ">
	      </div>
	    </div>
	  </div>";
	  if (isset($validate['email']) && $validate['email'] == 'invalid')
	  {
	    echo "<div class = 'span3 offset1 alert alert-error'>Not a valid email.</div>";
	  }
	  if (isset($validate['email']) && $validate['email'] == 'duplicate')
	  {
	  	echo "<div class = 'span3 offset1 alert alert-error'>This email address is already in use.</div>";
	  }
	  
	  if ((isset($contact["firstName"]) && empty($contact["firstName"])) || (isset($contact["lastName"]) && empty($contact["lastName"])) || (isset($contact["email"])) && empty($contact["email"]))
	    {
	      echo "<div class = 'span3 offset1 alert alert-error'>The above fields are mandatory.</div>";
	    }

	  echo "
	  <div class='control-group' style='margin-bottom:20px'>
	    <label class='control-label' for='webaddr' style='width:160px; float:left; margin-right: 20px; margin-top:5px; text-align:right'>Web Site</label>
	    <div class='controls' style='margin-left: 180px; '>
	      <div class='input-prepend' style='display:inline'>
	        <span class='add-on'><i class='icon-globe'></i></span>
	        <input class='span3' id='webaddr' type='text' name = 'webaddr'";
	        if (isset($contact["webaddr"]) && !empty($contact["webaddr"])) 
	          {
	            $webaddr = $contact["webaddr"];
	            echo "value = '$webaddr'";
	          }
	        echo ">
	      </div>
	    </div>
	  </div>

	  <div class='control-group' style='margin-bottom:20px'>
	    <label class='control-label' for='home_phone' style='width:160px; float:left; margin-right: 20px; margin-top:5px; text-align:right'>Phone Number</label>
	    <div class='controls' style='margin-left: 180px; '>
	      <div class='input-prepend' style='display:inline'>
	        <span class='add-on'><i class='icon-phone'";
	        if (isset($validate['phone']) && !$validate['phone'])
	          {
	            echo"style = 'color:red'";
	          }
	        echo "></i></span>
	        <input class='span2' id='home_phone' type='text' name = 'home_phone'";
	        if (isset($contact["home_phone"]) && !empty($contact["home_phone"])) 
	          {
	            $home_phone = $contact["home_phone"];
	            echo "value = '$home_phone'";
	          }
	        echo ">
	      </div>          
	    </div>
	  </div>";
	  if (isset($validate['phone']) && !$validate['phone'])
	  {
	    echo "<div class = 'span3 offset1 alert alert-error'>Not a valid phone number.</div>";
	  }
	  echo "

	  </div>
	  <div class = 'span6'>

	  <div class='control-group' style='margin-bottom:20px'>
	    <label class='control-label' for='work_phone' style='width:160px; float:left; margin-right: 20px; margin-top:5px; text-align:right'>Work Phone</label>
	    <div class='controls' style='margin-left: 180px; '>
	      <div class='input-prepend' style='display:inline'>
	        <span class='add-on'><i class='icon-phone'";
	        if (isset($validate['work']) && !$validate['work'])
	          {
	            echo"style = 'color:red'";
	          }
	        echo "></i></span>
	        <input class='span2' id='work_phone' type='text' name = 'work_phone'";
	        if (isset($contact["work_phone"])) 
	          {
	            $work_phone = $contact["work_phone"];
	            echo "value = '$work_phone'";
	          }
	        echo ">
	      </div>          
	    </div>
	  </div>";
	  if (isset($validate['work']) && !$validate['work'])
	  {
	    echo "<div class = 'span3 offset1 alert alert-error'>Not a valid phone number.</div>";
	  }
	  echo "

	  <div class='control-group' style='margin-bottom:20px'>
	    <label class='control-label' for='mobile_phone' style='width:160px; float:left; margin-right: 20px; margin-top:5px; text-align:right'>Mobile Number</label>
	    <div class='controls' style='margin-left: 180px; '>
	      <div class='input-prepend' style='display:inline'>
	        <span class='add-on'><i class='icon-mobile-phone'";
	        if (isset($validate['mobile']) && !$validate['mobile'])
	          {
	            echo"style = 'color:red'";
	          }
	        echo "></i></span>
	        <input class='span2' id='mobile_phone' type='text' name = 'mobile_phone'";
	        if (isset($contact["mobile_phone"])) 
	          {
	            $mobile_phone = $contact["mobile_phone"];
	            echo "value = '$mobile_phone'";
	          }
	        echo ">
	      </div>          
	    </div>
	  </div>";
	  if (isset($validate['mobile']) && !$validate['mobile'])
	  {
	    echo "<div class = 'span3 offset1 alert alert-error'>Not a valid phone number.</div>";
	  }
	  echo "

	  <div class='control-group' style='margin-bottom:20px'>
	    <label class='control-label' for='twitter' style='width:160px; float:left; margin-right: 20px; margin-top:5px; text-align:right'>Twitter Name</label>
	    <div class='controls' style='margin-left: 180px; '>
	      <div class='input-prepend' style='display:inline'>
	        <span class='add-on'><i class='icon-twitter'></i></span>
	        <input class='span3' id='twitter' type='text' name='twitter'";
	        if (isset($contact["twitter"])) 
	          {
	            $twitter = $contact["twitter"];
	            echo "value = '$twitter'";
	          }
	        echo ">
	      </div>          
	    </div>
	  </div>

	  <div class='control-group' style='margin-bottom:20px'>
	    <label class='control-label' for='facebook' style='width:160px; float:left; margin-right: 20px; margin-top:5px; text-align:right'>Facebook URL</label>
	    <div class='controls' style='margin-left: 180px; '>
	      <div class='input-prepend' style='display:inline'>
	        <span class='add-on'><i class='icon-facebook'></i></span>
	        <input class='span3' id='facebook' type='text' name='facebook'";
	        if (isset($contact["facebook"])) 
	          {
	            $facebook = $contact["facebook"];
	            echo "value = '$facebook'";
	          }
	        echo ">
	      </div>          
	    </div>
	  </div>

	  <div class='control-group' style='margin-bottom:20px'>
	    <label class='control-label' for='image' style='width:160px; float:left; margin-right: 20px; margin-top:5px; text-align:right'>Picture URL</label>
	    <div class='controls' style='margin-left: 180px; '>
	      <div class='input-prepend' style='display:inline'>
	        <span class='add-on'><i class='icon-picture'";
	        if (isset($validate['image']) && !$validate['image'])
	          {
	            echo"style = 'color:red'";
	          }
	        echo "></i></span>
	        <input class='span3' id='image' type='text' name='image'";
	        if (isset($contact["image"])) 
	          {
	            $image = $contact["image"];
	            echo "value = '$image'";
	          }
	        echo ">
	      </div>          
	    </div>
	  </div>";

	  if (isset($validate['image']) && !$validate['image'])
	  {
	    echo "<div class = 'alert alert-error'>Not a valid image URL.</div>";
	  }
	  echo "

	  <div class='control-group' style='margin-bottom:20px'>
	    <label class='control-label' for='comment' style='width:160px; float:left; margin-right: 20px; margin-top:5px; text-align:right'>Comment</label>
	    <div class='controls' style='margin-left: 180px; '>
	      
	        <textarea rows='5' name = 'comment'>";
	        if (isset($contact["comment"]) && !empty($contact["comment"])) 
	          {
	            $comment = $contact["comment"];
	            echo "$comment";
	          }
	        echo "</textarea>
	      
	    </div>
	  </div>
	  </div>
  </div>
  <div class = row>
	  <div class='control-group' style = 'margin-left:180px'>
	    <div class='controls'>
	      
	      <button type='submit' class='btn btn-large btn-primary'>Submit</button>
	      <a href='main_menu.php' class = 'btn btn-large'>Cancel</a>
	    </div>
	  </div>
  </div>
  </form>";
}

function validateFields($fields)
{
	$validate['email'] = null;
	$validate['phone'] = true;
	$validate['work'] = true;
	$validate['mobile'] = true;
	$validate['image'] = true;

	if (isset($fields['image']) && !empty($fields['image']))
	{
	  	// this code is based on code found at http://stackoverflow.com/questions/2280394/check-if-an-url-exists-in-php
	  	// which doesn't actually work properly because it's case sensitive on the string "Not Found" and not all HTTP
	  	// headers are version 1.1.  Some are 1.0.  It also fails to properly discriminate local files, which don't 
	  	// return HTTP headers.  So I fixed it.
		if (!file_exists($fields['image']))
		{

			$file = $fields['image'];
			$file_headers = @get_headers($file);
			if (empty($file_headers))
			{
				$validate['image'] = false;
			}
			elseif(preg_match('@HTTP/1.[0-9] 404@i', $file_headers[0])) 
			{
			    $validate['image'] = false;
			}
			else 
			{
			    $validate['image'] = true;
			}
		}
		else
		{
			$validate['image'] = true;
		}
	}
	  
	if (isset($fields["email"]) && !empty($fields["email"])){
	  $validate['email'] = validateEmail($fields["email"]);
	}

	if (isset($fields["home_phone"]) && !empty($fields["home_phone"]))
	{
	  if(validatePhone($fields["home_phone"]))
	  {
	    $validate["home_phone"] = trimPhone($fields["home_phone"]);
	  }
	  else
	  {
	    $validate['phone'] = false;
	  }
	}

	if (isset($fields["work_phone"]) && !empty($fields["work_phone"]))
	{
	  if(validatePhone($fields["work_phone"]))
	  {
	    $validate["work_phone"] = trimPhone($fields["work_phone"]);
	  }
	  else
	  {
	    $validate['work'] = false;
	  }
	}

	if (isset($fields["mobile_phone"]) && !empty($fields["mobile_phone"]))
	{
	  if (validatePhone($fields["mobile_phone"]))
	  {
	    $validate["mobile_phone"] = trimPhone($fields["mobile_phone"]);
	  }
	  else
	  {
	    $validate['mobile'] = false;
	  }
	}
	return $validate;
}

function clearContact ()
{
	foreach ($_SESSION as $key => $value) {
		if ($key != "logged_in" && $key != "user_n" && $key != "current_user")
        {
          unset($_SESSION[$key]);
        }
	}
}
/*
function sortContacts ($contacts)
{
	if (!empty($contacts))
	{
		for ($i=1; $i<=count($contacts); $i++)
		{
			$newcontacts[$i] = $contacts[$i];
		}
	}
	else
	{
		$newcontacts = null;
	}
	return $newcontacts;
}*/


function validatePhone($phoneString)
{
	// cut out everything except numbers
	$digits = preg_replace("/[^0-9]/", '', $phoneString);
	// check to see if we have 10 or 11 digits
	if (strlen($digits)==11 or strlen($digits) == 10)
	{
		// if it has 11 digits, the first digit should be 1.  Otherwise, it's not a valid #
		if (strlen($digits) == 11)
		{
			// if it has 11 digits and the first digit is 1, it's valid, so we can return true
			if (preg_match("/^1/", $digits))
			{
				return true;
			}
			else // if it has 11 digits and the first digit isn't 1, it's invalid
			{
				return false;
			}
		}
		else // if it has 10 digits
		{
			// it's probably a phone number or at least can pass for one
			return true;
		}

	}
	else // it's not the right number of digits, and so it's not a good phone #
	return false;
}

function trimPhone($phoneString) // make sure to validatePhone before attempting this!
{
	// cut out everything except numbers
	$digits = preg_replace("/[^0-9]/", '', $phoneString);
	// if it has 11 digits, the first digit should be 1, because we validated already
	if (strlen($digits) == 11)
	{
		// so let's strip it out
		$digits = preg_replace("/^1/", '', $digits);
	}
	// now we should have a nice 10-digit number with no other characters in it.
	// so let's break it up into a 3-3-4 pattern.
	preg_match("/\b([0-9]{3})([0-9]{3})([0-9]{4})\b/", $digits, $matches);
	$newphone = "(" . $matches[1] . ")" . " " . $matches[2]."-".$matches[3];
	return $newphone;

}

function validateEmail($emailString)
{
	// returns true if the email address is valid
	// a valid email address has the form: first@second.third, where third is betwen 2 and 4 characters
	// and first can include some more punctuation than second is allowed to because second is a domain

	// yes, I can actually explain this regex.  And all the other ones.

	if(preg_match("/\b[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}\b/", $emailString))
	{
		if(isEmailUnique($emailString)){
			return 'okay';
		}
		//echo 'duplicate';
		return 'duplicate';
	}
	else
	{
		return 'invalid';
	}
}

function isEmailUnique($email){
	global $db;
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	//echo $email;
	if($result = $db->query("SELECT * FROM members WHERE email LIKE '$email'")){
		if ($result->num_rows > 0){//check to see whether the email already exists
			//and if it does,
			while($row = $result->fetch_assoc()){

				// check to make sure it's not the email of the user currently being edited
				if (!isset($_SESSION['editingId'])){//if this isn't being called from edit_contact.php, editingId won't be set, therefore this is a new user, and the email is invalid
					return false; 
				}
				if ($row['id'] == $_SESSION['editingId']){//but if it's a user being edited, editingId will be set, and so we check it against the id belonging to the email in question
					return true; //if they're the same, we're editing the contact that belongs to that email
				}
				return false; // but if they're not the same, then we're trying to set the email of the user currently being edited to be the same as another user
			}
			

	    }else{
	      	return true; // if the email doesn't already exist, we're good
	    }
	}else{
		//echo "test";
		return false; // if we can't actually query the database, we'll assume that the email already exists, just to be safe
   	}   
}
?>
