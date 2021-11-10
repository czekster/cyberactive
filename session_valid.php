<?php
include_once('globals.php');
global $SESSION_TIMEOUT;

if (!isset($_SESSION['email']) || (time() - $_SESSION["login_time_stamp"] > $SESSION_TIMEOUT)) {
	session_destroy();
   session_start();
	$_SESSION['message'] = "Invalid or expired session.";
   header("Location: index.php");
} else { //updates time (last seen) in session
   $_SESSION["login_time_stamp"] = time();
}
?>