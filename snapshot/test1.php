<?php
/* Set Variables */
$host="localhost";
$db="assignment2"; 
$username="admin";
$pass="pass";

/* Attempt to connect */
$mysqli=new mysqli($host,$username,$pass,$db);
if (mysqli_connect_error()){
    die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());    
}
else
{
echo 'Success... ' . $mysqli->host_info . "\n";
}

    //$db = new mysqli('localhost', 'admin', '', 'assignment2');
	$result = array();
	//if($db->connect_errno > 0){
	//    die('Unable to connect to database [' . $db->connect_error . ']');
	//}
	/*if($result = mysqli_query($mysqli, "SELECT * FROM users")){
      print_r($result);
   }else{
      echo "fail";
   }  */ 
    $id=2;
	$query = $mysqli->prepare("SELECT * FROM `users` WHERE `id` = ?");
	$query->bind_param('i', $id);
	$query->execute();
	//$query->bind_result($id, $title, $first_name, $last_name, $email, $webaddr, $home_phone, $work_phone, $mobile_phone, $twitter, $facebook, $image, $comment);
	$query->bind_result($id, $title, $first_name, $last_name, $email, $webaddr, $home_phone, $work_phone, $mobile_phone, $twitter, $facebook, $image, $comment);
	echo "test";
	
	while($query->fetch()){
		print $id;
		$result['id']=$id;
		$result['title']=$title;
		$result['first_name']=$first_name;
		$result['last_name']=$last_name;
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
	$mysqli->close();
?>
<html>
	<head>
		<title>Test1</title>
	</head>
	<body>

		<?php print_r($result);?>
	</body>
</html>
