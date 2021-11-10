<?php
session_start();

include_once('globals.php');

$id_stix_object = $_GET['id_stix_object'];
$retracted = $_GET['retracted'];
$o_offset = $_GET['o_offset'];

if (!isset($_SESSION['user']) || !isset($_GET['id_stix_model'])) {
   $_SESSION['message'] = "Expired or invalid session.";
   header("Location: index.php");
   exit;
}
$u = unserialize($_SESSION['user']);

// permission to access this object
if (isset($_GET['id_stix_model']) && !$u->hasPermissionOnModel($_GET['id_stix_model'])) {
   $_SESSION['panel_color'] = "w3-pale-red";
   $_SESSION['message'] = "You don't have permission to edit this model.";
   header("Location: index.php");
   exit;
}

$obj = new STIXObject();
$obj->retract($id_stix_object, $retracted);

if ($retracted) {
   $_SESSION['success_msg'] = "STIX object <b>retracted</b>.";
   header("Location: dashboard.php?o_offset=0&show_retracted=0");
   exit;
} else {
   $_SESSION['success_msg'] = "STIX object <b>restored</b>.";
   header("Location: dashboard.php?o_offset=".$o_offset."&show_retracted=1");
   exit;
}
?>