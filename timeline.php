<?php
session_start();
include 'session_valid.php';

if (isset($_SESSION['user']))
   $u = unserialize($_SESSION['user']);
else {
   $_SESSION['message'] = "Expired or invalid session.";
   header("Location: index.php");
   exit;
}
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
      <h2 class="w3-text-teal">Timeline</h2>

      <!-- first card -->
      <div class="w3-card-4">
       <header class="w3-container w3-teal">
       <h3>Events ordered by the 'modified' object parameter</h3>
       </header>
        <div class="w3-container">
<?php
$model = new STIXModel();
$mnames = $model->getSTIXModelNames($u->getIdProfile());
$colours_rr = getHTMLColours($mnames);

$all_stix_models = $u->getSTIXModels();
$size_models = count($all_stix_models);
if ($size_models > 0) {
   $events = array();
   $discarded_objs = array();
   foreach ($all_stix_models as $model) {
      $id_stix_model = $model['id_stix_model'];
      $stix_model = new STIXmodel();
      $stix_model->get($id_stix_model);
      $model_name = $stix_model->getName();
      
      // fetch its json representation
      $full_model_json = $stix_model->fetchModelJSON();
      $json_decoded = json_decode($full_model_json, true);

      $rkeys = array_keys($json_decoded['objects']);
      $size_keys = count($rkeys);
      for ($i = 0; $i < $size_keys; $i++) {
         $keys = array_keys($json_decoded['objects'][$i]);
         if (isset($json_decoded['objects'][$i]["type"])) {
            $type= $json_decoded['objects'][$i]['type'];
            if (isset($json_decoded['objects'][$i]['modified']) && $type != "relationship") { // SKIPPING relationships
               $modified = $json_decoded['objects'][$i]['modified'];
               $event_name = isset($json_decoded['objects'][$i]['name']) ? $json_decoded['objects'][$i]['name'] : "";
               $confidence = isset($json_decoded['objects'][$i]['confidence']) ? "(confidence: ".$json_decoded['objects'][$i]['confidence'].")" : "";
               array_push($events, array("time" => $modified, "model_name" => $model_name, "type" => $type, "event_name" => $event_name, "confidence" => $confidence));
            } else array_push($discarded_objs, $type);
         }
      }
   }
   echo "<p>Showing data for <b>all models</b> from your profile, ordered by 'modified' property (DESC), skipping 'relationships': </p><p/>\n";
   usort($events, function ($item1, $item2) { // order events by modified property (DESC)
      return $item2['time'] <=> $item1['time'];
   });
   foreach ($events as $key => $value) {
      $time = $value["time"];
      $newtime = gmdate("d-m-Y H:i:s", strtotime($time)); // "Y-m-d\TH:i:s\Z
      $newtime = "<font face=\"Courier new\"><b>".$newtime."</b></font>";
      $model_name = $value["model_name"];
      $type = $value["type"];
      $event_name = $value["event_name"];
      $confidence = $value["confidence"];
      $fc = $colours_rr[$model_name];
      echo "[".$newtime."] on model <font color=\"".$fc."\">'".$model_name."'</font>, <b>".$type."</b>".($event_name==""?"":", name='".$event_name."' ").($confidence==""?"":", ".$confidence)."<br>\n";
   }
   $size_discarded = count($discarded_objs);
   if ($size_discarded > 0) {
      echo "<br>The following objects didn't have a 'modified' property (and were discarded): <br>\n";
      echo "&nbsp;&nbsp;&nbsp;- ";
      for ($i = 0; $i < $size_discarded-1;$i++) {
         echo "".$discarded_objs[$i].", ";
      }
      echo "".$discarded_objs[$size_discarded-1]."";
   }
} else { // no models in this profile
?>
        <p>No models were found.</p>
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