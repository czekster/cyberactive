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

   <h2 class="w3-text-teal w3-left">Contact us</h2>

   <div class="w3-panel"> <!-- main panel -->
    <p>Please, fill the fields on the following form, we will contact you as soon as possible.</p>
      <form action="contact_do.php" method="POST" class="w3-container w3-card-4 w3-light-grey w3-text-teal w3-margin">

      <div class="w3-row w3-section">
        <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-user"></i></div>
          <div class="w3-rest">
            <input class="w3-input w3-border" name="first" type="text" placeholder="First Name">
          </div>
      </div>

      <div class="w3-row w3-section">
        <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-user"></i></div>
          <div class="w3-rest">
            <input class="w3-input w3-border" name="last" type="text" placeholder="Last Name">
          </div>
      </div>

      <div class="w3-row w3-section">
        <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-envelope-o"></i></div>
          <div class="w3-rest">
            <input class="w3-input w3-border" name="email" type="text" placeholder="Email">
          </div>
      </div>

      <div class="w3-row w3-section">
        <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-phone"></i></div>
          <div class="w3-rest">
            <input class="w3-input w3-border" name="phone" type="text" placeholder="Phone">
          </div>
      </div>

      <div class="w3-row w3-section">
        <div class="w3-col" style="width:50px"><i class="w3-xxlarge fa fa-pencil"></i></div>
          <div class="w3-rest">
            <textarea class="w3-input w3-border" name="message" type="text" placeholder="Message" rows="5"></textarea>
          </div>
      </div>

      <button class="w3-button w3-block w3-section w3-teal w3-ripple w3-padding">Send</button>

      </form>
     </div> <!-- div for the main panel -->

<?php
if (isset($_SESSION['message']) && $_SESSION['message']) {
   $msg = $_SESSION['message'];
   $panel_color = $_SESSION['panel_color'];
   $body_msg = $_SESSION['body_msg'];
   $_SESSION['message'] = "";
   $_SESSION['panel_color'] = "";
   $_SESSION['body_msg'] = "";
?>
      <div class="w3-panel <?php echo $panel_color?>">
        <h4>Message</h4>
        <p><?php echo "".$msg; ?></p>
  
        <div class="w3-panel w3-light-gray">
         <p><?php echo $body_msg; ?></p>
        </div>
      </div>
<?php
}
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