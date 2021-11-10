<?php
session_start();

include_once('globals.php');
$id_stix_model = $_GET['id_stix_model'];

if (isset($_SESSION['user']) || $name == "")
   $u = unserialize($_SESSION['user']);
else {
   $_SESSION['message'] = "Expired or invalid session.";
   header("Location: index.php");
   exit;
}

if ($u->hasPermissionOnModel($id_stix_model)) {
   $stix_model = new STIXModel();
   $stix_model->get($id_stix_model);
   //$temp = fopen($stix_model->getName().".json", "w") or die("Unable to open file!");
   ////$filename = stream_get_meta_data($temp)['uri'];
   $contents = $stix_model->fetchModelJSON();
   $filename = stripslashes(trim($stix_model->getName())).".json"; // remove slashes and spaces (trim)
   $filename = str_replace('/', '-', $filename); //remove backslashes as well
   file_put_contents("tmp/".$filename, $contents);

   if (file_exists("tmp/".$filename)) {
      header('Content-Description: File Transfer');
      //header('Content-Type: application/octet-stream');
      header('Content-Type: application/json');
      header('Content-Disposition: attachment; filename="' . basename("tmp/".$filename) . '"');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      //header('Content-Length: ' . filesize($filename));
      readfile($filename);
      exit;
   } else echo "File does not exist in the server";

} else {
   $_SESSION['panel_color'] = "w3-pale-red";
   $_SESSION['message'] = "You don't have permission to edit this model.";
   header("Location: index.php");   
   exit;
}
?>