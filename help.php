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
<?php
// get all the STIX models for this user's group
?>
  <div class="w3-row w3-padding-64">
    <div class="w3-twothird w3-container">
      <h2 class="w3-text-teal">Help</h2>

      <!-- first card -->
      <div class="w3-card-4">
         <header class="w3-container w3-teal">
         <h3>Cyber-A<font color="white"><b>cti</b></font>VE's documents</h3>
         </header>
           <div class="w3-container">
             <p><a href="json/STIX2.1.json" target="_blank">STIX2.1.json</a>, created based on STIX&trade; specification</p>
             <p><a href="json/STIX2.1-vocabularies.json" target="_blank">STIX2.1-vocabularies.json</a>, also created based on STIX&trade; documentation on vocabularies, types, and identifiers.</p>
           </div>
      </div>
      <p></p>
      <!-- second card -->
      <div class="w3-card-4">
         <header class="w3-container w3-teal">
         <h3>Feature list</h3>
         </header>
           <div class="w3-container">
             <h6>Latest version: <?php echo $LATEST_VERSION; ?></h6>
             <p>
             - parameter space exploration, perceiving the type of each parameter and whether they are required or optional;<br/>
             - adherence to STIX&trade; version 2.1;<br/>
             - standard web based application constructs: password is hashed on client-side and transmitted over the Internet, server-side testing for access, session handling and timeout after 10min of inactivity, <i>forget my password</i> (send e-mail link for change password);<br/>
             - STIX&trade; model creation and addition of objects (SDOs, SROs, and SCOs), integrated with original documentation;<br/>
             - Edit models and objects, retract objects (restrict access on specific objects);<br/>
             - Preview STIX&trade; model's JSON before sharing;<br/>
             - Model validation, checking required parameters against user modelling choices;<br/>
             </p>
           </div>
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