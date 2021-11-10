<?php
session_start();

include_once('globals.php');
$name = $_POST['name'];

if (isset($_SESSION['user']) || $name == "")
   $u = unserialize($_SESSION['user']);
else {
   $_SESSION['message'] = "Expired or invalid session.";
   header("Location: index.php");
   exit;
}

// create new STIX_model object
$stix_model = new STIXModel();
$id_stix_model = $stix_model->insert($name,$u->getIdUser());

header("Location: dashboard.php");
exit;
?>