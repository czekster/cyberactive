<?php
session_start();

include_once('globals.php');

if (isset($_SESSION['user']))
   $u = unserialize($_SESSION['user']);
else {
   $_SESSION['message'] = "Expired or invalid session.";
   header("Location: index.php");
   exit;
}

if ($u->getIdUserProfile() != 1) {  // if not admin
   session_destroy();
   $_SESSION['message'] = "Invalid or expired session.";
   header("Location: index.php");   
   exit;
} else {
   $id_user = $_POST['id_user'];
   $id_profile = $_POST['id_profile'];
   $id_user_profile = $_POST['id_user_profile'];
   
   $user = new User();
   $r = $user->updateUser($id_user, $id_profile, $id_user_profile);   
   
   if ($r == 1) {
      $_SESSION['message'] = "User assigned to profile.";
   } else {
      $_SESSION['message'] = "Error. Try again.";
   }
   header("Location: settings.php");
   exit;
}
?>