<?php
// mailer file completed

require_once("vendor/autoload.php");
// require_once("data_reader.php");

try {
    // Get POST data
    $smtpUser = $_POST["smtpUser"];
    $smtpPwd = $_POST["smtpPwd"];
    $mailFrom = (empty($_POST["mailFrom"])? $smtpUser : $_POST["mailFrom"]);
    $execType = $_POST["executionType"];
    $participantFile = $_FILES["participantsList"]["name"];

    // Loop through the data to get all participants data (Name, username, and password)
    $row = 0;
    $participants = array(); // prepare the participants data storage
    if (($handle=fopen(dirname(__FILE__) . "/src/" . $participantFile,"r"))!==false) {
        while (($data=fgetcsv($handle,0,";"))!==false) {
            if (strcasecmp($data[4],"email")!=0) {
                $participants[$row] = array(
                    "username" => $data[1],
                    "fullname" => $data[2],
                    "gender" => $data[3],
                    "email" => $data[4],
                    "password" => $data[5],
                    "school" => $data[6],
                    "city" => $data[7],
                    "companion" => $data[8],
                    "level" => $data[9],
                    "class" => $data[10],
                    "language" => $data[11],
                    "category" => $data[12],
                    "session" => $data[13],
                    "challenge_day" => $data[14],
                    "challenge_date" => ((strcasecmp($data[14],"selasa")==0)? "9 November 2021" : ((strcasecmp($data[14],"rabu")==0)? "10 November 2021" : ((strcasecmp($data[14],"kamis")==0)? "11 November 2021" : ((strcasecmp($data[14],"jumat")==0)? "12 November 2021" : "13 November 2021")))),
                    "challenge_hour" => ((strcasecmp($data[13],"a")==0)? ((strcasecmp($data[14],"jumat")==0)? "07:00 - 09:00 WIB" : "08:00 - 10:00 WIB") : ((strcasecmp($data[13],"b")==0)? ((strcasecmp($data[14],"jumat")==0)? "(Tidak Tersedia)" : "10:00 - 12:00 WIB") : ((strcasecmp($data[13],"c")==0)? ((strcasecmp($data[14],"jumat")==0)? "14:00 - 16:00 WIB" : "13:00 - 15:00 WIB") : (((strcasecmp($data[14],"jumat")==0)? "(Tidak Tersedia)" : "15:30 - 17:30 WIB"))))),
                    "synch_status" => $data[15],
                    "score" => $data[16],
                    "action" => $data[17]
                );
                $row++;
            }
        }
        fclose($handle);
    }

    // Read the email template
    $mail_template_dir = dirname(__FILE__). "/mail_template.html";
    $mail_template = "";
    if (($handle=fopen($mail_template_dir,"r"))!==false) {
        $mail_template=file_get_contents($mail_template_dir);
        fclose($handle);
    }

    // Create the SMTP Transport
    $transport = (new Swift_SmtpTransport('smtp.gmail.com', 587, 'tls'))
        ->setUsername($smtpUser)
        ->setPassword($smtpPwd);
    
    // Create the Mailer using the created Transport
    $mailer = new Swift_Mailer($transport);

    // Create a message
    $message = new Swift_Message();

    // Set an "Email's Subject"
    $message->setSubject('Informasi akun peserta Tantangan Bebras biro Universitas Surabaya');

    // Set the "From address"
    $message->setFrom([$mailFrom=>'Panitia Tantangan Bebras biro UBAYA']);

    // Set the mail's encoder
    $message->setEncoder(new Swift_Mime_ContentEncoder_PlainContentEncoder('8bit'));

    echo count($participants) . " participants' data are loaded...<br><br>";

    for ($row=0; $row<count($participants); $row++) {
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
        if (strcasecmp($execType,"production")==0)
            $result = $mailer->send($message);
        echo "Email is sent to participant #" . ($row + 1) . "...<br><br>";
        echo $result;
    } // end for ($row=0; $row<count($participants); $row++) {
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
