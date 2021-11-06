<?php
// mailer file completed

require_once("vendor/autoload.php");
require_once("data_reader.php");

try {
    // Get POST data
    $smtpUser = $_POST["smtpUser"];
    $smtpPwd = $_POST["smtpPwd"];
    $mailFrom = (empty($_POST["mailFrom"])? $smtpUser : $_POST["mailFrom"]);
    $execType = $_POST["executionType"];
    $participantFile = $_FILES["participantsList"]["name"];

    // Create the SMTP Transport
    $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
        ->setUsername('smtpUsername@example.com')
        ->setPassword('smtpPassword');
    
    // Create the Mailer using the created Transport
    $mailer = new Swift_Mailer($transport);

    // Create a message
    $message = new Swift_Message();

    // Set an "Email's Subject"
    $message->setSubject('Informasi akun peserta Tantangan Bebras biro Universitas Surabaya');

    // Set the "From address"
    $message->setFrom(['mailFrom'=>'Panitia Tantangan Bebras biro UBAYA']);

    // Set the mail's encoder
    $message->setEncoder(new Swift_Mime_ContentEncoder_PlainContentEncoder('8bit'));

    echo count($participants) . " participants' data are loaded...<br><br>";

    // for ($row=0; $row<count($participants); $row++) {
    for ($row=0; $row<5; $row++) {

    // Set the "To address" [Use setTo method for multiple recipients, argument should be array]
    // $message->addTo('toAddress@example.com','To Recipient Name');
    $message->setTo($participants[$row]["email"], $participants[$row]["fullname"]);

    // Add "CC" address [Use setCc method for multiple recipients, argument should be array]
    // $message->addCc('ccMailAddress@example.com','CC Recipient Name');

    // Add "BCC" address [Use setBcc method for multiple recipients, argument should be array]
    // $message->addBcc('bccMailAddress@example.com','BCC Recipient Name');

    // Add an "attachment" (Also, the dynamic data can be attached)
    // $attachment = Swift_Attachment::fromPath('example.xls');
    // $attachment->setFilename('testing.xls');
    // $message->attach($attachment);

    // Add inline "Image"
    // $inline_attachment = Swift_Image::fromPath('nature.jpg');
    // $cid = $message->embed($inline_attachment);

    // Replace the mail template according to the mail recipient
    $mail_content = $mail_template;
    $mail_content = str_replace("[participant_name]", $participants[$row]["fullname"], $mail_content);
    $mail_content = str_replace("[participant_username]", $participants[$row]["username"], $mail_content);
    $mail_content = str_replace("[participant_password]", $participants[$row]["password"], $mail_content);
    $mail_content = str_replace("[challenge_day]", $participants[$row]["challenge_day"], $mail_content);
    $mail_content = str_replace("[challenge_date]", $participants[$row]["challenge_date"], $mail_content);
    $mail_content = str_replace("[challenge_hour]", $participants[$row]["challenge_hour"], $mail_content);
    $mail_content = str_replace("[participant_school]", $participants[$row]["school"], $mail_content);
    $mail_content = str_replace("[challenge_category]", $participants[$row]["category"], $mail_content);
    $mail_content = str_replace("[challenge_language]", $participants[$row]["language"], $mail_content);

    // Set the content type of the message
    $message->setContentType("text/html");

    // Set the plain-text "Body"
    // $message->setBody("Testing Swift Mailer from localhost.\nThanks, \nAdmin", "text/html");
    $message->setBody($mail_content);

    // Set a HTML-based "Body"
    // $message->addPart("This is the HTML version of the message.<br/>Example of inline image:<br/><img src='" . $cid . "' width='200' height='200'/><br/>Thanks,<br/>Admin", "text/html");

    echo $message;
    echo "Sending email to participant #" . ($row + 1) . "...<br>";
    // Send the message
    // $result = $mailer->send($message);
    echo "Email is sent to participant #" . ($row + 1) . "...<br><br>";
    echo $result;
    } // end for ($row=0; $row<count($participants); $row++) {
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
