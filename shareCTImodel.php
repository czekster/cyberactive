<?php
session_start();
include 'session_valid.php';

include_once ('globals.php');

//TODO: verify if this user can see this STIX model! only from his group (read only) and from himself (read-write)!

if (!isset($_SESSION['user']) || !isset($_GET['id_stix_model'])) {
   $_SESSION['message'] = "Expired or invalid session.";
   header("Location: index.php");
   exit;
}

$u = unserialize($_SESSION['user']);

if (!$u->hasPermissionOnModel($_GET['id_stix_model'])) {
   $_SESSION['panel_color'] = "w3-pale-red";
   $_SESSION['message'] = "You don't have permission to edit this model.";
   header("Location: index.php");
   exit;
}

$id_stix_model = $_GET['id_stix_model'];
$stix_model = new STIXModel();
$stix_model->get($id_stix_model);

// JSON manip
$json_file = file_get_contents("json/STIX2.1.json");
$json_data = json_decode($json_file, true);

// get all keys from JSON
$root_keys = array_keys($json_data);

?>

<?php
include("page_header.php");
?>

<body>

<?php
include("page_navbar.php");
?>

<?php
include("page_sidebar.php");
?>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- Main content: shift it to the right by 250 pixels when the sidebar is visible -->
<div class="w3-main" style="margin-left:250px">
<?php
// get all the STIX models for this user's group
?>
  <div class="w3-row w3-padding-64">
    <div class="w3-twothird w3-container">
      <h2 class="w3-text-teal">Share CTI</h2>

      <!-- first card -->
      <div class="w3-card-4">
       <header class="w3-container w3-teal">
       <h3>Validate JSON</h3>
       </header>
        <div class="w3-container">
          <p>Model: <b><?php echo $stix_model->getName(); ?></b></p>
        </div>
        <div class="w3-container">
         <p>
         <form method="GET" action="edit_new_object.php">
          <input type="hidden" name="id_stix_model" value="<?php echo $id_stix_model?>">
          <button class="w3-btn w3-teal" type="submit">Edit model</button>
         </form>
         </p>
        </div>
      </div>
      <p/>
      
      <!-- second card -->
      <div class="w3-card-4">
       <header class="w3-container w3-teal">
       <h3>Model analysis on required parameters</h3>
       </header>
        <div class="w3-container">
<?php

$full_model_json = $stix_model->fetchModelJSON();
$json_decoded = json_decode($full_model_json, true);

$rkeys = array_keys($json_decoded['objects']);
$size_keys = count($rkeys);
for ($i = 0; $i < $size_keys; $i++) {
   $keys = array_keys($json_decoded['objects'][$i]);
   if (isset($json_decoded['objects'][$i]["type"])) {
      $type= $json_decoded['objects'][$i]['type'];
      echo "Object type <b>\"".$type."\":</b><br>\n";
      
      $reqs = getAllSTIXParamRequired($json_data, $root_keys, $type);
      $aux = "";
      foreach ($reqs as $req) {
         $found = 0;
         foreach ($keys as $k) {
            if ($k == $req) {
               $found = 1;
               break;
            }
         }
         if (!$found)
            $aux .= "&nbsp;&nbsp;<font color=\"red\">Property '".$req."' is <b>required</b> and not set.</font><br/>\n";
      }
      echo ($aux==""?"No inconsistencies were found.":$aux);
      echo "<hr>";
   } else echo "--unknown type--<br>";
}
if ($size_keys == 0) {
?>
   <p>No objects found in this model.</p>
<?php
}
?>
        </div>
      </div>

    </div>
    <!-- end of main cards -->

    <div class="w3-third w3-container">
      <p class="w3-border w3-padding-large w3-padding-32 w3-center">How to CTI?</p>
      <p class="w3-border w3-padding-large w3-padding-64 w3-center">How to create a model?</p>
      <p class="w3-border w3-padding-large w3-padding-32 w3-center">Papers on CTI</p>
      <p class="w3-border w3-padding-large w3-padding-32 w3-center">Links</p>
    </div>

  </div>

<?php
include("page_footer.php");
?>

<!-- END MAIN -->
</div>

<?php
include("page_scripts.php");
?>

</body>
</html>