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
      <h2 class="w3-text-teal">About...</h2>

      <!-- first card -->
      <div class="w3-card-4">
         <header class="w3-container w3-teal">
         <h3>General details</h3>
         </header>
           <div class="w3-container">
             <p>The environment was developed in the context of the Active Building Centre Research Programme (ABC-RP), funded by EPSRC/UK.</p>
             <p>The idea is to provide an environment for cyber-security analysts to devise, compose, edit, share, and iterate over the standardised format offered by STIX&trade;.</p>
           </div>
      </div>
      <p></p>
      <!-- second card -->
      <div class="w3-card-4">
         <header class="w3-container w3-teal">
         <h3>People involved in the project</h3>
         </header>
           <div class="w3-container">
             <p>- <a href="http://homepages.cs.ncl.ac.uk/ricardo.melo-czekster/" target="_blank">Ricardo M. Czekster</a>, <a href="https://www.ncl.ac.uk" target="_blank">Newcastle University</a>, <a href="https://www.ncl.ac.uk/computing/" target="_blank">School of Computing</a>.</p>
             <p>E-mail: <font face="Courier new">ricardo.melo-czekster@ncl.ac.uk</font></p>
             <p>- <a href="#" target="_blank">Roberto Metere</a>, <a href="https://www.ncl.ac.uk" target="_blank">Newcastle University</a>, <a href="https://www.ncl.ac.uk/computing/" target="_blank">School of Computing</a> and The Alan Turing Institute, London.</p>
             <p>E-mail: <font face="Courier new">roberto.metere@ncl.ac.uk</font></p>
             <p>- <a href="http://homepages.cs.ncl.ac.uk/ricardo.melo-czekster/" target="_blank">Charles Morisset</a>, <a href="https://www.ncl.ac.uk" target="_blank">Newcastle University</a>, <a href="https://www.ncl.ac.uk/computing/" target="_blank">School of Computing</a>.</p>
             <p>E-mail: <font face="Courier new">charles.morisset@ncl.ac.uk</font></p>
             <p/>
             <p><a href="https://www.ncl.ac.uk/computing/research/srs/" target="_blank">Secure and Resilient Systems (SRS)</a> research group.</p>
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