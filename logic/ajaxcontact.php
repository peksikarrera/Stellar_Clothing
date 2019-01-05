<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include "connection.php";

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';
if(isset($_POST['bsubmit'])){
    $name = $_POST['tbname'];
    $tbemail = $_POST['tbemail'];
    if(isset($_POST['message'])) $message = $_POST['message'];
    $regName = "/^[A-Z][a-z]{2,12}(\s[A-Z][a-z]{3,22})+$/";
    $errors = [];
    if(!preg_match($regName,$name)){
        $errors[] = "Wrong name format";
    }
    if(!filter_var($tbemail,FILTER_VALIDATE_EMAIL)){
        $errors[] = "Wrong mail format";
    }
    if(empty($message)){
        $errors[] = "Message box is blank";
    }
    if(count($errors)>0){
        http_response_code(422);
    }
    else{
        $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
        try {
            //Server settings                           // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'testphpnalog@gmail.com';                 // SMTP username
            $mail->Password = 'sifrazaphp';                           // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to

            //Recipients
            $mail->setFrom('testphpnalog@gmail.com', 'sifrazaphp');
            $mail->addAddress('testphpnalog@gmail.com', 'Test nalog');     // Add a recipient

            //Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = 'New message from ' . $tbemail . ' sent via contact form';
            $mail->Body    = 'Message : ' . $message;

            $mail->send();
            echo 'Message has been sent';
            http_response_code(200);
        } catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
            http_response_code(500);
        }
    }
}