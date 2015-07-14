<?php

// If an error was submitted on this form and an error code was sent back from send_invite.php, set $error_code to the returned value
// or else set it to 0 (no error or first load)
if ($_GET['error'] == "1") {
	$error_code = 1;
} else {
	$error_code = 0;
}

?>

<body>
<h2><span style="text-decoration:underline">Phishing Test/Security Awareness Training</span></h2>
<h3>List names and emails of individuals to receive training</h3>

<?php
// Depending on error code value, display appropriate error message
if ($error_code == "1") {
	echo "<b><div style='color: red'>Please include either the name and email address of invited guests or upload a CSV file. Thank you!</div></b><br/>";
}


// Since form method is POST, values will not be returned if an error is discovered. Sorry. 
echo "<form action='send_phish.php' method='POST' enctype='multipart/form-data'>";

echo "Email user account information: <input type='text' size='25' name='name' />\t Email: <input type='text' size='25' name='email' /> <br/>";

?>

<h3>Email Phishing Template Selection</h3>

<p>Select the template that you want to use</p>
<input type="radio" name="template" value="phish">Standard Phishing<br/>
<input type="radio" name="template" value="password">Password<br/>
<input type="radio" name="template" value="sensitive">Disclosure of Sensitive Information
<br/><br/>

<input type="submit" value="Submit" name="submit">

</form>
</body>
