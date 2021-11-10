<?php
session_start();
include 'session_valid.php';
include_once 'globals.php';
global $LIMIT;

if (isset($_SESSION['user']))
   $u = unserialize($_SESSION['user']);
else {
   $_SESSION['message'] = "Session expired.";
   header("Location: index.php");
   exit;
}
// clean session vars
//$_SESSION['success_msg'] = "";

$limit = $LIMIT;
$o_offset = isset($_GET['o_offset']) ? $_GET['o_offset'] : 0; // offset for the objects
$m_offset = isset($_GET['m_offset']) ? $_GET['m_offset'] : 0; // offset for the models
$show_retracted = isset($_GET['show_retracted']) ? $_GET['show_retracted'] : 0; // show all retracted objects

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
    <div class="w3-content w3-container w3-left" style="width:65%;">
      <h2 class="w3-text-teal">My dashboard</h2>

<?php
if (isset($_SESSION['success_msg']) && $_SESSION['success_msg']) {
   $msg = $_SESSION['success_msg'];
   unset($_SESSION['success_msg']);
?>
      <div class="w3-panel w3-pale-green">
        <h4>Success!</h4>
        <p><?php echo "".$msg; ?></p>
      </div>
<?php
}
?>

<!-- show all STIX models for this user -->
      <h3 class="w3-text-teal">STIX&trade; <b>models</b></h3>
      
      <table id="standard-table">
<?php
$all_stix_models = $u->getSTIXModels($limit, $m_offset);
$size_models = $u->countSTIXModels();
if (count($all_stix_models) > 0) {
?>
       <tr><td colspan="5"><?php echo "Profile: ".$u->getProfileName()?></td></tr>
       <tr>
         <th class="w3-center" style="width:2%;">Id</th>
         <th class="w3-center" style="width:15%;">Name</th>
         <th class="w3-center" style="width:15%;">Date Created</th>
         <th class="w3-center" style="width:15%;">Date Modified</th>
         <th class="w3-center" style="width:10%;">Action</th>
       </tr>
<?php
} else {
?>
   <!--<h5>None</h5>-->
   <div class="w3-text w3-left">
     <a href="new_stix_model.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white">
      <button class="w3-text w3-left w3-teal" style="height:45px;">&nbsp;New model&nbsp;</button>
     </a>
   </div>
<?php
}

foreach ($all_stix_models as $models) {
   $id_stix_model = $models['id_stix_model'];
?>
       <tr class="w3-text w3-center">
         <td><?php echo $id_stix_model; ?></td>
         <td align="left"><?php echo $models['name']; ?></td>
         <td><?php echo formatDate($models['created_date']); ?></td>
         <td><?php echo formatDate($models['modified_date']); ?></td>
         <td>
          <form action="edit_new_object.php" method="GET">
           <input type="hidden" name="id_stix_model" value="<?php echo $id_stix_model; ?>">
           <button class="w3-btn w3-teal" type="submit">View / Add objects</button>
          </form>
         </td>
       </tr>
<?php
}
?>
      </table>
      
<!-- pagination -->
<?php
   $pages = $size_models/$LIMIT;
   $current_page = $o_offset/$LIMIT;
?>
    <div class="w3-bar w3-center">
<?php
for ($i = 0; $i < floor($pages); $i++) {   
   if ($i == 0) {
?>
     <a href="dashboard.php?m_offset=0" class="w3-button">&laquo;</a>
<?php
   }
?>
     <a href="dashboard.php?m_offset=<?php echo ($LIMIT*($i)); ?>" class="w3-button <?php echo $current_page == $i ? "w3-pale-green" : ""; ?>"><?php echo ($i+1); ?></a>
<?php
   if ($i == floor($pages)) {
?>
     <a href="dashboard.php?m_offset=<?php echo ($size_models-$LIMIT) > 0 ? ($size_models-$LIMIT) : 0; ?>" class="w3-button">&raquo;</a>
<?php
   }
}
?>
   </div>

<?php
// I need this to be able to edit the STIX objects
$json_file = file_get_contents("json/STIX2.1.json");
$json_data = json_decode($json_file, true);

// get all keys from JSON
$root_keys = array_keys($json_data);
?>

<!-- show all STIX elements from this user's profile -->
      <h3 class="w3-text-teal">Previously created STIX&trade; <b>objects</b> for profile</h3>
      <!-- start table -->
      <table id="standard-table">
