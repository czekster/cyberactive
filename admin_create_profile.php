<?php
session_start();
include 'session_valid.php';

include_once ('globals.php');

if (!isset($_SESSION['user'])) {
   $_SESSION['message'] = "Expired or invalid session.";
   header("Location: index.php");
   exit;
}

$u = unserialize($_SESSION['user']);

if ($u->getIdUserProfile() != 1) {
   $_SESSION['panel_color'] = "w3-pale-red";
   $_SESSION['message'] = "You don't have access to this script.";
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
      <h2 class="w3-text-teal">Create profile</h2>

<?php
$user = new User();
?>
      <!-- first card -->
      <div class="w3-card-4">
       <header class="w3-container w3-teal">
       <h3>Options</h3>
       </header>
        <form name="profileform" method="POST" action="admin_create_profile_do.php">
         <div class="w3-container">
          <p>Name: <input type="text" name="description"></p>
          <button type="submit" class="w3-btn w3-teal">Submit</button></p>
         </div>
        </form>
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