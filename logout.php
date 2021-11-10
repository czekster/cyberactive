<?php
require_once('User.php');

session_start();
if (isset($_SESSION['user'])) {
   $u = unserialize($_SESSION['user']);
   $user = new User();
   $user->get($u->getIdUser());
   $user->logout();
}
session_destroy();
$_SESSION['email'] = "";
$_SESSION['message'] = "Invalid or expired session.";

header("Location: index.php");
?>