<?php
session_start();

include_once('globals.php');
$email = $_GET['email'];
$link = $_GET['link'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
   $_SESSION['message'] = "Invalid email.";
   $_SESSION['panel_color'] = "w3-pale-red";
   header("Location: index.php");
   exit;
}
else {
   $newUser = new User();
   $id_user = $newUser->recoveryLink($email, $link);
   if ($id_user != -1) {
      header("Location: login_update_user.php?email=".$email."&link=".$link);
      exit;
   } else {
      $_SESSION['message'] = "Invalid recovery link.";
      $_SESSION['panel_color'] = "w3-pale-red";
      header("Location: index.php");
      exit;
   }
}
?>