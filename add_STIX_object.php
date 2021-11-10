<?php
session_start();
include 'session_valid.php';
include_once 'globals.php';
global $SPEC_VERSION;

//TODO: verify if this user can see this STIX model! only from his group (read only) and from himself (read-write)!

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

$id_stix_model = $_GET['id_stix_model'];
$STIX_obj_type = $_GET['STIX_obj_type'];
$STIX_obj_type_id = $_GET['STIX_obj_type_id'];
$rvalue = $_GET['rvalue'];
$s = $_GET['s'];

$stix_model = new STIXModel();
$stix_model->get($id_stix_model);

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

<?php
$json_file = file_get_contents("json/STIX2.1.json");
$json_data = json_decode($json_file, true);

// get all keys from JSON
$root_keys = array_keys($json_data);

$spec_url = $json_data[$rvalue][$STIX_obj_type_id]["documentation-url"];
?>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- Main content: shift it to the right by 250 pixels when the sidebar is visible -->
<div class="w3-main" style="margin-left:250px">
  <div class="w3-row w3-padding-64">
  <div class="w3-content w3-container w3-left">

   <div class="w3-card-4" style="width:100%;">
    <div class="w3-container w3-teal">
      <h3>Add STIX&trade; object</h3>
    </div>
    <div class="w3-container w3-gray">
      <h6><?php echo "".$rvalue; ?>: <a href="<?php echo $spec_url;?>" title="See STIX documentation" target="_blank"><?php echo $STIX_obj_type; ?></a></h6>
    </div>

 <!-- STIX properties form from JSON -->
  <form class="w3-container" action="add_STIX_object_do.php" method="POST" name="addmodelform">
   <input type="hidden" name="id_stix_model" value="<?php echo $id_stix_model; ?>">
   <input type="hidden" name="STIX_obj_type_id" value="<?php echo $STIX_obj_type_id; ?>">
   <input type="hidden" name="STIX_obj_type" value="<?php echo $STIX_obj_type; ?>">
   <input type="hidden" name="rvalue" value="<?php echo $rvalue; ?>">
<?php
// test if image exists on folder (look 'globals.php')
$app_name= ($_SERVER['HTTP_HOST'] == "localhost" ? "cyberactive" : "");
$str_image = checkRemoteFile($_SERVER['HTTP_HOST']."/".$app_name."/images/STIX/".$STIX_obj_type.".png")==1 ? $STIX_obj_type.".png" : "none.png";
?>
    <div class="w3-container">
     <table style="width:100%;">
      <tr>
       <td>
        <img src="images/STIX/<?php echo $str_image; ?>">
       </td>
       <td><h4 class="w3-text-teal">Properties</h4></td>
      </tr>
      <tr>
       <td valign="top"><b>Common</b></td>
       <td>
        <button class="w3-btn w3-pale-green" type="button" onclick="hideDiv('common-prop')">&nbsp;&nbsp;Show/Hide&nbsp;&nbsp;</button>
        <div style="display:none" id="common-prop">
        <table id="standard-table">
         <tr>
          <th style="width:10%;">Property</th>
          <th style="width:25%;">Value</th>
          <th style="width:5%;">Type</th>
         </tr>
<?php
$REQ = 0;    // this indicates to retrieve the property's requirements (required,optional,etc) (look JSON file)
$TYPE = 1;   // retrieve type, instead of value
$keys = array_keys($json_data[$rvalue][$STIX_obj_type_id]['common_properties']);
foreach ($keys as $key => $value) {
   $p_req = $json_data[$rvalue][$STIX_obj_type_id]['common_properties'][$value][$REQ];
   $p_type = $json_data[$rvalue][$STIX_obj_type_id]['common_properties'][$value][$TYPE];
   if ($p_req == "required") $pr_req = "class=\"w3-pale-red\"";
   elseif ($p_req == "optional - deprecated") $pr_req = "class=\"w3-pale-yellow\"";
   else $pr_req = "";
?>
         <tr <?php echo $pr_req; ?>>
          <td><?php echo $value; ?></td>
          <td>
<?php
   if ($value == "type") {
?>
   <font face="Courier">"<?php echo $STIX_obj_type; ?>"</font>
   <input type="hidden" name="type" value="<?php echo $STIX_obj_type; ?>"> 
   <input type="hidden" name="property_type" value="<?php echo $STIX_obj_type; ?>"> 
<?php
   } else
   if ($value == "spec_version") {
?>
   <font face="Courier">"<?php echo $SPEC_VERSION; ?>"</font>
   <input type="hidden" name="spec_version" value="<?php echo $SPEC_VERSION; ?>"> 
   <input type="hidden" name="property_spec_version" value="<?php echo $SPEC_VERSION; ?>"> 
<?php
   } else
   if ($value == "id") {
      $uuid = $stix_model->generate_uuid($STIX_obj_type);
?>
   <font face="Courier">"<?php echo $uuid; ?>"</font>
   <input type="hidden" name="id" value="<?php echo $uuid; ?>"> 
   <input type="hidden" name="property_id" value="<?php echo $uuid; ?>"> 
<?php
   } else
   if ($value == "created") {
?>
   <font face="Courier">"<?php echo $stix_model->STIXTime(); ?>"</font>
   <input type="hidden" name="created" value="<?php echo $stix_model->STIXTime(); ?>"> 
   <input type="hidden" name="property_created" value="<?php echo $stix_model->STIXTime(); ?>"> 
<?php
   } else
   if ($value == "modified") {
?>
   <font face="Courier">"<?php echo $stix_model->STIXTime(); ?>"</font>
   <input type="hidden" name="modified" value="<?php echo $stix_model->STIXTime(); ?>"> 
   <input type="hidden" name="property_modified" value="<?php echo $stix_model->STIXTime(); ?>"> 
<?php
   } else {
?>
           <?php echo $stix_model->retrieveVocabulary($p_type, $value, $u->getIdProfile(), ""); ?>
<?php
}
?>
          </td>
          <td><?php echo $p_type;?></td>
         </tr>
<?php
}
?>
        </table>
        </div> <!-- div for the whole form to show/hide for 'common properties' -->
       </td>
      </tr>
      <tr>
       <td valign="top"><b>Specific</b></td>
       <td>      
        <button class="w3-btn w3-pale-green" type="button" onclick="hideDiv('specific-prop')">&nbsp;&nbsp;Show/Hide&nbsp;&nbsp;</button>
        <div style="<?php echo ($s!=0 && isset($_SESSION['success_msg'])) ? "display:none" : "display:block"; ?>" id="specific-prop">
        <table id="standard-table" style="width:100%;">
         <tr>
          <th style="width:10%;">Property</th>
          <th style="width:25%;">Value</th>
          <th style="width:5%;">Type</th>
         </tr>
