<?php
require_once("globals.php");

class User {
   private $id_user;
   private $email;
   private $id_profile;
   private $id_user_profile;
   private $profilename;
   private $lastLogin;

   function __construct() {
   }
   
   function getIdUser() {
      return $this->id_user;
   }
   
   function getLastLogin() {
      return $this->lastLogin;
   }
   
   function getLastLoginFmt() {
      return gmdate("d/m/Y H:i", strtotime($this->lastLogin));
   }

   function getEmail() {
      return $this->email;
   }

   function getProfileName() {
      return $this->profilename;
   }

   function getIdProfile() {
      return $this->id_profile;
   }

   function getIdUserProfile() {
      return $this->id_user_profile;
   }

   /**
       Insert a new user
       Return the id that was inserted.
   */
   function insert($email, $passwd, $id_profile) {
      global $properties;
      $lastID = -1;
      $sql = "INSERT INTO users (email, passwd, id_profile, last_login) VALUES (:email, :passwd, :id_profile, now());";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindParam(":email", $email);
         $stmt->bindParam(":passwd", $passwd);
         $stmt->bindParam(":id_profile", $id_profile);
         $stmt->execute();
         $lastID = $conn->lastInsertId();
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
         return $lastID;
      }
   }
   
   /**
      Return a dropdown with all users.
   */
   public function getUsersDropdown() {
      global $properties;
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $sql = "SELECT id_user, email ".
                "FROM users u ".
                "ORDER by email ASC;";
         $stmt = $conn->prepare($sql);
         $stmt->execute();
         $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
         if ($rows && count($rows) > 0) {
            $aux  = "\n<select name=\"id_user\" style=\"width:40%;\">\n";
            foreach ($rows as $row) {
               $aux .= " <option value=\"".$row['id_user']."\">".$row['email']."</option>\n";
            }
            $aux .= "</select>\n";
         } else return "No users were found.";
         return $aux;
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }

   /**
      Return a dropdown with all users.
   */
   public function getUserProfilesDropdown() {
      global $properties;
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $sql = "SELECT id_user_profile, description ".
                "FROM user_profiles up ".
                "ORDER by description ASC;";
         $stmt = $conn->prepare($sql);
         $stmt->execute();
         $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
         if ($rows && count($rows) > 0) {
            $aux  = "\n<select name=\"id_user_profile\" style=\"width:40%;\">\n";
            $a = 0;
            foreach ($rows as $row) {
               if ($row['description'] != "Administrators") {
                  $sel = "selected";
                  $a++;
               }
               $aux .= " <option value=\"".$row['id_user_profile']."\" ".($a == 1 ? $sel : "").">".$row['description']."</option>\n";
            }
            $aux .= "</select>\n";
         } else return "No profiles were found.";
         return $aux;
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }
   
   public function updateUser($id_user, $id_profile, $id_user_profile) {
      global $properties;
      $lastID = -1;
      $sql = "UPDATE users SET id_profile=:id_profile, id_user_profile=:id_user_profile WHERE id_user=:id_user;";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindParam(":id_profile", $id_profile);
         $stmt->bindParam(":id_user_profile", $id_user_profile);
         $stmt->bindParam(":id_user", $id_user);
         $stmt->execute();
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;     
         return 1;
      }
   }

   /**
      Return a dropdown with all users.
   */
   public function getProfilesDropdown() {
      global $properties;
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $sql = "SELECT id_profile, description ".
                "FROM profiles up ".
                "ORDER by description ASC;";
         $stmt = $conn->prepare($sql);
         $stmt->execute();
         $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
         if ($rows && count($rows) > 0) {
            $aux  = "\n<select name=\"id_profile\" style=\"width:40%;\">\n";
            foreach ($rows as $row) {
               $aux .= " <option value=\"".$row['id_profile']."\">".$row['description']."</option>\n";
            }
            $aux .= "</select>\n";
         } else return "No profiles were found.";
         return $aux;
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }

   /**
       Function to verify if user could edit a model.
   */
   function hasPermissionOnModel($id_stix_model) {
      global $properties;
      $sql = "SELECT count(sm.id_stix_model) as total ".
             "FROM stix_models sm, profiles p, users u  ".
             "WHERE u.id_profile=p.id_profile AND ".
             "      sm.id_user=u.id_user AND p.id_profile=:id_profile AND ".
             "      sm.id_stix_model=:id_stix_model;";
             //echo $sql;exit();
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_profile', $this->id_profile);
         $stmt->bindValue(':id_stix_model', $id_stix_model);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         return $row['total'];
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }
   
   /**
       Retrieve a user from the DB, storing in this user.
   */
   function get($id_user=-1) {
      global $properties;
      $sql = "SELECT u.email,u.id_profile,p.description,u.last_login,u.id_user_profile ".
             "FROM users u, profiles p, user_profiles up ".
             "WHERE u.id_profile=p.id_profile AND u.id_user_profile=up.id_user_profile AND u.id_user=:id_user;";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_user', $id_user);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         $this->id_user = $id_user;
         $this->email = $row['email'];
         $this->id_profile = $row['id_profile'];
         $this->id_user_profile = $row['id_user_profile'];
         $this->profilename = $row['description'];
         $this->lastLogin = $row['last_login'];
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }

   /**
       Return an array with all STIX models for this user.
   */
   function getSTIXModels($limit=-1, $offset=-1) {
      global $properties;
      $sql = "SELECT sm.id_stix_model, sm.name, sm.created_date, sm.modified_date, p.description ".
             "FROM stix_models sm, profiles p, users u ".
             "WHERE u.id_profile=p.id_profile AND sm.id_user=u.id_user AND p.id_profile=:id_profile ".
             "ORDER by sm.modified_date DESC ";
      if ($limit != -1 && $offset != -1)
         $sql .= "LIMIT ".$limit." OFFSET ".$offset;
      $sql .= ";";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_profile', $this->id_profile);
         $stmt->execute();
         $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
         return $rows;
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }

   function countSTIXModels() {
      global $properties;
      $sql = "SELECT count(sm.id_stix_model) as total ".
             "FROM stix_models sm, profiles p, users u ".
             "WHERE u.id_profile=p.id_profile AND u.id_user=:id_user;";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_user', $this->id_user);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         $total = $row['total'];
         return $total;
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }

   /**
       Return an array with all STIX models for this user.
   */
   function getSTIXObjects($limit, $offset, $retracted) {
      global $properties;
      $sql = "SELECT sm.id_stix_model, so.id_stix_object, so.name, st.description, so.created_date, so.modified_date, so.uuid ".
             "FROM stix_models sm, stix_objects so, models_objects mo, stix_types st, profiles p, users u ".
             "WHERE so.id_stix_type=st.id_stix_type AND  ".
             "      sm.id_stix_model=mo.id_stix_model AND ".
             "      mo.id_stix_object=so.id_stix_object AND ".
             "      sm.id_user=u.id_user AND ". //
             "      u.id_profile=p.id_profile AND ". //
             "      p.id_profile=:id_profile AND so.retracted=:retracted ".
             "ORDER by so.modified_date DESC ".
             "LIMIT ".$limit." OFFSET ".$offset.";";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_profile', $this->id_profile);
         $stmt->bindValue(':retracted', $retracted);
         $stmt->execute();
         $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
         return $rows;
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }
   
   function countSTIXObjects($show_retracted) {
      global $properties;
      $sql = "SELECT count(so.id_stix_object) as total ".
             "FROM stix_models sm, stix_objects so, models_objects mo, stix_types st, profiles p, users u ".
             "WHERE so.id_stix_type=st.id_stix_type AND  ".
             "      sm.id_stix_model=mo.id_stix_model AND ".
             "      mo.id_stix_object=so.id_stix_object AND ".
             "      sm.id_user=u.id_user AND ". //
             "      u.id_profile=p.id_profile AND ". //
             "      p.id_profile=:id_profile AND so.retracted=:retracted ";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_profile', $this->id_profile);
         $stmt->bindValue(':retracted', $show_retracted);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         $total = $row['total'];
         return $total;
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }

   /**
       Retrieve a user from the DB, storing in this user.
   */
   function login($email, $passwd) {
      global $properties;
      $sql = "SELECT id_user FROM users WHERE email=:email AND passwd=:passwd;";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':email', $email);
         $stmt->bindValue(':passwd', $passwd);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         $id_user = $row['id_user'];
         if ($id_user) {
            // update login 'last login' timestamp
            $this->updateLastLogin($conn, $id_user);
            return $id_user;
         } else  { return null; }
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }
   
   /**
       Retrieve a user from the DB, storing in this user.
   */
   function newlogin($email, $passwd) {
      global $properties;
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $sql = "SELECT id_user FROM users WHERE email=:email";
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':email', $email);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         $id_user = $row['id_user'];
         if ($id_user) {
            return -1;
         } else  { // insert user
            $sql = "INSERT INTO users (email, passwd, last_login, id_profile, id_user_profile) VALUES (:email, :passwd, now(), 5, 2);"; 
            // id_profile = 5 is "External users" is "User" and id_user_profile = 2
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':passwd', $passwd);
            $stmt->execute();
            $lastID = $conn->lastInsertId();
            return $lastID;
         }
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }
   
   /**
       Recover user - step 1
   */
   function recoveryLink($email, $link) {
      global $properties;
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $sql = "SELECT id_user FROM users WHERE email=:email AND passwd=:passwd";
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':email', $email);
         $stmt->bindValue(':passwd', $link);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         $id_user = $row['id_user'];
         return ($id_user ? $id_user : -1);
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }
   
   /**
       Update password for user
   */
   function updatePasswdLink($email, $passwd, $link) {
      global $properties;
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         // check link
         $sql = "SELECT id_user FROM users WHERE email=:email AND passwd=:passwd";
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':email', $email);
         $stmt->bindValue(':passwd', $link);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         $id_user = $row['id_user'];
         if ($id_user) {
            // update
            $sql = "UPDATE users SET passwd=:passwd WHERE id_user=:id_user;";
            //echo "id_user=".$id_user."<br>".$passwd."<br>";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':passwd', $passwd);
            $stmt->bindValue(':id_user', $id_user);
            $stmt->execute();
            return 1;
         } else return -1;
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }

   /**
      Recover password for user.
   */
   function recoverPwd($email) {
      global $properties;
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $sql = "SELECT id_user FROM users WHERE email=:email";
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':email', $email);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         $id_user = $row['id_user'];
         if ($id_user) { // user exists
            $sql = "UPDATE users set passwd=:passwd WHERE id_user=:id_user;"; 
            $stmt = $conn->prepare($sql);
            $auxpwd = bin2hex(random_bytes(30)); // generates a random string for password
            $stmt->bindValue(':passwd', $auxpwd);
            $stmt->bindValue(':id_user', $id_user);
            $stmt->execute();
            $r = $this->sendPHPEmail($email, $auxpwd);
            return $r;
         } else  { // cant find this user, error
            return "no-user";
         }
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }
   
   private function sendPHPEmail($email, $newpwd) {
      global $properties;
      $utils = new Utils();
      $subject = "ActiVE password recovery details";
      $message = " Please, click <a href=\"".$properties->getHost()."login_recover.php?email=".$email."&link=".$newpwd."\">on this link<a> to recover your password."; // Texto da mensagem
      $enviado = $utils->sendEmail($email, $subject, $message);
      if ($enviado) {
         return "ok";
      } else {
         return $mail->ErrorInfo;
      }
   }

   /**
       Retrieve a user from the DB, storing in this user.
   */
   private function updateLastLogin($conn, $id_user) {
      global $properties;
      $sql = "UPDATE users SET last_login=now() WHERE id_user=:id_user;";
      try {
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_user', $id_user);
         $stmt->execute();
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      }
   }

   /**
       Retrieve a user from the DB, storing in this user.
   */
   function logout() {
      global $properties;
      $sql = "UPDATE users SET last_login=now() WHERE id_user=:id_user;";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_user', $this->id_user);
         $stmt->execute();
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }

}

?>