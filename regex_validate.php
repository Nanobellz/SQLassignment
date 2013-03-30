<?php
//regex_validate.php 
include "dbcall.php";
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
		return 'duplicate';
	}
	else
	{
		return 'invalid';
	}
}

function isEmailOkay($email){
	global $db;
	if($db->connect_errno > 0){
	    die('Unable to connect to database [' . $db->connect_error . ']');
	}
	if($result = $db->query("SELECT * FROM members WHERE email = $email")){
		if ($result->num_rows > 0){
			return false;
	    }else{
	      	return true;
	    }
	}else{
		return true;
   	}   
}
?>