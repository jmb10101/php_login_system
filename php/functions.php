<?php

/***** Generic php helper functions *****/


// ensure the primary session has been established
function start_primary_session() {
	if (!isset($_SESSION)) {
		session_name("primary_session");
	    session_set_cookie_params(7*24*60*60);	// session cookie will live for 1 week
		session_start();
	}
}

// scrub form input 
function scrub_input($data) {
	$data = htmlspecialchars(stripslashes(trim($data)));
	return $data;
}

// ensure email is in the correct format
function checkEmail($str) {
	return preg_match("/^[\.A-z0-9_\-\+]+[@][A-z0-9_\-]+([.][A-z0-9_\-]+)+[A-z]{1,4}$/", $str);
}

// send email
function send_mail($from,$to,$subject,$body)
{
	$headers = '';
	$headers .= "From: $from\n";
	$headers .= "Reply-to: $from\n";
	$headers .= "Return-Path: $from\n";
	$headers .= "Message-ID: <" . md5(uniqid(time())) . "@" . $_SERVER['SERVER_NAME'] . ">\n";
	$headers .= "MIME-Version: 1.0\n";
	$headers .= "Date: " . date('r', time()) . "\n";

	mail($to,$subject,$body,$headers);
}

?>