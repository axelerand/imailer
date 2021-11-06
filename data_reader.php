<?php
// file to read the participant's data for "Tantangan Bebras biro UBAYA"

// Loop through the data to get all participants data (Name, username, and password)
$row = 0;
$participants = array(); // prepare the participants data storage
if (($handle=fopen(dirname(__FILE__) . "/src/Manajemen Siswa - BebrasWeb.csv","r"))!==false) {
    // Uncomment the code block below if your csv file uses semi-colon (;) as delimiter
    // while (($data=fgetcsv($handle,0,";"))!==false) {
    //     if (strcasecmp($data[4],"email")!=0) {
    //         $participants[$row] = array(
    //             "username" => $data[1],
    //             "fullname" => $data[2],
    //             "gender" => $data[3],
    //             "email" => $data[4],
    //             "password" => $data[5],
    //             "school" => $data[6],
    //             "city" => $data[7],
    //             "companion" => $data[8],
    //             "level" => $data[9],
    //             "class" => $data[10],
    //             "language" => $data[11],
    //             "category" => $data[12],
    //             "session" => $data[13],
    //             "challenge_day" => $data[14],
    //             "challenge_date" => ((strcasecmp($data[14],"selasa")==0)? "9 November 2021" : ((strcasecmp($data[14],"rabu")==0)? "10 November 2021" : ((strcasecmp($data[14],"kamis")==0)? "11 November 2021" : ((strcasecmp($data[14],"jumat")==0)? "12 November 2021" : "13 November 2021")))),
    //             "challenge_hour" => ((strcasecmp($data[13],"a")==0)? ((strcasecmp($data[14],"jumat")==0)? "07:00 - 09:00 WIB" : "08:00 - 10:00 WIB") : ((strcasecmp($data[13],"b")==0)? ((strcasecmp($data[14],"jumat")==0)? "(Tidak Tersedia)" : "10:00 - 12:00 WIB") : ((strcasecmp($data[13],"c")==0)? ((strcasecmp($data[14],"jumat")==0)? "14:00 - 16:00 WIB" : "13:00 - 15:00 WIB") : (((strcasecmp($data[14],"jumat")==0)? "(Tidak Tersedia)" : "15:30 - 17:30 WIB"))))),
    //             "synch_status" => $data[15],
    //             "score" => $data[16],
    //             "action" => $data[17]
    //         );
    //         $row++;
    //     }
    // }

    // Uncomment the code below if your csv file uses comma (,) as delimiter
    while (($data=fgetcsv($handle,0,","))!==false) {
        if (strcasecmp($data[3],"email")!=0) {
            $participants[$row] = array(
                "username" => $data[0],
                "fullname" => $data[1],
                "gender" => $data[2],
                "email" => $data[3],
                "password" => $data[4],
                "school" => $data[5],
                "city" => $data[6],
                "companion" => $data[7],
                "level" => $data[8],
                "class" => $data[9],
                "language" => $data[10],
                "category" => $data[11],
                "session" => $data[12],
                "challenge_day" => $data[13],
                "challenge_date" => ((strcasecmp($data[13],"selasa")==0)? "9 November 2021" : ((strcasecmp($data[13],"rabu")==0)? "10 November 2021" : ((strcasecmp($data[13],"kamis")==0)? "11 November 2021" : ((strcasecmp($data[13],"jumat")==0)? "12 November 2021" : "13 November 2021")))),
                "challenge_hour" => ((strcasecmp($data[12],"a")==0)? ((strcasecmp($data[13],"jumat")==0)? "07:00 - 09:00 WIB" : "08:00 - 10:00 WIB") : ((strcasecmp($data[12],"b")==0)? ((strcasecmp($data[13],"jumat")==0)? "(Tidak Tersedia)" : "10:00 - 12:00 WIB") : ((strcasecmp($data[12],"c")==0)? ((strcasecmp($data[13],"jumat")==0)? "14:00 - 16:00 WIB" : "13:00 - 15:00 WIB") : (((strcasecmp($data[13],"jumat")==0)? "(Tidak Tersedia)" : "15:30 - 17:30 WIB"))))),
                "synch_status" => $data[14],
                "score" => $data[15],
                "action" => $data[16]
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

// Replace the mail template correspond to each participant's data [email, name, username, password]
// for ($row = 1; $row<count($participants); $row++) {
//     $mail_content = $mail_template;
//     $mail_content = str_replace("[participant_name]", $participants[$row]["fullname"], $mail_content);
//     $mail_content = str_replace("[participant_username]", $participants[$row]["username"], $mail_content);
//     $mail_content = str_replace("[participant_password]", $participants[$row]["password"], $mail_content);
//     $mail_content = str_replace("[challenge_day]", $participants[$row]["challenge_day"], $mail_content);
//     $mail_content = str_replace("[challenge_date]", $participants[$row]["challenge_date"], $mail_content);
//     $mail_content = str_replace("[challenge_hour]", $participants[$row]["challenge_hour"], $mail_content);
//     $mail_content = str_replace("[participant_school]", $participants[$row]["school"], $mail_content);
//     $mail_content = str_replace("[challenge_category]", $participants[$row]["category"], $mail_content);
//     $mail_content = str_replace("[challenge_language]", $participants[$row]["language"], $mail_content);
//     echo $mail_content;
// }
?>