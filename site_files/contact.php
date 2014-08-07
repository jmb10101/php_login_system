<?php
$nameErr = $emailErr = $messageErr = "";
$name = $email = $message = $phone = $howYouHeard = $howYouHeardBox = $companyName = $currentWebsite = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST["submit"] == "Submit") {

	//name is required
	if (empty($_POST["name"])) {
		$nameErr = "Name is required";
	}else{
		$name = scrub_input($_POST["name"]);
		if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
			$nameErr = "Only letters and spaces allowed";
		}
	}
	
	// email is required
	if (empty($_POST["email"])) {
		$emailErr = "Email is required";
	}else{
		$email = scrub_input($_POST["email"]);
		if (!checkEmail($email)) {
			$emailErr = "Invalid Email Format";
		}
	}
	
	// phone is not required, scrub input and save
	if (!empty($_POST["phone"])) $phone = scrub_input($_POST["phone"]);
	
	// howYouHeard is not required, scrub input and save
	if (!empty($_POST["howYouHeard"])) $howYouHeard = scrub_input($_POST["howYouHeard"]);
	
	// howYouHeardBox is not required, scrub input and save
	if (!empty($_POST["howYouHeardBox"])) $howYouHeardBox = scrub_input($_POST["howYouHeardBox"]);
	
	// companyName is not required, scrub input and save
	if (!empty($_POST["companyName"])) $companyName = scrub_input($_POST["companyName"]);
	
	// current website is not required, scrub input and save
	if (!empty($_POST["currentWebsite"])) $currentWebsite = scrub_input($_POST["currentWebsite"]);
	
	// message is required 
	if (empty($_POST["message"])) {
		$messageErr = "Message is required";
	}else{	
		$message = scrub_input($_POST["message"]);
	}	
}

?>
<h1>Contact Us</h1>

<?php 
// if user has not tried to submit, or there are input errors, display the form, else display a success message
$formSubmitted = false;
if (isset($_POST["submit"])) {
	if ($_POST["submit"] == "Submit") {
		$formSubmitted = true;
	}
}
if (!$formSubmitted || $nameErr != "" || $emailErr != "" || $messageErr != ""){
	?>
	<p>We want to help you the best way possible. Use the form below to contact us about your specific web design and development needs. Please also use the form if you have any questions regarding our company or website creation in general. Providing specific and detailed information will enable us to create the most helpful response to your situation.</p><p class="note">Note: If the form does not meet your needs or you have a more specific inquiry, please email us at <a href="mailto:admin@jjwebcreation.com">admin@jjwebcreation.com</a>. We will respond to all form submissions and emails within a 24 hour period. </p>
	<br><br>
	<h2>Contact Form</h2>
	<form method="post" action="<?php echo $_SERVER["PHP_SELF"]."?page=contact"?>">
		<span class="error">*required field</span><br><br>
		<input type="text" name="name" value="<?php echo $name ?>" placeholder="Full Name">
		<span class="error">* <?php echo $nameErr;?></span>
		<br>
		<input type="text" name="email" value="<?php echo $email ?>" placeholder="Email">
		<span class="error">* <?php echo $emailErr;?></span>
		<br>
		<input type="text" name="phone" value="<?php echo $phone ?>" placeholder="Phone Number">
		<br><br>
		<span class="contact-form-text">Please tell us how you found us:</span>
		<select id="howYouHeard" name="howYouHeard" onchange="make_howYouHeard()">
			<option value="0" selected disabled>Choose an option</option>
			<option value="Referred">Referred by someone</option>
			<option value="Search">Search engine</option>	
			<option value="Website">Link from another website</option>
			<option value="Other">Other</option>
		</select>
		
		<span id="howYouHeardLabel" class="contact-form-text"></span>
		<span id="howYouHeardBox"></span>
		
		<br>
		<input type="text" name="companyName" value="<?php echo $companyName ?>" placeholder="Company Name">
		<br>
		<input type="text" name="currentWebsite" value="<?php echo $currentWebsite ?>" placeholder="Current Website (if applicable)">
		<br><br>
		<span class="contact-form-text">How can we help you? (provide a brief description of your needs or any questions you may have)</span>
		<textarea name="message" rows="8" cols="80" placeholder="Your Message"><?php echo $message?></textarea>
		<span class="error">* <?php echo $messageErr;?></span>
		<br>
		<input type="submit" name="submit" value="Submit">
	</form>
	<?php
}else{
	if ($_POST["submit"] == "Submit") {
		// no errors detected, complete submission and notify user
		echo "<br><span class='success'>Submission Successful!</span>";
		echo "<p>Thank you for inquiry. We will review the information and respond to you as soon as possible (within 24 hours).
									 <br>If you have any further questions or comments, please email us at <a href='mailto:admin@jjwebcreation.com'>admin@jjwebcreation.com</a>.</p>";
		mail("admin@jjwebcreation.com","JJWebCreation Contact Form - ".$name, "\nname: ".$name."\nemail: ".$email."\nphone: ".$phone."\nhow they found us: ".$howYouHeard."\nspecified: ".$howYouHeardBox."\ncompany Name: ".$companyName."\ncurrent website: ".$currentWebsite."\nmessage: ".$message);
	}
}
?>

<script>
function make_howYouHeard() {
	var box = "<input type='text' name='howYouHeardBox' id='box1'><br>";
	
	switch (document.getElementById('howYouHeard').value)
	{
		case "0":
			document.getElementById('howYouHeardLabel').innerHTML = ""
			document.getElementById('howYouHeardBox').innerHTML = "";		
			break;
		case "Referred":
			document.getElementById('howYouHeardLabel').innerHTML = "Who were you referred by?"
			document.getElementById('howYouHeardBox').innerHTML = box;
			break;
		case "Search":
			document.getElementById('howYouHeardLabel').innerHTML = "Which search engine?"
			document.getElementById('howYouHeardBox').innerHTML = box;
			break;
		case "Website":
			document.getElementById('howYouHeardLabel').innerHTML = "Which website?"
			document.getElementById('howYouHeardBox').innerHTML = box;
			break;
		case "Other":
			document.getElementById('howYouHeardLabel').innerHTML = "Please specify how you found us:"
			document.getElementById('howYouHeardBox').innerHTML = box;
			break;			
	}
}
</script>

