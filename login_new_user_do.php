<?php
session_start();

include_once('globals.php');
$email = $_POST['email'];
$passwd = $_POST['passwd'];
$passwd2 = $_POST['passwd2'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $passwd == "" || $passwd2 == "" || $passwd != $passwd2) {
   $_SESSION['message'] = "Invalid email or empty or invalid password.";
   $_SESSION['panel_color'] = "w3-pale-red";
   header("Location: index.php");
   exit;
}
else {
   $newUser = new User();
   $id = $newUser->newlogin($email, $passwd);
   if ($id == -1) {
      $_SESSION['message'] = "This user already exists. Try recovering your password.";
      $_SESSION['panel_color'] = "w3-pale-red";
      header("Location: login_new_user.php");
      exit;
   } else {      
      // creates new user
      $u = new User();
      $u->get($id);
      $_SESSION['email'] = $u->getEmail();
      $_SESSION['user'] = serialize($u);
      $_SESSION['login_time_stamp'] = time();
      $_SESSION['message'] = "";
      $_SESSION['panel_color'] = "";
      header("Location: index.php");
      exit;
   }
}
?>