<?php
//functions.php

$db = new mysqli('localhost', 'admin', 'pass', 'assignment2');

function saveJson ($filename, $save_object)
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
}
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
	$query = $db->prepare("SELECT * FROM `users` WHERE `id` = ?");
	$query->bind_param('i', $id);
	$query->execute();
	//$result = $query->execute();  //doesn't work.  Can't get fetch_assoc() to work with bound parameters
	//$array = $result->fetch_assoc();
	$query->bind_result($id, $title, $firstName, $lastName, $email, $webaddr, $home_phone, $work_phone, $mobile_phone, $twitter, $facebook, $image, $comment);
	while($query->fetch()){
		$result['id']=$id;
		$result['title']=$title;
		$result['firstName']=$firstName;
		$result['lastName']=$lastName;
		$result['email']=$email;
		$result['webaddr']=$webaddr;
		$result['home_phone']=$home_phone;
		$result['work_phone']=$work_phone;
		$result['mobile_phone']=$mobile_phone;
		$result['twitter']=$twitter;
		$result['facebook']=$facebook;
		$result['image']=$image;
		$result['comment']=$comment;
	}
	$query->close();
	return $result;
}

function check_login($login, $pass){
   global $db;
   $goodLogin = false;
   $query = $db->prepare("SELECT * FROM `passwords` WHERE `user` = ?");
   $query->bind_param('s', $login);
   $query->execute();
   
   $query->bind_result($user, $password);
   while($query->fetch()){
     if ($password === $pass)
     	$goodLogin=true;
   }
   
   $query->close();
   
   return $goodLogin;
   
}

function getPassword($login){
	global $db;
  	
   	$query = $db->prepare("SELECT `pass` FROM `passwords` WHERE `user` = ?");
   	$query->bind_param('s', $login);
   	$query->execute();
   	
   	$query->bind_result($password);
   	$query->fetch();
   	$query->close();
   	return $password;
}

function setPassword($login, $password){
	global $db;
	$query = $db->prepare("UPDATE `passwords` SET `pass` = ? WHERE `user` = ?");
	$query->bind_param('ss', $password, $login);
	$query->execute();
	$query->close();
}

function modifyContact($contact){
	global $db;
	$query = $db->prepare("UPDATE `assignment2`.`users` SET `title` = ?, `firstName`=?, `lastName`=?, `email`=?, `webaddr`=?, `home_phone`=?, `work_phone`=?, `mobile_phone`=?, `twitter`=?, `facebook`=?, `image`=?, `comment`=? WHERE `id` = ?");
    $query->bind_param('ssssssssssssi', $contact['title'], $contact['firstName'], $contact['lastName'], $contact['email'], $contact['webaddr'], $contact['home_phone'], $contact['work_phone'], $contact['mobile_phone'], $contact['twitter'], $contact['facebook'], $contact['image'], $contact['comment'], $contact['id']);

    $query->execute();
    $query->close();
}

function getLast (){
	global $db;
	
	if($result = $db->query("SELECT max(id) FROM users")){
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

function getContacts(){
   global $db;
   if($result = $db->query("SELECT * FROM users ORDER BY lastName ASC")){
      return $result;
   }else{
      return array();
   }   
}

function delete($id)
{
    global $db;
    $query = $db->prepare("DELETE FROM `users` WHERE `id` = ?");
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
	  </div>";
	  if ((isset($contact["firstName"]) && empty($contact["firstName"])) || (isset($contact["lastName"]) && empty($contact["lastName"])))
	    {
	      echo "<div class = 'span3 offset1 alert alert-error'>The above fields are mandatory.</div>";
	    }
	    echo "      

	  <div class='control-group' style='margin-bottom:20px'>
	    <label class='control-label' for='email' style='width:160px; float:left; margin-right: 20px; margin-top:5px; text-align:right'>Email address</label>
	    <div class='controls' style='margin-left: 180px'>
	      <div class='input-prepend'>
	        <span class='add-on'><i class='icon-envelope-alt'";
	        if (isset($validate['email']) && !$validate['email'])
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
	  if (isset($validate['email']) && !$validate['email'])
	  {
	    echo "<div class = 'span3 offset1 alert alert-error'>Not a valid email.</div>";
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
	$validate['email'] = true;
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
	  
	if (isset($fields["email"]) && !empty($fields["email"]))
	  $validate['email'] = validateEmail($fields["email"]);

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
		if ($key != "logged_in" && $key != "user_n")
        {
          unset($_SESSION[$key]);
        }
	}
}

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
}
?>