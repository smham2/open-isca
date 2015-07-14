<?php
/*
open-isca
Phishing Email Generator/Security Awareness Training Tool
Created by Stan Hammond
2015

File: send_phish.php
This does the heavy lifting and processes the information received from admin.php
This is proof of concept code. There are features available here that are not available in the main code.
1. Emails that are generated contain the browser and IP address that made the request.  This is to make it known to the end user that
the email generated is "technically" a test message.
2. Email address are not stored.
3. Videos for the training are on Youtube under the Google Security channel, all video rights belong to Google.

It is assumed (we know what this means) that the code will be used correctly - for testing and training purposes and nothing malicious.
However, I am not responsible for any misuse of this code.
This code is covered by the MIT LICENSE

*/


// This function will run when errors are found in the data that was entered and return to the invite.php form for re-entry
// Because the form method is POST, the data entered will not be returned 
function input_error ($error_num) {

	// Change this to match the location of the admin.php file, if is different from the setting below 
	$url = "http://".$_SERVER['SERVER_NAME']."/open-isca/admin.php?&error=$error_num";
	header("Location: ".$url);
	exit();

}

// This function will return the email template, video and email subject
// combination based on the selection from the admin.php page
// The video links are for videos provided on YouTube under the Google Cyber Security Awareness channel
// That location is https://www.youtube.com/channel/UCx9mpsq9kYATCOmRQZuZZ-A and https://www.youtube.com/user/GoogleCyberSecurity/about
// All rights to the videos belong to them
function email_selection () {

	// If the Phishing example was selected, set email template, subject and video for that example.
        if ($_POST['template'] == "phish") {
                $template = file_get_contents("phish_email1.txt");
		$subject = "Update Your Information";
		$video = 'https://www.youtube.com/watch?v=f3dAQ2SvpEs';
        }
	// If the Password example was selected, set email template, subject and video for that example.
        else if ($_POST['template'] == "password") {
                $template = file_get_contents("phish_email2.txt");
		$subject = "Change Your Password";
		$video = 'https://www.youtube.com/watch?v=TqkbgqoBHTc';
        }
	// If the sensitive information example was selected, set email template, subject and video for that example.
        else if ($_POST['template'] == "sensitive") {
                $template = file_get_contents("phish_email3.txt");
		$subject = "Warning: Do Not Ignore!";
		$video = 'https://www.youtube.com/watch?v=u4GbT2Pt20M';
        }

	// Return the array containing the template, video and subject
	return array($template, $video, $subject);

}

// This function will email the phishing message
function send_email ($email_message, $email_address, $email_subject) {

	$to = "$email_address"; // Recepient's address that was extracted from form data
	$from = "no-reply@mail.com";
	$email_subject = "$email_subject";

	$headers = "From: " . $from . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\n"; // Set to send HTML formatted email
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	mail($to, $email_subject, $email_message, $headers);

}

// This function will take the $name, $email and email phish template and format the message to be sent.
function create_phish_email ($name, $email){

	// Retrieve the contents for the phishing email template from the email_selection function
	list($email_message, $video, $subject) = email_selection();

	// Get the deadline date for us to use in the phishing attempt.
	$deadline_array = getdate();
	$deadline_day = $deadline_array['mday'] + 7; // Set to 7 days, since we want a response in 7 days 
	$deadline_stamp = mktime($deadline_array['hours'],$deadline_array['minutes'],$deadline_array['seconds'],
        $deadline_array['mon'],$deadline_day,$deadline_array['year']);
	$deadline_str = date("F d, Y", $deadline_stamp);

	// Replace the deadline, name, email and video with the values of $deadline_str, $name, $email and $video respectively
	$email_message = str_replace("#DEADLINE#", $deadline_str, $email_message);
	$email_message = str_replace("#NAME#", $name, $email_message);
	$email_message = str_replace("#EMAIL#", $email, $email_message);
	$email_message = str_replace("#VIDEO#", $video, $email_message);

	// Appending the following information for proof-of-concept = IP address of requestor and browser information
	$email_message .= "<br/><br/>This request was submitted from ".$_SERVER['HTTP_USER_AGENT'];
	$email_message .= "<br/>The IP address of the computer that submitted this is ".$_SERVER['REMOTE_ADDR'];

	// Send the values of $email_message, $email and $subject to send_email function for processing
	send_email($email_message, $email, $subject);

	// Display message stating that a phishing email was sent
	echo "A phishing email was sent to $name at <b>$email</b>.<br/>";

}


// If data was entered in the name field and the email addess in the email field is valid then
// send the name and email values from admin.php to the create_phish_email function
if ($_POST['name'] && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

	create_phish_email($_POST["name"], $_POST["email"]);

}
// Or else return an error and go back to the admin.php form
else {

	input_error("1");

}

?>
