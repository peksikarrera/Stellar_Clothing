<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include "connection.php";

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if(isset($_POST['createaccbtn'])){
    $reName = '/^[A-Z][a-z]{2,15}$/';
    $reSurname = '/^[A-Z][a-z]{3,20}$/';
    $rePasswd = '/^[0-9A-z\!\#\$\%\^\&\*\/]{7,20}$/';
    $rehomeaddr = '/^[A-Z][A-Za-z0-9\s\-\/]{5,45}$/';
    $rezip = '/^[0-9\-\sA-z]{3,12}$/';
    $rephnumber = '/^\+[0-9]{8,18}$/';
    $repaynumber = '/^[0-9]{15,19}$/';
    $recsc = '/^[0-9]{3,5}$/';
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['mail'];
    $password = md5($_POST['password']);
    $homeaddress = $_POST['homeaddress'];
    $zipcode = $_POST['zipcode'];
    $phonenumber = $_POST['phonenumber'];
    $csc = $_POST['csc'];
    $paymentcardnumber = $_POST['paymentcardnumber'];
    $day = isset($_POST['day']) ? $_POST['day'] : '';
    $month = isset($_POST['month']) ? $_POST['month'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';
    $country = isset($_POST['country']) ? $_POST['country'] : '';
    $errors = [];
    if(!preg_match($reName,$firstname)){
        $errors[] = "Wrong first name format";
    }
    if(!preg_match($reSurname,$lastname)){
        $errors[] = "Wrong surname format";
    }
    if(!preg_match($rePasswd,$_POST['password'])){
        $errors[] = "Wrong password format";
    }
    if(!preg_match($rehomeaddr,$homeaddress)){
        $errors[] = "Wrong home address format";
    }
    if(!preg_match($rephnumber,$phonenumber)){
        $errors[] = "Wrong phone number format";
    }
    if(!preg_match($repaynumber,$paymentcardnumber)){
        $errors[] = "Wrong payment card number format";
    }
    if(!preg_match($rezip,$zipcode)){
        $errors[] = "Wrong zip code format";
    }
    if(!preg_match($recsc,$csc)){
        $errors[] = "Wrong csc format";
    }
    if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
        $errors[] = "Wrong mail format";
    }
    if(empty($day) ||  empty($month) || empty($year) || empty($country)){
        $errors[] = "A date of birth and a country are not selected";
    }
    if(count($errors)){
        http_response_code(422);
    }
    else{
        $datetime = date("Y-m-d H:i:s");
        $random = $email . md5($datetime);
        $query = "INSERT INTO users(`user_id`,`firstname`,`lastname`,`email`,`password`,`phonenumber`,`time`,`active`,`random`,`nooftrials`,`banned`,`role_id`) VALUES('',:firstname,:lastname,:email,:password,:phonenumber,:datetime,false,:random,0,false,2)";
        $preparedquery = $connection->prepare($query);
        $preparedquery->bindParam(":firstname",$firstname);
        $preparedquery->bindParam(":lastname",$lastname);
        $preparedquery->bindParam(":email",$email);
        $preparedquery->bindParam(":password",$password);
        $preparedquery->bindParam(":random",$random);
        $preparedquery->bindParam(":datetime",$datetime);
        $preparedquery->bindParam(":phonenumber",$phonenumber);
        $query = "INSERT INTO usercarddata VALUES('',:cardnumber,:csc,(SELECT user_id FROM users WHERE email='$email'))";
        $preparedquery2 = $connection->prepare($query);
        $preparedquery2->bindParam(":cardnumber",$paymentcardnumber);
        $preparedquery2->bindParam(":csc",$csc);
        $datestring = $year."-".$month."-".$day;
        $timestamp = strtotime($datestring);
        $date = date("Y-m-d H:i:s",$timestamp);
        $query = "INSERT INTO personaluserdata VALUES('',:homeaddress,:zipcode,:country,:date,(SELECT user_id FROM users WHERE email='$email'))";
        $preparedquery3 = $connection->prepare($query);
        $preparedquery3->bindParam(":homeaddress",$homeaddress);
        $preparedquery3->bindParam(":zipcode",$zipcode);
        $preparedquery3->bindParam(":country",$country);
        $preparedquery3->bindParam(":date",$date);
        try{
            $result = $preparedquery->execute();
            $result2 = $preparedquery2->execute();
            $result3 = $preparedquery3->execute();
            if($result && $result2 && $result3){
                http_response_code(201);

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
                    $mail->addAddress($email, $firstname . " " . $lastname);     // Add a recipient

                    //Content
                    $mail->isHTML(true);                                  // Set email format to HTML
                    $mail->Subject = 'Registracija';
                    $mail->Body    = 'Click on the link to verify your account: <a href="https://stellarclothing.000webhostapp.com/logic/mail_verificiation.php?vercrypt='.$random.'">link</a>';

                    $mail->send();
                    echo 'Message has been sent';
                } catch (Exception $e) {
                    echo 'Message could not be sent. Mailer Error: ', $mail->ErrorInfo;
                    http_response_code(500);
                }
            }
            else{
                http_response_code(409);
            }
        }
        catch(PDOException $x){
            http_response_code(500);
        }
    }
}