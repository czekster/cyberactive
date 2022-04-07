<?php

/**
   Change properties for your set up here.
   Rename this to "Property.php" after you're done.
*/
class Property {
   private $appName = "cyberaCTIve";
   private $serverName = "mysql.performanceware.com.br";
   private $userName = "performancewar04";
   private $password = "yCewtN2021";
   private $dbName = "performancewar04";
   private $host = "https://cyberactive.performanceware.com.br/";
   // for emailing
   private $smtp_host = "smtp.performanceware.com.br";
   private $smtp_username = "active_admin@performanceware.com.br";
   private $smtp_password = "EU9FWfxd5Sb9mKS";
   private $smtp_bcc = "rczekster@gmail.com";
   private $smtp_sender = "active_admin@performanceware.com.br";
   
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