<!-- Navbar -->
<div class="w3-top">
 <div class="w3-bar w3-theme w3-top w3-left-align w3-large">
   <a class="w3-bar-item w3-button w3-right w3-hide-large w3-hover-white w3-large w3-theme-l1" href="javascript:void(0)" onclick="w3_open()"><i class="fa fa-bars"></i></a>
   <a href="index.php" class="w3-bar-item w3-button w3-theme-l1">Home</a>
   <a href="new_stix_model.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white">New model</a>
   <a href="links.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white">Links</a>
   <a href="contact.php" class="w3-bar-item w3-button w3-hide-small w3-hover-white">Contact</a>
   <a href="help.php" class="w3-bar-item w3-button w3-hide-small w3-hide-medium w3-hover-white">Help</a>
   <a href="about.php" class="w3-bar-item w3-button w3-hide-small w3-hide-medium w3-hover-white">About...</a>
    <div class="w3-right" style="font-size: 12px;">
     <p><?php echo ($u!=null?$u->getEmail():"");?>&nbsp;&nbsp;&nbsp;</p>
    </div>
    <div class="w3-right">&nbsp;&nbsp;&nbsp;</div>
    <div class="w3-right" style="font-size: 12px;">
     <p><?php echo ($u!=null ? ("Last seen: ".$u->getLastLoginFmt()) : ""); ?></p>
    </div>
 </div>
</div>