<?php
$all_stix_objects = $u->getSTIXObjects($limit, $o_offset, $show_retracted);
$size_objs = $u->countSTIXObjects($show_retracted);
if ($size_objs > 0) {
?>
       <tr>
        <td colspan="6" align="right">
          <form action="dashboard.php" method="GET">
           <input type="hidden" name="o_offset" value="0">
<?php
   if ($show_retracted) {
?>
           <input type="hidden" name="show_retracted" value="0">
           <button class="w3-btn w3-pale-green" type="submit">Show all</button>
<?php
   } else {
?>
           <input type="hidden" name="show_retracted" value="1">
           <button class="w3-btn w3-pale-green" type="submit">Show retracted</button>
<?php
}
?>
          </form>
        </td>
       </tr>
       <tr>
         <th class="w3-center" style="width:2%;">Id</th>
         <th class="w3-center" style="width:10%;">Type</th>
         <th class="w3-center" style="width:20%;">Name</th>
         <th class="w3-center" style="width:15%;">Date Modified</th>
         <th colspan="2" class="w3-center" style="width:10%;">Actions</th>
       </tr>
<?php
   } else {
?>
       <tr>
        <td><p>None.</p>
        <td colspan="5" align="right" style="width:450px;">
          <form action="dashboard.php" method="GET">
           <input type="hidden" name="o_offset" value="0">
           <input type="hidden" name="show_retracted" value="<?php echo $show_retracted ? "0" : "1"; ?>">
           <button class="w3-btn w3-pale-green" type="submit">Show all</button>
          </form>
        </td>
       </tr>
<?php
}

foreach ($all_stix_objects as $objects) {
   $id_stix_model = $objects['id_stix_model'];
   $id_stix_object = $objects['id_stix_object'];
   $rvalue = $objects['description'];
   $type = explode("--", $objects['uuid']);
?>
       <tr class="w3-text w3-center">
         <td><?php echo $id_stix_object; ?></td>
         <td align="left"><?php echo $type[0]; ?></td>
         <td align="left"><?php echo $objects['name']; ?></td>
         <td><?php echo formatDate($objects['modified_date']); ?></td>
         <td>
          <form action="edit_STIX_object.php" method="GET">
           <input type="hidden" name="id_stix_model" value="<?php echo $id_stix_model; ?>">
           <input type="hidden" name="id_stix_object" value="<?php echo $id_stix_object; ?>">
           <input type="hidden" name="STIX_obj_type_id" value="<?php echo getSTIXObjectTypeId($root_keys, $json_data, $type[0]); ?>">
            <input type="hidden" name="STIX_obj_type" value="<?php echo $type[0]; ?>">
            <input type="hidden" name="rvalue" value="<?php echo $rvalue; ?>">
            <input type="hidden" name="s" value="0">
           <button class="w3-btn w3-teal" type="submit">Edit</button>
          </form>
         </td>
        <td>
          <form action="retract_object_do.php" method="GET">
           <input type="hidden" name="id_stix_object" value="<?php echo $id_stix_object; ?>">
           <input type="hidden" name="o_offset" value="<?php echo $o_offset; ?>">
           <input type="hidden" name="retracted" value="<?php echo $show_retracted ? 0 : 1; ?>">
           <button class="w3-btn w3-teal" type="submit"><?php echo $show_retracted ? "Restore" : "Retract"; ?></button>
          </form>
        </td>
       </tr>
<?php
}
?>
      </table>

   <!-- pagination -->
<?php
   $pages = $size_objs/$LIMIT;
if ($pages > 1) {
   $current_page = $o_offset/$LIMIT;
?>
    <div class="w3-bar w3-center">
<?php
   for ($i = 0; $i < floor($pages)+1; $i++) {
      if ($i == 0) {
?>
     <a href="dashboard.php?o_offset=0" class="w3-button">&laquo;</a>
<?php
   }
?>
     <a href="dashboard.php?o_offset=<?php echo ($LIMIT*($i)); ?>&show_retracted=<?php echo $show_retracted?>" class="w3-button <?php echo $current_page == $i ? "w3-pale-green" : ""; ?>"><?php echo ($i+1); ?></a>
<?php
      if ($i == floor($pages)) {
?>
     <a href="dashboard.php?o_offset=<?php echo ($size_objs-$LIMIT) > 0 ? ($size_objs-$LIMIT) : 0; ?>" class="w3-button">&raquo;</a>
<?php
      }
   }
?>
   </div>
<?php
}
?>
    <!--
    <div class="w3-third w3-container">
      <p class="w3-border w3-padding-large w3-padding-32 w3-center">AD</p>
      <p class="w3-border w3-padding-large w3-padding-64 w3-center">AD</p>
    </div>
    -->
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