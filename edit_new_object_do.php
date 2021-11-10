<?php
session_start();

include_once('globals.php');
$id_stix_model = $_POST['id_stix_model'];
$name = $_POST['name'];

if (isset($_SESSION['user']) || $name == "")
   $u = unserialize($_SESSION['user']);
else {
   $_SESSION['message'] = "Expired or invalid session.";
   header("Location: index.php");
   exit;
}

if ($u->hasPermissionOnModel($id_stix_model)) {
   $stix_model = new STIXModel();
   $stix_model->updateName($name, $id_stix_model);

   $_SESSION['panel_color'] = "w3-pale-green";
   $_SESSION['message'] = "Model name is now '".$name."'.";
   header("Location: edit_new_object.php?id_stix_model=".$id_stix_model);
   exit;
} else {
   $_SESSION['panel_color'] = "w3-pale-red";
   $_SESSION['message'] = "You don't have permission to edit this model.";
   header("Location: edit_new_object.php?id_stix_model=".$id_stix_model);   
   exit;
}
?>