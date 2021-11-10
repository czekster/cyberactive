<?php
session_start();
include 'session_valid.php';
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
      <h3>Oops.</h3>
    </div>

   <div class="w3-panel w3-pale-red">
     <h3>Error!</h3>
     <p><?php echo $_SESSION['message']; ?></p>
   </div>  

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