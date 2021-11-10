 <?php

require_once("globals.php");


class Utils {

   function __construct() {
   }
   
   /**
      Send an email using valid (web site) credentials.
   */
   function sendEmail($email, $subject, $message) {
      global $properties;
      require_once("./phpmailer/PHPMailerAutoload.php");
      $mail = new PHPMailer();
      $mail->IsSMTP();
      $mail->Host = $properties->getSMTPHost();
      //$mail->SMTPDebug = 2; //cz
      $mail->SMTPAuth = true; // keep it true
      $mail->SMTPOptions = array( //cz
         'ssl' => array(
         'verify_peer' => false,
         'verify_peer_name' => false,
         'allow_self_signed' => true));
      $mail->Port = 587; // keep it 587
      $mail->Mailer = "smtp"; //cz
      $mail->SMTPSecure = false; // keep it false
      $mail->SMTPAutoTLS = false; // keep it false
      $mail->Username = $properties->getSMTPUsername(); // use active account
      $mail->Password = $properties->getSMTPPassword(); // pwd
      // sender
      $mail->Sender = $properties->getSMTPSender();
      $mail->From = $properties->getSMTPSender();
      $mail->FromName = "Cyber-ActiVE Administrator";
      // recipient
      $mail->AddAddress($email); // to
      //$mail->AddAddress('recebe2@dominio.com.br'); // Define qual conta de email receberá a mensagem
      $mail->AddBCC($properties->getSMTPBCC()); // Define qual conta de email receberá uma cópia
      $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
      $mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
      $mail->Subject  = $subject;
      $mail->Body = $message;
      // send email
      $smail = $mail->Send();
      // clean recipients
      $mail->ClearAllRecipients();
      // msg
      if ($smail) {
         return "ok";
      } else {
         return $mail->ErrorInfo;
      }
   }


}

?> 