<?php
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

class MailerModel extends Model {
    private static $mail;

    public function __construct() {
        self::$mail = new PHPMailer(true);
        self::$mail->isSMTP();                   // Send using SMTP
        self::$mail->Host       = SMTP_HOST;     // Set the SMTP server to send through
        self::$mail->SMTPAuth   = true;          // Enable SMTP authentication
        self::$mail->Username   = SMTP_USERNAME;
        self::$mail->Password   = SMTP_PASSWORD; // SMTP password
        self::$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        self::$mail->Port       = SMTP_PORT;     // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        self::$mail->setFrom(SMTP_MAIL, SITENAME);
        self::$mail->isHTML(true); 
	  }

    public static function sendMail(string $to, string $subject, string $body, string $alt = ''): bool {
      self::$mail->addAddress($to);
      self::$mail->Subject = $subject;
      self::$mail->Body = $body;
		  self::$mail->alt = $alt;
		
      return self::$mail->send();
	  }
}