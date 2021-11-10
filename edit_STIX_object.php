<?php
session_start();
include 'session_valid.php';
include_once 'globals.php';
global $SPEC_VERSION;

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
$id_stix_object = $_GET['id_stix_object'];
$STIX_obj_type = $_GET['STIX_obj_type'];
$STIX_obj_type_id = $_GET['STIX_obj_type_id'];
$rvalue = $_GET['rvalue'];
$s = $_GET['s'];

$stix_model = new STIXModel();
$stix_model->get($id_stix_model);

$stix_obj = new STIXObject();
$stix_obj->get($id_stix_object);

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
      <h3>Edit STIX&trade; object</h3>
    </div>
    <div class="w3-container w3-gray">
      <h6><?php echo "".$rvalue; ?>: <a href="<?php echo $spec_url;?>" title="See STIX documentation" target="_blank"><?php echo $STIX_obj_type; ?></a></h6>
    </div>

 <!-- STIX properties form from JSON -->
  <form class="w3-container" action="edit_STIX_object_do.php" method="POST" name="editmodelform">
   <input type="hidden" name="id_stix_model" value="<?php echo $id_stix_model; ?>">
   <input type="hidden" name="id_stix_object" value="<?php echo $id_stix_object; ?>">
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
      //$uuid = $stix_model->generate_uuid($STIX_obj_type);
?>
   <font face="Courier">"<?php echo $stix_obj->UUID(); ?>"</font>
   <input type="hidden" name="id" value="<?php echo $stix_obj->UUID(); ?>"> 
   <input type="hidden" name="property_id" value="<?php echo $stix_obj->UUID(); ?>"> 
<?php
   } else
   if ($value == "created") {
?>
   <font face="Courier">"<?php echo $stix_obj->getCreatedDateSTIXFmt(); ?>"</font>
   <input type="hidden" name="created" value="<?php echo $stix_obj->getCreatedDateSTIXFmt(); ?>"> 
   <input type="hidden" name="property_created" value="<?php echo $stix_obj->getCreatedDateSTIXFmt(); ?>"> 
<?php
   } else
   if ($value == "modified") {
?>
   <font face="Courier">"<?php echo $stix_obj->getModifiedDateSTIXFmt(); ?>"</font>
   <input type="hidden" name="modified" value="<?php echo $stix_obj->getModifiedDateSTIXFmt(); ?>"> 
   <input type="hidden" name="property_modified" value="<?php echo $stix_obj->getModifiedDateSTIXFmt(); ?>"> 
<?php
   } else {
?>
           <?php echo $stix_model->retrieveVocabulary($p_type, $value, $u->getIdProfile(), $stix_obj->getJSON()); ?>
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
          <td><?php echo $stix_model->retrieveVocabulary($p_type, $value, $u->getIdProfile(), $stix_obj->getJSON()); ?></td>
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

    <div class="w3-container w3-cell">
     <form action="edit_new_object.php" method="GET">
      <input type="hidden" name="id_stix_model" value="<?php echo $id_stix_model; ?>">
      <p><button type="submit" class="w3-btn w3-teal w3-center">Cancel</button></p>
     </form>
    </div>
    <div class="w3-container w3-cell">
      <p><button type="button" class="w3-btn w3-teal w3-center" onclick="editmodelform.submit();">Submit</button></p>
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