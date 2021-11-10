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
    <div class="w3-content w3-container w3-left w3-two-thirds">
      <h2 class="w3-text-green">Settings</h2>

<?php
if ($u->getIdUserProfile() == 1) {
?>
      <div class="w3-card-4">
       <div class="w3-container w3-pale-green">
        <h3>Administration</h3>
       </div>
       <div class="w3-container w3-margin-left">
        <p>
         <a href="admin_clean_do.php"><button class="w3-btn w3-light-gray">Truncate tables</button></a>&nbsp;
         <a href="admin_dumpdb_do.php"><button class="w3-btn w3-light-gray">Dump DB</button></a></p>
        <p>
         <a href="admin_assign_user_pr.php"><button class="w3-btn w3-light-gray">Assign User to Profile</button></a>&nbsp;
         <a href="admin_create_profile.php"><button class="w3-btn w3-light-gray">Create profile</button></a>
        </p>
       </div>
      </div>
<?php
}
?>

<?php
if (isset($_SESSION['message']) && $_SESSION['message']) {
   $panel_color = $_SESSION['panel_color'];
   $_SESSION['panel_color'] = "";
?>
      <div class="w3-panel <?php echo $panel_color?>">
        <h4>Message</h4>
        <p><?php echo "".$_SESSION['message']; ?></p>
      </div>
<?php
}
// truncate session object message
$_SESSION['message'] = "";
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