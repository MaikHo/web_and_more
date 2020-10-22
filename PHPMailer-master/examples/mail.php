<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>PHPMailer - mail() test</title>
</head>
<body>
<?php
require '../PHPMailerAutoload.php';

//Create a new PHPMailer instance
$Mailer = new PHPMailer();

//Set who the message is to be sent from
$Mailer->setFrom('from@example.com', 'First Last');

                                    //Set an alternative reply-to address
                                    $Mailer->addReplyTo('replyto@example.com', 'First Last');

//Set who the message is to be sent to
$Mailer->addAddress('whoto@example.com', 'John Doe');

//Set the subject line
$Mailer->Subject = 'PHPMailer mail() test';

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$Mailer->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));

//Replace the plain text body with one created manually
$Mailer->AltBody = 'This is a plain-text message body';

//Attach an image file
$Mailer->addAttachment('images/phpmailer_mini.gif');

//send the message, check for errors
if (!$Mailer->send()) {
    echo "Mailer Error: " . $Mailer->ErrorInfo;
} else {
    echo "Message sent!";
}
?>
</body>
</html>
