<?php

//$lastID = $myPDO->insertUser("admin", "adminpwd");
//echo "Last ID: ".$lastID."<br>\n";

//$myPDO->dumpTable("users",1);


include_once('globals.php');

$json_file = file_get_contents("json/STIX2.1.json");
$json_data = json_decode($json_file, true);
//var_dump($json_data);
//echo "Here: ".$json_data['STIX Domain Objects'][1]['type']."<br>";

switch (json_last_error()) {
  case JSON_ERROR_NONE:
      echo ' - No errors';
  break;
  case JSON_ERROR_DEPTH:
      echo ' - Maximum stack depth exceeded';
  break;
  case JSON_ERROR_STATE_MISMATCH:
      echo ' - Underflow or the modes mismatch';
  break;
  case JSON_ERROR_CTRL_CHAR:
      echo ' - Unexpected control character found';
  break;
  case JSON_ERROR_SYNTAX:
      echo ' - Syntax error, malformed JSON';
  break;
  case JSON_ERROR_UTF8:
      echo ' - Malformed UTF-8 characters, possibly incorrectly encoded';
  break;
  default:
      echo ' - Unknown error';
  break;
}
echo "====<br>\n";
/*
foreach ($json_data as $k => $v) {
    print_r($json_data[$k]);
}
*/

// CONSTANTS
$PROPERTY = 0;
$TYPE = 1;

$SDO_count = count($json_data['STIX Domain Objects']);
echo "SDO count: ".$SDO_count."<br>";
for ($i = 0; $i < $SDO_count; $i++) {
   //echo "i=".$i." -> ".$json_data['STIX Domain Objects'][$i]['type']."<br>";
}

$SRO_count = count($json_data['STIX Relationship Objects']);
echo "SRO count: ".$SRO_count."<br>";
for ($i = 0; $i < $SRO_count; $i++) {
   //echo "i=".$i." -> ".$json_data['STIX Relationship Objects'][$i]['type']."<br>";
}

$arr_types = array();

// get all keys from JSON
$root_keys = array_keys($json_data);

// find all property types:
foreach ($root_keys as $rkey => $rvalue) {
   $c_count = count($json_data[$rvalue]);
   echo "For '".$rvalue."', there are ".$c_count." items.<br>";
   for ($i = 0; $i < $c_count; $i++) {
      // process the common_properties...
      $keys = array_keys($json_data[$rvalue][$i]['common_properties']);
      foreach ($keys as $key => $value) {
         $common_property = $json_data[$rvalue][$i]['common_properties'][$value][$TYPE];
         $arr_types[$common_property] = $common_property;
      }
      // process the specific_properties...
      $keys = array_keys($json_data[$rvalue][$i]['specific_properties']);
      foreach ($keys as $key => $value) {
         $specific_property = $json_data[$rvalue][$i]['specific_properties'][$value][$TYPE];
         $arr_types[$specific_property] = $specific_property;
      }
   }
}

/*
echo "All property types: <br>";
for ($i = 0; $i < $SDO_count; $i++) {
   $sdo = $json_data['STIX Domain Objects'][$i]['type'];
   $count_common = count($json_data['STIX Domain Objects'][$i]['common_properties']);
   //echo "property (sdo=".$sdo.") -> ".$count_common."<br>";
   $keys = array_keys($json_data['STIX Domain Objects'][$i]['common_properties']);
   foreach ($keys as $key => $value) {
      $common_property = $json_data['STIX Domain Objects'][$i]['common_properties'][$value][$TYPE];
      //echo "&nbsp;&nbsp;property -> ".$value.", type=".$common_property."<br>";
      $arr_types[$common_property] = $common_property;
   }
}
*/

// Print hash
ksort($arr_types);
$i = 1;
foreach ($arr_types as $key => $value) {
   echo "i=".($i++)." key=".$key."<br>";
}


/*
$field_mandate = $json_data['STIX Domain Objects'][0]['common_properties']['type'][$PROPERTY];
$type = $json_data['STIX Domain Objects'][0]['common_properties']['type'][$TYPE];
echo "Field mandate: ".$field_mandate.". Type: ".$type."<br>";

$Cybox_count = count($json_data['STIX Cyber-observable Objects']);
echo "Cybox count: ".$Cybox_count."<br>";


var_dump($json_data['STIX Domain Objects'][0]['common_properties']['type']);
*/




?>
