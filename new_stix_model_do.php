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


// Create models from bases
$stix_model = null;
switch($_POST["base"]) {
  case "ab":
    $stix_model = new ActiveBuildingModel($name,$u->getIdUser());
    $id_stix_model = $stix_model->getId();
    break;
  
  default:
    // create new STIX_model object
    $stix_model = new STIXModel();
    $id_stix_model = $stix_model->insert($name,$u->getIdUser());
    break;
}

header("Location: dashboard.php");
exit;
?>
