<?php

// These two files must be included in order for this login system to function
require "php/functions.php";
require "php/connect.php";

start_primary_session();	// Start the session
	
if (isset($_SESSION["id"]) && !isset($_COOKIE["cRememberMe"]) && isset($_SESSION["rememberMe"]))
{
	if (!$_SESSION["rememberMe"]) {
		// user is logged in, but has not opted to remember credentials, and the cookie to remember credentials is not set
		// destroy the session
		$_SESSION = array();
		session_destroy();
		echo "<span class='alert-info'>Session has been Destroyed!-noCookie</span>";
	}
}

if (isset($_GET["logoff"]))	// The logoff tab has been clicked, destroy the session 
{
	$_SESSION = array();
	session_destroy();
	header("Location: index.php");	// redirect user to home page
	echo "<span class='alert-info'>Session has been Destroyed!-Logoff</span>";
}

// check to see if we need to clear error messages
if (isset($_SESSION["clear_errors"])) {
	if ($_SESSION["clear_errors"]) {	// if this is true, that means the last time this site was loaded, it was not as a result of submitting a form. We need to clear the messages so that they do not persist throughout the session.
		$_SESSION["msg"]["login-err"] = "";
		$_SESSION["msg"]["register-err"] = "";	
		$_SESSION["clear_errors"] = false;
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {			
	// check to see which form was submitted
	if ($_POST["submit"] == "Login") {
		// Login form has been submitted
		$err = array();	// error array
		$_SESSION["msg"]["register-err"] = "";
		
		if(!$_POST["loginUsername"] || !$_POST["loginPassword"])
			$err[] = "*All fields must be filled in!";
			
		if(!count($err)) {		// If there are no errors, scrub input and continue
			$_POST["loginUsername"] = mysqli_real_escape_string($link, scrub_input($_POST["loginUsername"]));
			$_POST["loginPassword"] = mysqli_real_escape_string($link, $_POST["loginPassword"]);

			
			// Attempt to find login information inside the accounts database
			$row = mysqli_fetch_assoc(mysqli_query($link, "SELECT id, username FROM users WHERE username='{$_POST['loginUsername']}' AND password='".md5($_POST['loginPassword'])."'"));
			
			if($row["username"]) {
				// Login submission was found, Login the user by setting the session variables
				$_SESSION["username"] = $row["username"];
				$_SESSION["id"] = $row["id"];
				$_SESSION["rememberMe"] = $_POST["rememberMe"];
		
				// Set the cookie to remember the user			
				setcookie("cRememberMe", $_POST["rememberMe"]);
				
			}else $err[] = "*Wrong Username and/or Password.";
		}
		
		// save error messages to session
		if($err) $_SESSION["msg"]["login-err"] = implode('<br>',$err);		
		
		// redirect user to login screen
		header("Location: index.php?page=about");
		exit;
	}elseif ($_POST["submit"] == "Register") {
		// Register form has been submitted
		$err = array(); // error array
		$_SESSION["msg"]["login-err"] = "";
		
		if(strlen($_POST["registerUsername"]) < 3 || strlen($_POST["registerUsername"]) > 20) {
			$err[] = "*Your username must be between 3 and 20 characters.";
		}
		
		if(preg_match("/[^a-z0-9\-\_\.]+/i", $_POST["registerUsername"])) {
			$err[] = "*Your username contains invalid characters.";
		}
		
		if(!checkEmail($_POST["registerEmail"] = mysqli_real_escape_string($link, scrub_input($_POST["registerEmail"])))) {
			$err[] = "*Your email is not valid.";
		}
		
		if(!count($err)) {
			// If there are no errors, attempt to create the account
			$tPassword = substr(md5($_SERVER["REMOTE_ADDR"].microtime().rand(1,100000)),0,6); //generates a random, temporary password
			
			$_POST["registerEmail"] = mysqli_real_escape_string($link, $_POST["registerEmail"]);
			$_POST["registerUsername"] = mysqli_real_escape_string($link, $_POST["registerUsername"]);			
			
			// Attempt to enter new account into the database
			// check to see if username or email has already been used
			if(mysqli_num_rows(mysqli_query($link, "SELECT * FROM users WHERE username='{$_POST['registerUsername']}' OR email='{$_POST['registerEmail']}'")) > 0) {
				$err[] = "*Username or Email already exists.";
			}else{
				mysqli_query($link, "INSERT INTO users(username, password, email, regIP, sign_up_date)
									VALUES('".$_POST['registerUsername']."',
											'".md5($tPassword)."',
											'".$_POST['registerEmail']."',
											'".$_SERVER['REMOTE_ADDR']."',
											NOW())
				");
				
				// If row was successfully entered, send the temporary password to the registered email address
				if(mysqli_affected_rows($link) == 1) {
					// send temporary password
					send_mail("doNotReply@jjwebcreation.com",
							$_POST["registerEmail"],
							"Thank you for registering an account",
							"Your temporary password is ".$tPassword);
				}
			}
		}
		
		// save error messages to session
		if($err) $_SESSION["msg"]["register-err"] = implode('<br>',$err);	
		
		header("Location: index.php?page=about");
		exit;
	}	
} else {
	// make sure error messages are cleared next time a page loads
	$_SESSION["clear_errors"] = true;
}

	
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Charset -->
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	
	<title>Basic Login System</title>
	<link rel="shortcut icon" href="images/jjweblogoicon.ico">
	
	<!-- CSS -->
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/panel.css">
	
	<!-- JS -->
	<script src="js/jquery-1.11.1.js"></script>	
	
</head>
<body>
<!-- Start Page Container -->
<div id="container">
	<!-- Start Header -->
	<?php include "layout/header.php";?>
	<!-- End Header -->

	<!-- Start Content -->
	<div id="content">
		<div id="content-fixer">
			<?php include "site_files/members.php"; ?>
		</div>
	</div>
	<!-- End Content -->

	<!-- Start Footer-->
	<?php include "layout/footer.php";?>
	<!-- End Footer-->
</div>
<!-- End Page Container -->
</body>
</html>
