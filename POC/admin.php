<?php
/*
open-isca
Phishing Email Generator/Security Awareness Training Tool
Created by Stan Hammond
2015

File: admin.php 
This is the initial entry point to generate a phishing email.  You can select from three different email types.
This is proof of concept code. There are features available here that are not available in the main code.
1. Emails that are generated contain the browser and IP address that made the request.  This is make it known to the end user that
the email generated is "technically" a test message.
2. Email address are not stored.
3. Videos for the training are on Youtube under the Google Security channel, all video rights belong to Google.

It is assumed (we know what this means) that the code will be used correctly - for testing and training purposes and nothing malicious.
However, I am not responsible for any misuse of this code.
This code is covered by the MIT LICENSE

*/

// If an error was submitted on this form and an error code was sent back from send_phish.php, set $error_code to the returned value
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
// If error code value of 1 was returned from send_phish.php, display error message
if ($error_code == "1") {
	echo "<b><div style='color: red'>Please include both name and email address. Thank you!</div></b><br/>";
}


// Since form method is POST, values will not be returned if an error is discovered. Sorry. 
echo "<form action='send_phish.php' method='POST'>";

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
