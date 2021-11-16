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
      <h3>New STIX&trade; model<?php echo isset($_REQUEST["base"]) && $_REQUEST["base"] == "ab" ? " for Active Building" : ""; ?></h3>
    </div>
    <form class="w3-container" action="new_stix_model_do.php" method="POST" name="newmodelform">
      <p>
        <label class="w3-text-teal"><b>Name</b></label>
        <input class="w3-input w3-border w3-sand" name="name" type="text"/>
        <input name="base" type="hidden" value="<?php echo isset($_REQUEST["base"]) ? $_REQUEST["base"] : ""; ?>" />
      </p>
      <p>
      <button type="button" class="w3-btn w3-teal" onclick="if(newmodelform.name.value == '') alert('Input a name for the model.'); else newmodelform.submit();">Submit</button></p>
    </form>
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
