<?php

/**
   Change properties for your set up here.
   *******Rename this file to "Property.php" after you're done.************
*/
class Property {
   // basic properties
   private $appName = "CyberActiVE";
   private $serverName = "put server name here";
   private $userName = "put username (DB) here";
   private $password = "put password (DB) here";
   private $dbName = " put database name here";
   private $host = "put application host name here";
   // for emailing
   private $smtp_host = "put SMTP host name here";
   private $smtp_username = "put SMTP username here";
   private $smtp_password = "put SMTP password here";
   private $smtp_bcc = "put an email for BCC (blind carbon copy) here";
   private $smtp_sender = "put a SMTP sender here";
   
   function getHost() {
      return $this->host;
   }
   
   function getSMTPUsername() {
      return $this->smtp_username;
   }

   function getSMTPHost() {
      return $this->smtp_host;
   }
   
   function getSMTPPassword() {
      return $this->smtp_password;
   }
   
   function getSMTPBCC() {
      return $this->smtp_bcc;
   }
   
   function getSMTPSender() {
      return $this->smtp_sender;
   }
   
   function getServerName() {
      return $this->serverName;
   }
   
   function getStrConnection() {
      return "mysql:host=".$this->getServerName().";dbname=".$this->getDbName();
   }
   
   function setAppName($appName) {
      $this->appName = $appName;
   }

   function getAppName() {
      return $this->appName;
   }
   
   function getUserName() {
      return $this->userName;
   }

   function getPassword() {
      return $this->password;
   }

   function getDbName() {
      return $this->dbName;
   }
}


?>