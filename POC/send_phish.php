<?php

// This function will run when errors are found in the data that was entered and return to the invite.php form for re-entry
// Because the form method is POST, the data entered will not be returned 
function input_error ($error_num) {

	$url = "http://".$_SERVER['SERVER_NAME']."/dev/project/admin.php?&error=$error_num";
	header("Location: ".$url);
	exit();

}

// This function will return the email template, video and email subject
// combination based on the selection from the admin.php page
function email_selection () {

        if ($_POST['template'] == "phish") {
                $template = file_get_contents("phish_email1.txt");
		$subject = "Update Your Information";
		$video = 'https://www.youtube.com/watch?v=f3dAQ2SvpEs';
        }
        else if ($_POST['template'] == "password") {
                $template = file_get_contents("phish_email2.txt");
		$subject = "Change Your Password";
		$video = 'https://www.youtube.com/watch?v=TqkbgqoBHTc';
        }
        else if ($_POST['template'] == "sensitive") {
                $template = file_get_contents("phish_email3.txt");
		$subject = "Warning: Do Not Ignore!";
		$video = 'https://www.youtube.com/watch?v=u4GbT2Pt20M';
        }

	return array($template, $video, $subject);

}

// This function will email the invitation to the invited guest 
function send_email ($email_message, $email_address, $email_subject) {

	$to = "$email_address"; // Recepient's address that was extracted from form data or CSV file
	$from = "no-reply@mail.com";
	$email_subject = "$email_subject";

	$headers = "From: " . $from . "\r\n";
	$headers .= 'MIME-Version: 1.0' . "\n"; // Set to send HTML formatted email
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

	mail($to, $email_subject, $email_message, $headers);

}

// This function will take the $guest_name, $guest_email and email invitation template and format the message to send to the invited guest.
function create_phish_email ($name, $email){

	// Retrieve the contents for the invitation template from the email_selection function
	list($email_message, $video, $subject) = email_selection();

	// Get the deadline date for us to receive an RSVP for the party.
	$deadline_array = getdate();
	$deadline_day = $deadline_array['mday'] + 7; // Set to 7 days, since we want a response in 7 days 
	$deadline_stamp = mktime($deadline_array['hours'],$deadline_array['minutes'],$deadline_array['seconds'],
        $deadline_array['mon'],$deadline_day,$deadline_array['year']);
	$deadline_str = date("F d, Y", $deadline_stamp);

	// Replace the deadline, name and email with the values of $deadline_str, $guest_name and $guest_email respectively
	$email_message = str_replace("#DEADLINE#", $deadline_str, $email_message);
	$email_message = str_replace("#NAME#", $name, $email_message);
	$email_message = str_replace("#EMAIL#", $email, $email_message);
	$email_message = str_replace("#VIDEO#", $video, $email_message);

	// Appending the following information for proof-of-concept = IP address of requestor and browser information
	$email_message .= "<br/><br/>This request was submitted from ".$_SERVER['HTTP_USER_AGENT'];
	$email_message .= "<br/>The IP address of the computer that submitted this is ".$_SERVER['REMOTE_ADDR'];

	// For this example, so as not to send emails to addresses that do not exist, I have turned off this function
	send_email($email_message, $email, $subject);

	// Display message stating that an invitation was sent
	echo "A phishing email was sent to $name at <b>$email</b>.<br/>";

	// Return the contents of the email message that will be sent to the sender's screen
	//echo "$email_message <br/><hr/>";

}


// This function will process an uploaded CSV file for data
function upload_file () {
	// Set variable $uploadfile to the tempoary location of uploaded file
	$uploadfile = $_FILES['maillist']['tmp_name'];

	// Check to see if file is of type CSV
	if ($_FILES['maillist']['type'] == 'text/csv') {
		// Set $n to 0, used to index the guest in the CSV when added to $guest_list array
		$n = 0;
		// Open uploaded file for reading
		$fp = fopen($uploadfile, 'rb');
			// While the file is open and there is no empty line (EOF)
			while ( ($line = fgets($fp)) !== false) {
				// Separate the name and email address using comma as divider from each line 
				// and store them in $name and $email
				list($name, $email) = explode(",",$line);
				// Remove any strange characters (sanitize) the email addresses in the CSV file
				$email = filter_var($email, FILTER_SANITIZE_EMAIL);
				// If the email is valid, send the $name and $email to the create_invitation function
				if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
					create_invitation($name, $email);
				}
				// Increment $n by 1
				$n++;
			}
			
	} 

}


// If a file was submitted, send file to upload_file function
if ($_FILES['maillist']['name']) {

	upload_file();

}
// Or if data was entered in the first name field, run input_data function
else if ($_POST['name'] && filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {

	create_phish_email($_POST["name"], $_POST["email"]);

}
// Or else return an error and go back to the input.php form
else {

	input_error("1");

}

?>
