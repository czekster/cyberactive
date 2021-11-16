<?php
session_start();

include_once('globals.php');
global $STIX_TYPE_SDO;
global $STIX_TYPE_SRO;
global $STIX_TYPE_SCO;
global $STIX_TYPE_SBO;

if (isset($_SESSION['user']) || $name == "")
   $u = unserialize($_SESSION['user']);
else {
   $_SESSION['message'] = "Expired or invalid session.";
   header("Location: index.php");
   exit;
}

// permission to access this object
if (isset($_POST['id_stix_model']) && !$u->hasPermissionOnModel($_POST['id_stix_model'])) {
   $_SESSION['panel_color'] = "w3-pale-red";
   $_SESSION['message'] = "You don't have permission to edit this model.";
   $id_stix_model = $_POST['id_stix_model'];
   header("Location: edit_new_object.php?id_stix_model=".$id_stix_model);
   exit;
} else {
   $arr_json = array();
   foreach($_POST as $key => $value) {
      //echo "key=".$key." value=".$value."<br>";
      if ($key == "rvalue") {
         $rvalue = $_POST['rvalue'];
         if ($rvalue == "STIX Domain Objects") {
            $id_stix_type = $STIX_TYPE_SDO;
         } else 
         if ($rvalue == "STIX Relationship Objects") {
            $id_stix_type = $STIX_TYPE_SRO;
         } else 
         if ($rvalue == "STIX Cyber-observable Objects") {
            $id_stix_type = $STIX_TYPE_SCO;
         }
      } else
      if ($key == "name")
         $name = $_POST['name'];
      else
      if ($key == "STIX_obj_type")
         $STIX_obj_type = $_POST['STIX_obj_type'];
      else
      if ($key == "id")
         $uuid = $_POST['id'];
      else
      if ($key == "id_stix_model")
         $id_stix_model = $_POST['id_stix_model'];
      else
      if ($key == "type")
         $type = $_POST['type'];
      else
      if ($key == "STIX_obj_type_id")
         $STIX_obj_type_id = $_POST['STIX_obj_type_id'];
      // name is in the table - for some elements, there is no 'name' identifier, so in this case, an empty string should be provided

      //*  processing PROPERTIES for the JSON *//
      if (preg_match("/^property_/u", $key)) {    // 'normal' properties
         $prop_identifier = explode("property_",$key);
         if (isset($value) && $value != "") {
            array_push($arr_json, "\"".$prop_identifier[1]."\": \"".$value."\"");
         }
      } else
      if (preg_match("/^sel_property_/u", $key)) {  // select field
         $prop_identifier = explode("sel_property_",$key);
         if ($_POST[$key] != "") { // this was needed because the <select> should have a <option>none</option>
            if (is_array($_POST[$key])) { // it is a multiple select
               $aux = "\"".$prop_identifier[1]."\": [\n";
               $opts = array();
               foreach ($_POST[$key] as $option) {
                  array_push($opts, $option);
               }
               for ($i = 0; $i < count($opts)-1; $i++) {
                  $aux .= "\t\t\"".trim($opts[$i])."\",\n";
               }
               $aux .= "\t\t\"".trim($opts[count($opts)-1])."\"\n\t]";
               array_push($arr_json, $aux);
            } else {
               $aux  = "\"".$prop_identifier[1]."\": ";
               $aux .= "\"".trim($_POST[$key])."\"";
               array_push($arr_json, $aux);
            }
         }
      } else
      if (preg_match("/^ta_property_/u", $key)) {  // textarea field
         $prop_identifier = explode("ta_property_",$key);
         if (isset($value) && $value != "") {
            $ta_values = explode("\n", $value);
            $aux = "";
            $aux .= "\"".$prop_identifier[1]."\": [ ";
            for ($i = 0; $i < count($ta_values)-1; $i++) {
               $aux .= "\"".trim($ta_values[$i])."\", ";
            }
            $aux .= "\"".trim($ta_values[count($ta_values)-1])."\" ]";
            array_push($arr_json, $aux);
         }
      }
   }

   // create final json from array arr_json
   $json = "{\n";
   for ($i = 0; $i < count($arr_json)-1; $i++) {
      $json .= "\t".$arr_json[$i].",\n";
   }
   $json .= "\t".trim($arr_json[count($arr_json)-1])."\n";
   $json .= "}";

   if (!isset($name))
      $name = "";
   if (!isset($uuid))
      $uuid = $STIX_obj_type."--"; // I am not particularly happy to 'solve' this problem like this...

   // create STIX object and insert 
   $obj = new STIXObject();
   $last_id = $obj->insert($id_stix_type, $name, $u->getIdProfile(), $uuid, $json, $id_stix_model);

   $_SESSION['panel_color'] = "w3-pale-green";
   $_SESSION['message'] = "STIX object added: '".$name."'.";
   header("Location: edit_new_object.php?id_stix_model=".$id_stix_model);
   exit;
}
?>
