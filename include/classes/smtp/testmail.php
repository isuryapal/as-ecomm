<?php
//phpinfo();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("class.phpmailer.php");

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->SMTPDebug = 2;

$mail->Host = "smtp-relay.gmail.com";
$mail->Port = 587;

$mail->SMTPAuth = false;
$mail->SMTPSecure = "tls";
$mail->Username = "info@deluxora.com";
$mail->Password = "15/july/56";

$mail->From = "info@deluxora.com";
$mail->FromName = "Test from contact";
$mail->AddAddress("info@deluxora.com");
//$mail->AddReplyTo("mail@mail.com");

$mail->IsHTML(true);

$mail->Subject = "Test message from server";
$mail->Body = "Test Mail<b>in bold!</b>";
//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";

if(!$mail->Send())
{
echo "Message could not be sent. <p>";
echo "Mailer Error: " . $mail->ErrorInfo;
exit;
}

echo "Message has been sent";

?>