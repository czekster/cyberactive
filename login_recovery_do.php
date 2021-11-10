<?php
session_start();

include_once('globals.php');
$email = $_POST['email'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
   $_SESSION['message'] = "Invalid email.";
   $_SESSION['panel_color'] = "w3-pale-red";
   header("Location: index.php");
   exit;
}
else {
   $newUser = new User();
   $r = $newUser->recoverPwd($email);
   if ($r == "no-user") {
      $_SESSION['message'] = "This user was not found in our database. Try using other username.";
      $_SESSION['panel_color'] = "w3-pale-red";
      header("Location: login_recovery.php");
      exit;
   } else
   if ($r == "ok") {      
      $_SESSION['message'] = "A link with a new password was sent to ".$email." Try to log in again, please (also, check your 'SPAM' folder).";
      $_SESSION['panel_color'] = "w3-pale-green";
      header("Location: login_recovery.php");
      exit;
   } else {
      $_SESSION['message'] = "Error while sending email to ".$email." [msg='".$r."'].";
      $_SESSION['panel_color'] = "w3-pale-red";
      header("Location: login_recovery.php");
      exit;
   }
}
?>