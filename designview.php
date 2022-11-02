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
    <div class="w3-container">
      <h2 class="w3-text-teal">Official STIX&trade; Visualizer</h2>

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
          <button
            class="w3-btn w3-teal visualize-button"
            type="button"
            data-id="<?php echo $id_stix_model; ?>"
            data-title="<?php echo $models['name']; ?>"
          >Visualize</button>
         </td>
       </tr>
<?php
}
?>
      </table>
      
<!-- pagination -->
<?php
   $pages = $size_models/$LIMIT;
   $current_page = $m_offset/$LIMIT;
?>
    <div class="w3-bar w3-center">
<?php
for ($i = 0; $i < floor($pages)+1; $i++) {   
   if ($i == 0) {
?>
     <a href="designview.php?m_offset=0" class="w3-button">&laquo;</a>
<?php
   }
?>
     <a href="designview.php?m_offset=<?php echo ($LIMIT*($i)); ?>" class="w3-button <?php echo $current_page == $i ? "w3-pale-green" : ""; ?>"><?php echo ($i+1); ?></a>
<?php
   if ($i == floor($pages)+1) {
?>
     <a href="designview.php?m_offset=<?php echo ($size_models-$LIMIT) > 0 ? ($size_models-$LIMIT) : 0; ?>" class="w3-button">&raquo;</a>
<?php
   }
}
?>
   </div>

      <?php
        require_once("stix-visualizer.php");
      ?>
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
