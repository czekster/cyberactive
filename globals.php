<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// GLOBAL VARS
// GLOBAL CONSTANTS
$LATEST_VERSION = "0.1 (26/Oct/21)";  // latest version
$SESSION_TIMEOUT = 600; // in seconds (10 min = 600s)
$SPEC_VERSION = "2.1";  // STIX version to add to SDO/SRO/etc property

//static STIX types (look table 'stix_types')
$STIX_TYPE_SDO = 1;
$STIX_TYPE_SRO = 2;
$STIX_TYPE_SCO = 3;
$STIX_TYPE_SBO = 4;

// paging size
$LIMIT = 10;

// Include other files
require_once('PDO_utilities.php');
require_once('Profile.php');
require_once('Property.php'); // remember to rename "Property-sample.php" to "Property.php" after changing the properties to your setting.
require_once('STIXModel.php');
require_once('STIXObject.php');
include_once('ActiveBuildingModel.php');
require_once('TableRows.php');
require_once('User.php');
require_once('Utils.php');

// global variable properties, look 'Property.php'
$properties = new Property();


// GLOBAL FUNCTIONS
function checkRemoteFile($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    return curl_exec( $ch ) !== FALSE;
}

function formatDate($date) {
   return gmdate("d/m/Y H:i", strtotime($date));
}

function currentTime() {
   return gmdate("d/m/Y H:i");
}

// discover proper 'STIX_obj_type_id' value
function getSTIXObjectTypeId($root_keys, $json_data, $STIX_obj_type) {
  global $STIX_TYPE_SBO;
  
  foreach ($root_keys as $rkey => $rvalue) {
    $c_count = count($json_data[$rvalue]);
    for ($i = 0; $i < $c_count; $i++) {
      $obj_type = $json_data[$rvalue][$i]["type"];
      if ($obj_type == $STIX_obj_type)
        return $i;
    }
  }
  return $STIX_TYPE_SBO;
}

/**
   Returns an array with all STIX root types (SDO, SRO, SCO) and each of their types in an associative hash.
*/
function fetchSTIXTypes($json_data, $root_keys) {
   $arr = array();
   foreach ($root_keys as $rkey => $rvalue) {
      $c_count = count($json_data[$rvalue]);
      for ($i = 0; $i < $c_count; $i++) {
         $obj_type = $json_data[$rvalue][$i]["type"];
         $arr[$obj_type] = $rvalue;
      }
   }
   return $arr;
}

function getAllSTIXParamRequired($json_data, $root_keys, $type) {
   $REQ = 0;    // this indicates to retrieve the property's requirements (required,optional,etc) (look JSON file)
   $TYPE = 1;   // retrieve type, instead of value
   $reqs = array();
   $STIX_obj_type_id = getSTIXObjectTypeId($root_keys, $json_data, $type);
   //echo "--type=".$type." type_id=".$STIX_obj_type_id."<br>";

   // get all STIX types
   $arr_types = fetchSTIXTypes($json_data, $root_keys);
   // get the root value (SDO, SRO, SCO)
   $rvalue = $arr_types[$type];

   $keys = array_keys($json_data[$rvalue][$STIX_obj_type_id]['common_properties']);
   foreach ($keys as $key => $value) {
      $p_req = $json_data[$rvalue][$STIX_obj_type_id]['common_properties'][$value][$REQ];
      if ($p_req == "required")
         array_push($reqs, $value);
   }

   $keys = array_keys($json_data[$rvalue][$STIX_obj_type_id]['specific_properties']);
   foreach ($keys as $key => $value) {
      $p_req = $json_data[$rvalue][$STIX_obj_type_id]['specific_properties'][$value][$REQ];
      if ($p_req == "required")
         array_push($reqs, $value);
   }
   return $reqs;
}

