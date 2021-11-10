<?php
session_start();
?>

<?php
include("page_header.php");

include("page_scripts.php");

?>
<!-- EMPTY Navbar -->
<div class="w3-top">
  <div class="w3-bar w3-theme w3-top w3-left-align w3-large">
    <a class="w3-bar-item w3-button w3-right w3-hide-large w3-hover-white w3-large w3-theme-l1" href="javascript:void(0)" onclick="w3_open()"><i class="fa fa-bars"></i></a>
    <a href="index.php" class="w3-bar-item w3-button w3-theme-l1">Home</a>
  </div>
</div>

<!-- EMPTY Sidebar -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-large w3-theme-l5 w3-animate-left" id="mySidebar">
  <a href="javascript:void(0)" onclick="w3_close()" class="w3-right w3-xlarge w3-padding-large w3-hover-black w3-hide-large" title="Close Menu">
    <i class="fa fa-remove"></i>
  </a>
  <h4 class="w3-bar-item"><b>&nbsp;</b></h4>
</nav>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- Main -->
<div class="w3-main" style="margin-left:250px">
 <div class="w3-row w3-padding-64">
  <div class="w3-twothird w3-container" style="width:50%">
    <h1 class="w3-text-teal">Cyber-A<font color="gray"><b>cti</b></font>VE - password recovery</h1>

    <form class="w3-container" method="POST" action="login_recovery_do.php" name="recoverform">
     <label>E-mail:</label>
     <input class="w3-input w3-border" type="text" name="email">
      <p/>
      <button type="button" class="w3-button w3-teal" onClick="if(validate_recover(recoverform)) recoverform.submit();">Submit</button>
    </form>
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
  </div>
 </div>


<?php
include("page_footer.php");
?>

</div>




</body>
</html>