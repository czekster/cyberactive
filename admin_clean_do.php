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
   $pdo = new MyPDO();
   $r = $pdo->truncateAllTables();
   if ($r == 1) {
      $_SESSION['message'] = "Success. All tables are now empty.";
   } else {
      $_SESSION['message'] = "Error. Try again.";
   }
   header("Location: settings.php");
   exit;
}
?>