#colours (for the timeline) - source: https://www.w3schools.com/colors/colors_groups.asp
$COLOURS = array(
   "Pink" => "#FFC0CB",
   "LightPink" => "#FFB6C1",
   "HotPink" => "#FF69B4",
   "DeepPink" => "#FF1493",
   "PaleVioletRed" => "#DB7093",
   "MediumVioletRed" => "#C71585",
   #"Purple" => "Colors	",
   "Lavender" => "#E6E6FA",
   "Thistle" => "#D8BFD8",
   "Plum" => "#DDA0DD",
   "Orchid" => "#DA70D6",
   "Violet" => "#EE82EE",
   "Fuchsia" => "#FF00FF",
   "Magenta" => "#FF00FF",
   "MediumOrchid" => "#BA55D3",
   "DarkOrchid" => "#9932CC",
   "DarkViolet" => "#9400D3",
   "BlueViolet" => "#8A2BE2",
   "DarkMagenta" => "#8B008B",
   "Purple" => "#800080",
   "MediumPurple" => "#9370DB",
   "MediumSlateBlue" => "#7B68EE",
   "SlateBlue" => "#6A5ACD",
   "DarkSlateBlue" => "#483D8B",
   "RebeccaPurple" => "#663399",
   "Indigo " => "#4B0082",
   #"Red" => "Colors	",
   "LightSalmon" => "#FFA07A",
   "Salmon" => "#FA8072",
   "DarkSalmon" => "#E9967A",
   "LightCoral" => "#F08080",
   "IndianRed " => "#CD5C5C",
   "Crimson" => "#DC143C",
   "Red" => "#FF0000",
   "FireBrick" => "#B22222",
   "DarkRed" => "#8B0000",
   #"Orange" => "Colors	",
   "Orange" => "#FFA500",
   "DarkOrange" => "#FF8C00",
   "Coral" => "#FF7F50",
   "Tomato" => "#FF6347",
   "OrangeRed" => "#FF4500",
   #"Yellow" => "Colors	",
   "Gold" => "#FFD700",
   "Yellow" => "#FFFF00",
   "LightYellow" => "#FFFFE0",
   "LemonChiffon" => "#FFFACD",
   "LightGoldenRodYellow" => "#FAFAD2",
   "PapayaWhip" => "#FFEFD5",
   "Moccasin" => "#FFE4B5",
   "PeachPuff" => "#FFDAB9",
   "PaleGoldenRod" => "#EEE8AA",
   "Khaki" => "#F0E68C",
   "DarkKhaki" => "#BDB76B",
   #"Green" => "Colors	",
   "GreenYellow" => "#ADFF2F",
   "Chartreuse" => "#7FFF00",
   "LawnGreen" => "#7CFC00",
   "Lime" => "#00FF00",
   "LimeGreen" => "#32CD32",
   "PaleGreen" => "#98FB98",
   "LightGreen" => "#90EE90",
   "MediumSpringGreen" => "#00FA9A",
   "SpringGreen" => "#00FF7F",
   "MediumSeaGreen" => "#3CB371",
   "SeaGreen" => "#2E8B57",
   "ForestGreen" => "#228B22",
   "Green" => "#008000",
   "DarkGreen" => "#006400",
   "YellowGreen" => "#9ACD32",
   "OliveDrab" => "#6B8E23",
   "DarkOliveGreen" => "#556B2F",
   "MediumAquaMarine" => "#66CDAA",
   "DarkSeaGreen" => "#8FBC8F",
   "LightSeaGreen" => "#20B2AA",
   "DarkCyan" => "#008B8B",
   "Teal" => "#008080",
   #"Cyan" => "Colors	",
   "Aqua" => "#00FFFF",
   "Cyan" => "#00FFFF",
   "LightCyan" => "#E0FFFF",
   "PaleTurquoise" => "#AFEEEE",
   "Aquamarine" => "#7FFFD4",
   "Turquoise" => "#40E0D0",
   "MediumTurquoise" => "#48D1CC",
   "DarkTurquoise" => "#00CED1",
   #"Blue" => "Colors	",
   "CadetBlue" => "#5F9EA0",
   "SteelBlue" => "#4682B4",
   "LightSteelBlue" => "#B0C4DE",
   "LightBlue" => "#ADD8E6",
   "PowderBlue" => "#B0E0E6",
   "LightSkyBlue" => "#87CEFA",
   "SkyBlue" => "#87CEEB",
   "CornflowerBlue" => "#6495ED",
   "DeepSkyBlue" => "#00BFFF",
   "DodgerBlue" => "#1E90FF",
   "RoyalBlue" => "#4169E1",
   "Blue" => "#0000FF",
   "MediumBlue" => "#0000CD",
   "DarkBlue" => "#00008B",
   "Navy" => "#000080",
   "MidnightBlue" => "#191970",
   #"Brown" => "Colors	",
   "Cornsilk" => "#FFF8DC",
   "BlanchedAlmond" => "#FFEBCD",
   "Bisque" => "#FFE4C4",
   "NavajoWhite" => "#FFDEAD",
   "Wheat" => "#F5DEB3",
   "BurlyWood" => "#DEB887",
   "Tan" => "#D2B48C",
   "RosyBrown" => "#BC8F8F",
   "SandyBrown" => "#F4A460",
   "GoldenRod" => "#DAA520",
   "DarkGoldenRod" => "#B8860B",
   "Peru" => "#CD853F",
   "Chocolate" => "#D2691E",
   "Olive" => "#808000",
   "SaddleBrown" => "#8B4513",
   "Sienna" => "#A0522D",
   "Brown" => "#A52A2A",
   "Maroon" => "#800000",
   #"White" => "Colors	",
   //"White" => "#FFFFFF",
   "Snow" => "#FFFAFA",
   "HoneyDew" => "#F0FFF0",
   "MintCream" => "#F5FFFA",
   "Azure" => "#F0FFFF",
   "AliceBlue" => "#F0F8FF",
   "GhostWhite" => "#F8F8FF",
   "WhiteSmoke" => "#F5F5F5",
   "SeaShell" => "#FFF5EE",
   "Beige" => "#F5F5DC",
   "OldLace" => "#FDF5E6",
   "FloralWhite" => "#FFFAF0",
   "Ivory" => "#FFFFF0",
   "AntiqueWhite" => "#FAEBD7",
   "Linen" => "#FAF0E6",
   "LavenderBlush" => "#FFF0F5",
   "MistyRose" => "#FFE4E1",
   #"Grey" => "Colors	",
   "Gainsboro" => "#DCDCDC",
   "LightGray" => "#D3D3D3",
   "Silver" => "#C0C0C0",
   "DarkGray" => "#A9A9A9",
   "DimGray" => "#696969",
   "Gray" => "#808080",
   "LightSlateGray" => "#778899",
   "SlateGray" => "#708090",
   "DarkSlateGray" => "#2F4F4F",
   //"Black" => "#000000"
);

/**
   Returns an associative array $ret['model_name'] = color; with N HTML colours.
*/
function getHTMLColours($mnames) {
   $ret = array();
   // colours for 20 models, then repeating (% function below)
   $aux = array("LawnGreen", "Tomato", "MediumAquaMarine", "Orchid", "Salmon", "Yellow", "YellowGreen", "Lavender", "Indigo", "DarkCyan", 
                "Khaki", "DimGray", "Chartreuse", "Crimson", "DarkOrange", "FireBrick", "LightPink", "Plum", "SpringGreen", "Turquoise");
   $auxcc = 0;
   foreach ($mnames as $key) {
      $name = $key['name'];
      $ret[$name] = $aux[$auxcc++ % 20];
   }
   return $ret;
}

?>
