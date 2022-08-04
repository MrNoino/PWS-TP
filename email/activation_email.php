<?php


if(!isset($_GET["email"]) || !isset($_GET["name"]) || !isset($_GET["id"])){

    return;

}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

$mail = new PHPMailer(true); 

try {
    

    $mail->SMTPDebug = SMTP::DEBUG_OFF;                  
    $mail->isSMTP();                                       
    $mail->Host       = 'smtp.gmail.com';                    
    $mail->SMTPAuth   = true;                             
    $mail->Username   = 'nuno.miguelsl25@gmail.com';                    
    $mail->Password   = 'mgdqxmjlhjapbpwo';                           
    $mail->SMTPSecure = "tls";           
    $mail->Port       = 587;   
    $mail->CharSet = 'UTF-8';    
    $mail->Encoding = 'base64';                       

    //Recipients
    $mail->setFrom('nuno.miguelsl25@gmail.com', 'Nuno Lopes');
    $mail->addAddress($_GET['email'], $_GET['name']);  

    $mail->isHTML(true);                            
    $mail->Subject = 'Ativação de conta - Watchasave';
    $mail->Body    = '<p>Caro utilizador '. $_GET['name'] .',<br><br>

                        Após se registar na nossa plataforma, é necessário ativar a conta.<br><br>
                        
                        <a href="localhost/watchasave/activate-account.php?id='. $_GET["id"] .'" target="_blank">Ative a sua conta aqui</a><br><br>
                        
                        A equipa Watchasave</p>';

    $mail->AltBody = 'Caro utilizador '. $_GET['name'] .',\n\n

                    Após se registar na nossa plataforma, é necessário ativar a conta.\n\n
                    
                    Ative a sua conta com este link: localhost/watchasave/activate-account.php?id='. $_GET["id"] .'\n\n
                    
                    A equipa Watchasave';

    $mail->send();

    header("Location: ../login.php");

} catch (Exception $e) {

    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";

}