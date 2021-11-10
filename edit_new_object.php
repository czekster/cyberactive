<?php
session_start();
include 'session_valid.php';
include_once ('globals.php');

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
  <div class="w3-row w3-padding-64">
  <div class="w3-twothird w3-container">

   <div class="w3-card-4">
    <div class="w3-container w3-teal">
      <h3>Edit STIX&trade; model</h3>
    </div>
    <form class="w3-container" action="edit_new_object_do.php" method="POST" name="editmodelform">
     <input type="hidden" name="id_stix_model" value="<?php echo $id_stix_model; ?>">
      <p>
      <label class="w3-text-teal"><b>Name</b></label>
      <input class="w3-input w3-border w3-sand" name="name" value="<?php echo $stix_model->getName(); ?>" type="text"></p>
      <p>
      <button type="button" class="w3-btn w3-teal" onclick="if(editmodelform.name.value == '') alert('Input a name for the model.'); else editmodelform.submit();">Modify</button></p>
<?php
if (isset($_SESSION['message']) && $_SESSION['message']) {
   $msg = $_SESSION['message'];
   $panel_color = $_SESSION['panel_color'];
   $_SESSION['message'] = "";
   $_SESSION['panel_color'] = "";
?>
      <div class="w3-panel <?php echo $panel_color?>">
        <h4>Message</h4>
        <p><?php echo "".$msg; ?></p>
      </div>
<?php
}
?>
    </form>
   </div>
   <p/>
   <!-- show objects for this model -->
   <div class="w3-card-4">
<?php
$model = new STIXModel();
$objs = $model->getAllSTIXObjects($u->getIdUser(), $id_stix_model);
?>
    <div class="w3-container w3-teal">
     <h5>This model has <?php echo count($objs) == 0 ? "no objects" : (count($objs) == 1 ? "1 object" : count($objs)." objects"); ?>.</h5>
    </div>
    <div class="w3-panel w3-cell-middle"><?php echo count($objs) == 0 ? "<p>Choose a STIX&trade; object below and click on 'Add to my model'.</p>" : ""; ?></div>

     <div class="w3-panel">
      <table border="0">
       <tr>
<?php
$counter = 1;
foreach ($objs as $objects) {
   $id_stix_object = $objects['id_stix_object'];
   $uuid = explode("--", $objects['uuid']);
   $rvalue = $objects['description']; //TODO: break by rvalue: show the SDO, then SCO, then SRO ?
   $STIX_obj_type = $uuid[0];
   // test if image exists on folder (look 'globals.php')
   $app_name= ($_SERVER['HTTP_HOST'] == "localhost" ? "cyberactive" : "");
   $str_image = checkRemoteFile($_SERVER['HTTP_HOST']."/".$app_name."/images/STIX/".$STIX_obj_type.".png")==1 ? $STIX_obj_type.".png" : "none.png";
?>
        <td align="center" valign="top">
          <form action="edit_STIX_object.php" method="GET">
           <input type="hidden" name="id_stix_model" value="<?php echo $id_stix_model; ?>">
           <input type="hidden" name="id_stix_object" value="<?php echo $id_stix_object; ?>">
           <input type="hidden" name="STIX_obj_type" value="<?php echo $STIX_obj_type; ?>">
           <input type="hidden" name="STIX_obj_type_id" value="<?php echo getSTIXObjectTypeId($root_keys, $json_data, $STIX_obj_type); ?>">
           <input type="hidden" name="rvalue" value="<?php echo $rvalue; ?>">
           <input type="hidden" name="s" value="0">
           <input type="image" src="images/STIX/<?php echo $str_image; ?>">
          </form>
          <font style="font-size: 10px;"><?php echo $STIX_obj_type; ?></font>
        </td>
<?php
   if ($counter++ % 5 == 0) {
?>
           </tr>
           <tr>
<?php
   }
}
?>
       </tr>
      </table>
<?php
if (count($objs)>0) {
?>
      <div class="w3-panel w3-right" style="font-size: 12px;">
      Click on the image to edit it.
      </div>
    <!-- JSON preview --> 
    <div>
     <button class="w3-btn w3-pale-green" type="button" onclick="hideDiv('json-defs')">&nbsp;&nbsp;Preview JSON definitions&nbsp;&nbsp;</button>
      &nbsp;<a href="saveJSON.php?id_stix_model=<?php echo $id_stix_model;?>"><img src="images/save-32-32.png" width="16" alt="Save Model"></a>
     <div style="display:none" id="json-defs">
<pre style="font-size: 10px;"><?php echo $stix_model->fetchModelJSON(); ?></pre>
     </div>
    </div>

     </div>
<?php
}
?>

   </div>
   <!-- end show objects -->
  </div>
 </div>

 <!-- MAIN CONTENT -->
 <div class="w3-container">
   <h3 class="w3-text-teal">Add new object</h3>
       
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
           <button class="w3-btn w3-pale-green" onclick="hideDiv('<?php echo "desc".($counter_elem); ?>')">&nbsp;&nbsp;Show/Hide documentation&nbsp;&nbsp;</button>
           <div class="w3-left" style="display:none" id="<?php echo "desc".($counter_elem); ?>"><?php echo $description; ?></div>
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