<?php
$keys = array_keys($json_data[$rvalue][$STIX_obj_type_id]['specific_properties']);
foreach ($keys as $key => $value) {
   $p_req = $json_data[$rvalue][$STIX_obj_type_id]['specific_properties'][$value][$REQ];
   $p_type = $json_data[$rvalue][$STIX_obj_type_id]['specific_properties'][$value][$TYPE];
   if ($p_req == "required") $pr_req = "class=\"w3-pale-red\"";
   elseif ($p_req == "optional - deprecated") $pr_req = "class=\"w3-pale-yellow\"";
   else $pr_req = "";
?>
         <tr <?php echo $p_req == "optional - deprecated" ? " id=\"strikeout\"" : ""; ?> <?php echo $pr_req; ?>>
          <td><?php echo $value; ?></td>
          <td><?php echo $stix_model->retrieveVocabulary($p_type, $value, $u->getIdProfile(),""); ?></td>
          <td><?php echo $p_type;?></td>
         </tr>
<?php
}
?>
        </table>
        </div> <!-- div for the whole form to show/hide for 'specific properties' -->
       </td>
      </tr>
     </table>
    </div>
  </form>

    <div class="w3-panel">
     <p>
      <button type="button" class="w3-btn w3-teal" onclick="addmodelform.submit();">Add to my model</button></p>
    </div>

<?php
   if ($s!=0 && isset($_SESSION['success_msg'])) {
?>
   <div class="w3-panel w3-pale-green">
     <h3>Success!</h3>
     <p><?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?></p>
   </div>  
<?php
   }
?>


   </div>


  </div>
 </div>

 <!-- MAIN CONTENT -->
 <div class="w3-container">
   <h3 class="w3-text-teal">Add another object</h3>
       
   <h5>Jump to a section:</h5>
<?php
foreach ($root_keys as $rkey => $rvalue) {
   $ss = str_replace(' ', '', $rvalue);
?>
       <div class="w3-row">
         <?php echo "<a href=\"#".$ss."\">".$rvalue."</a>"; ?>
       </div>
<?php
}
?>

<?php
// find all property types:
$counter_elem = 0;  #counter for the 'javascript' function to hide/show the div element
foreach ($root_keys as $rkey => $rvalue) {
   $c_count = count($json_data[$rvalue]);
   $ss = str_replace(' ', '', $rvalue);
?>
      <a name="<?php echo $ss; ?>">
      <hr class="big-divider">
      <h3 class="w3-text-teal"><?php echo $rvalue; ?></h3>
       <div class="w3-container">
        <table id="standard-table" style="width:70%;">
         <tr>
          <th style="width:5%;">Image</th>
          <th style="width:15%;">Type</th>
          <th style="width:50%;">Description</th>
          <th style="width:5%;">Action</th>
         </tr>
<?php
   for ($i = 0; $i < $c_count; $i++) {
      $obj_type = $json_data[$rvalue][$i]["type"];
      $url = $json_data[$rvalue][$i]["documentation-url"];
      $description = $json_data[$rvalue][$i]["description"];
      // test if image exists on folder (look 'globals.php')
      $app_name= ($_SERVER['HTTP_HOST'] == "localhost" ? "cyberactive" : "");
      $str_image = checkRemoteFile($_SERVER['HTTP_HOST']."/".$app_name."/images/STIX/".$obj_type.".png")==1 ? $obj_type.".png" : "none.png";
?>
         <tr>
          <td valign="top"><img src="images/STIX/<?php echo $str_image; ?>"></td>
          <td align="center" valign="top"><a href="<?php echo $url; ?>" target="_blank"><?php echo $obj_type; ?></a></td>
          <td align="left" valign="top">
           <button class="w3-btn w3-pale-green" onclick="hideDiv('<?php echo "desc".($counter_elem); ?>')">&nbsp;&nbsp;Show/Hide documentation&nbsp;&nbsp;</button><div style="display:none" id="<?php echo "desc".($counter_elem); ?>"><?php echo $description; ?></div>
           <form action="add_STIX_object.php" method="GET">
            <input type="hidden" name="id_stix_model" value="<?php echo $id_stix_model; ?>">
            <input type="hidden" name="STIX_obj_type" value="<?php echo $obj_type; ?>">
            <input type="hidden" name="STIX_obj_type_id" value="<?php echo $i; ?>">
            <input type="hidden" name="rvalue" value="<?php echo $rvalue; ?>">
            <input type="hidden" name="s" value="0">
           </td>
           <td valign="top">
            <button class="w3-btn w3-teal" type="submit">Add to my model</button>
           </form>
          </td>
         </tr>
<?php
      $counter_elem++;
   }
?>
        </table>
       </div>
<?php
}
?>

 </div>
 <!-- END MAIN CONTENT -->

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