<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

require 'funkc.php';
require 'config.php';


    $email = $_SESSION['email'];
            $mail = new PHPMailer(true);

             $mail->CharSet = 'UTF-8';
    
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'autoturgusofficial@gmail.com'; // your Gmail
            $mail->Password   = 'bohxkbnthuqxdgxd';    // 16-char app password
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('autoturgusofficial@gmail.com', 'AutoTurgus');
            $mail->addAddress($email, returnUsername($conn, $email));

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Sveikiname prisijungus prie AutoTurgus bendruomenės';
            $mail->Body    = 'Sveiki!

Džiaugiamės, kad prisijungėte prie AutoTurgus bendruomenės.
Čia galėsite lengvai pirkti, parduoti ar tiesiog domėtis automobiliais.

Jei turite klausimų – visada esame pasiruošę padėti!

Sėkmės ir smagaus naudojimosi,
AutoTurgus komanda';
            $mail->AltBody = 'Sveiki!

Džiaugiamės, kad prisijungėte prie AutoTurgus bendruomenės.
Čia galėsite lengvai pirkti, parduoti ar tiesiog domėtis automobiliais.

Jei turite klausimų – visada esame pasiruošę padėti!

Sėkmės ir smagaus naudojimosi,
AutoTurgus komanda';

            $mail->send();
header("Location: ../pages/login.php");