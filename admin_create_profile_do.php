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
   $description = $_POST['description'];
   $r = 0;
   if ($description != "") {
      $profile = new Profile();
      $r = $profile->create($description);   
   }

   if ($r == 1) {
      $_SESSION['message'] = "Profile '".$description."' was created.";
      $_SESSION['panel_color'] = "w3-pale-green";
   } else {
      $_SESSION['message'] = "Error. Try again.";
      $_SESSION['panel_color'] = "w3-pale-red";
   }
   header("Location: settings.php");
   exit;
}
?>