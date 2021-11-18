<?php
session_start();

include_once('globals.php');
$email = $_POST['email'];
$pass = hash('sha512', $_POST['passwd']);

$myUser = new User();
$id = $myUser->login($email, $pass);
if ($id != "") {
   
   // creates new user
   $u = new User();
   $u->get($id);
   $_SESSION['email'] = $u->getEmail();
   $_SESSION['user'] = serialize($u);
   $_SESSION['login_time_stamp'] = time();
   unset($_SESSION['message']);
} else {
   $_SESSION['message'] = "Invalid user.";
}
header("Location: index.php");
exit;
?>
