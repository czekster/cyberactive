<?php
session_start();
include_once('globals.php');
global $LATEST_VERSION;
?>

<?php
include("page_header.php");
?>
<body>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<?php
if (!isset($_SESSION['email']) || (isset($_SESSION['email']) && $_SESSION['email'] == "")) {
?>

<!-- Overlay effect when opening sidebar on small screens -->
<!--div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>-->

<!-- EMPTY Navbar -->
<div class="w3-top">
  <div class="w3-bar w3-theme w3-top w3-left-align w3-large">
    <a class="w3-bar-item w3-button w3-right w3-hide-large w3-hover-white w3-large w3-theme-l1" href="javascript:void(0)" onclick="w3_open()"><i class="fa fa-bars"></i></a>
    <a href="index.php" class="w3-bar-item w3-button w3-theme-l1">Login</a>
  </div>
</div>

<!-- EMPTY Sidebar -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-large w3-theme-l5 w3-animate-left" id="mySidebar">
  <a href="javascript:void(0)" onclick="w3_close()" class="w3-right w3-xlarge w3-padding-large w3-hover-black w3-hide-large" title="Close Menu">
    <i class="fa fa-remove"></i>
  </a>
  <h4 class="w3-bar-item"><b>&nbsp;</b></h4>
</nav>

<!-- Main -->
<div class="w3-main" style="margin-left:250px">
 <div class="w3-row w3-padding-64">
  <div class="w3-quarter w3-container">
    <h1 class="w3-text-teal">Cyber-A<font color="gray"><b>cti</b></font>VE</h1>

    <form class="w3-container" method="POST" action="login.php" name="loginform" onSubmit="if(!validateEmail(this.email.value)) { alert('Invalid e-mail address.'); return false; } ">
     <label>E-mail:</label>
     <input class="w3-input w3-border" type="text" name="email">
     <label>Password:</label>
      <input class="w3-input w3-border" type="password" name="passwd">
       <!-- div for the new user and forgot password -->
       <div class="w3-display-container" style="height:40px;">
        <div class="w3-display-right">
        <a href="login_recovery.php">Forgot password</a> &nbsp;&nbsp;&nbsp;
        <a href="login_new_user.php">New user</a></div>
       </div> 
       <!-- -->
      <button class="w3-button w3-teal">Login</button>
    </form>
<?php
   if (isset($_SESSION['message']) && $_SESSION['message']!="") {
?>
   <div class="w3-panel w3-pale-red">
     <h3>Error!</h3>
     <p><?php echo $_SESSION['message']; $_SESSION['message'] = ""; ?></p>
   </div>  
<?php
   }
?>
  </div>
 </div>


<?php
include("page_footer.php");

include("page_scripts.php");

?>

</div>
<?php
} else {
?>
<body>

<?php
include("page_navbar.php");
?>

<?php
include("page_sidebar.php");
?>

<!-- Main content: shift it to the right by 250 pixels when the sidebar is visible -->
<div class="w3-main" style="margin-left:250px">
<?php
// get all the STIX models for this user's group
?>
  <div class="w3-row w3-padding-64">
    <div class="w3-twothird w3-container">
      <h1 class="w3-text-teal">Cyber-A<font color="gray"><b>cti</b></font>VE</h1>
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
      <div class="w3-card-4">
         <!-- main cards for the main panel -->
         <header class="w3-container w3-teal">
         <h3>Environment for Cyber Threat Intelligence (CTI)</h3>
         </header>
           <div class="w3-container">
             <p><font color="teal">Cyber-A</font><font color="gray"><b>cti</b></font><font color="teal">VE</font> was created to help cyber-security analysts documenting, tracking, and modelling cyber-attacks using STIX&trade;, the Structured Threat Information Expression.</p>
             <p>It is a standardised format and language to describe cyber-attacks and provide share feeds to other cyber-security officers so they make timely decisions based on events documented by peers.</p>
             <p>Among the many features of <font color="teal">Cyber-A</font><font color="gray"><b>cti</b></font><font color="teal">VE</font>, we highlight easy tracking of STIX&trade; based models and its underlying objects, parameter space exploration over objects (STIX Domain Objects, STIX Relationship Objects, and STIX Cyber-observable Objects, respectively SDOs, SROs, and SCOs), and localised sharing across domain-user profiles, to mention a few.</p>
           </div>
         <footer class="w3-container w3-teal">
         </footer>
         <!-- end of main cards -->
      </div>
      <p/>

      <!-- another card -->
      <div class="w3-card-4">
         <header class="w3-container w3-teal">
         <h3>Updates</h3>
         </header>
           <div class="w3-container">
             <p>Latest Cyber-ActiVE version: <?php echo $LATEST_VERSION; ?></p>
             <p>Working with STIX&trade; version 2.1</p>
           </div>
         <footer class="w3-container w3-teal">
         <h6>Date: 26/October/2021</h6>
         </footer>
      </div> 
      <!-- end of another card -->

    </div>
    <div class="w3-third w3-container">
      <p class="w3-border w3-padding-large w3-padding-32 w3-center">News story 1</p>
      <p class="w3-border w3-padding-large w3-padding-64 w3-center">Interesting topic</p>
      <p class="w3-border w3-padding-large w3-padding-32 w3-center">Tutorial on CTI</p>
      <p class="w3-border w3-padding-large w3-padding-32 w3-center">NCSC - UK</p>
    </div>


  </div>


<?php
include("page_footer.php");

include("page_scripts.php");
?>

<!-- END MAIN -->
</div>


<?php
}
?>

</body>
</html>
