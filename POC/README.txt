open-isca
------------------------------------------------------
Proof of Concept source code
Stanley Hammond
2015

This is the POC code used at dev.smham2.org to show what
open-isac can do.  It is very basic and does not have some
features in the main code.

Version 0.1 - Initial release

It is assumed (we know what this means) that the code will be used correctly - for testing and training purposes and nothing malicious.
However, I am not responsible for any misuse of this code.
This code is covered by the MIT LICENSE
------------------------------------------------------

The POC package contains 5 files:
admin.php - The entry point, this is the form for selecting the phishing emails
send_phish.php - This processes the information received from admin.php and sends the emails
phish_email1.txt - Phishing email template #1 
phish_email2.txt - Phishing email template #2
phish_email3.txt - Phishing email template #3

Prerequisites:
Web server (Apache or another HTTP server)
PHP 5 or greater

Installing:
Copy contains to a subdirectory in website's root directory (preferrably a directory called "open-isca")
If you copy it into another directory other than open-isca, you will need to change a line in the send_phish.php that handles error control.

This POC uses some freely available tools and options:
Videos are hosted on YouTube and are subject to the rights of the original owners and content creators  (Google Cyber Security Awareness)
Information on this is available at https://www.youtube.com/channel/UCx9mpsq9kYATCOmRQZuZZ-A and https://www.youtube.com/user/GoogleCyberSecurity/about
The generic logo used in the emails is freely available at http://www.logologo.com/logo.php?id=244
