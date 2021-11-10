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
      <h2 class="w3-text-teal">Links</h2>

      <!-- first card -->
      <div class="w3-card-4">
         <header class="w3-container w3-teal">
         <h3><a href="https://oasis-open.github.io/cti-documentation/stix/intro" target="_blank">Introduction to STIX&trade;</a></h3>
         </header>
           <div class="w3-container">
             <p>The link shows the SDOs, SROs, and SCOs based on STIX&trade; and a comprehensive modelling introduction to the language.</p>
           </div>
      </div> 
      <p/>
      <!-- second card -->
      <div class="w3-card-4">
         <header class="w3-container w3-teal">
         <h3>MITRE's <a href="https://attack.mitre.org/" target="_blank">ATT&CK&reg; framework</a></h3>
         </header>
           <div class="w3-container">
             <p>Knowledge base of TTPs with global outreach.</p>
             <p>Developed by MITRE corporation - US.</p>
           </div>
      </div>
      <p/>
      <!-- third card -->
      <div class="w3-card-4">
         <header class="w3-container w3-teal">
         <h3>NIST's <a href="https://nvd.nist.gov/vuln" target="_blank">NVD - National Vulnerability Database</a></h3>
         </header>
           <div class="w3-container">
             <p>Repository of known vulnerabilities for assessing cyber-security in multiple domains.</p>
             <p>Developed and maintained by NIST - US.</p>
           </div>
      </div> 
    </div>
    <!-- end of main cards -->

    <div class="w3-third w3-container">
      <p class="w3-border w3-padding-large w3-padding-32 w3-center">News story 1</p>
      <p class="w3-border w3-padding-large w3-padding-64 w3-center">Interesting topic</p>
      <p class="w3-border w3-padding-large w3-padding-32 w3-center">Tutorial on CTI</p>
      <p class="w3-border w3-padding-large w3-padding-32 w3-center">NCSC - UK</p>
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