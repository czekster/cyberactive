<?php
session_start();

require_once('globals.php');

$first = $_POST['first'];
$last = $_POST['last'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$message = $_POST['message'];

$msg = "<b>First:</b> ".substr($first, 0, 50)."<br/>".
       "<b>Last:</b> ".substr($last, 0, 50)."<br/>".
       "<b>E-mail:</b> ".substr($email, 0, 50)."<br/>".
       "<b>Phone:</b> ".substr($phone, 0, 15)."<br/>".
       "<b>Message:</b> ".substr($message, 0, 500)."<br/>";

$utils = new Utils();
$res = $utils->sendEmail($email, "Contact form (ActiVE)", $msg);
if ($res == "ok") {
   $_SESSION['message'] = "Your message was successfully sent.";
   $_SESSION['body_msg'] = $msg;
   $_SESSION['panel_color'] = "w3-pale-green";
} else {
   $_SESSION['message'] = "Error while sending message. Try again later.";
   $_SESSION['panel_color'] = "w3-pale-red";
}
header("Location: contact.php");
exit;
?>