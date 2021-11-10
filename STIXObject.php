<?php
require_once("globals.php");

class STIXObject {
   private $id_stix_object;
   private $id_profile;
   private $id_stix_type;
   private $id_stix_model;
   private $uuid;
   private $type_description;
   private $name;
   private $created_date;
   private $modified_date;
   private $retracted;
   private $json;

   function __construct() {
   }
   
   function getId() {
      return $this->id_stix_object;
   }
   
   function UUID() {
      return $this->uuid;
   }

   function getIdType() {
      return $this->id_stix_type;
   }

   function getIdProfile() {
      return $this->id_profile;
   }

   function getIdModel() {
      return $this->id_stix_model;
   }

   function getName() {
      return $this->name;
   }
   
   function getTypeDescription() {
      return $this->type_description;
   }

   function getCreatedDateFmt() {
      return gmdate("d/m/Y H:i", strtotime($this->created_date));
   }

   function getCreatedDateSTIXFmt() {
      return gmdate("Y-m-d\TH:i:s\Z", strtotime($this->created_date));
   }

   function getModifiedDateFmt() {
      return gmdate("d/m/Y H:i", strtotime($this->modified_date));
   }

   function getModifiedDateSTIXFmt() {
      return gmdate("Y-m-d\TH:i:s\Z", strtotime($this->modified_date));
   }

   function getJSON() {
      return $this->json;
   }
   
   /**
       Insert a new STIX object (from a user).
       Return the id that was inserted.
   */
   function insert($id_stix_type, $name, $id_profile, $uuid, $json="", $id_stix_model) {
      global $properties;
      $lastID = -1;
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         // begin a transaction
         $conn->beginTransaction();
         // first sql
         $sql  = "INSERT INTO stix_objects (id_stix_type,name,id_profile,uuid,json) ";
         $sql .= "VALUES (:id_stix_type,:name,:id_profile,:uuid,:json);";
         $stmt = $conn->prepare($sql);
         $stmt->bindParam(":id_stix_type", $id_stix_type);
         $stmt->bindParam(":name", $name);
         $stmt->bindParam(":id_profile", $id_profile);
         $stmt->bindParam(":uuid", $uuid);
         $stmt->bindParam(":json", $json);
         $stmt->execute();
         $lastID = $conn->lastInsertId();
         // add relationship (model - object)
         // second sql
         $sql  = "INSERT INTO models_objects (id_stix_model,id_stix_object) ";
         $sql .= "VALUES (:id_stix_model,:id_stix_object);";
         $stmt = $conn->prepare($sql);
         $stmt->bindParam(":id_stix_model", $id_stix_model);
         $stmt->bindParam(":id_stix_object", $lastID);         
         $stmt->execute();
         // third sql
         $sql = "UPDATE stix_models SET modified_date=now() WHERE id_stix_model=:id_stix_model;";
         $stmt = $conn->prepare($sql);
         $stmt->bindParam(":id_stix_model", $id_stix_model);
         $stmt->execute();
         // commit transaction
         $conn->commit();
      } catch(PDOException $e) {
         $conn->rollback();
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
         return $lastID;
      }
   }

   /**
       Retract a STIX object on the DB.
   */
   function update($id_stix_type, $name, $id_profile, $uuid, $json, $id_stix_object) {
      global $properties;
      $sql = "UPDATE stix_objects SET name=:name, modified_date=now(), uuid=:uuid, json=:json WHERE id_stix_object=:id_stix_object;";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_stix_object', $id_stix_object);
         $stmt->bindValue(':name', $name);
         $stmt->bindValue(':uuid', $uuid);
         $stmt->bindValue(':json', $json);
         $stmt->execute();
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }

   /**
       Retrieve a STIX object from the DB, storing in this php object.
   */
   function get($id_stix_object=-1) {
      global $properties;
      $sql  = "SELECT t.description,name,id_profile,uuid,created_date,modified_date,retracted,json ";
      $sql .= "FROM stix_objects so, stix_types t ";
      $sql .= "WHERE so.id_stix_type=t.id_stix_type AND so.id_stix_object=:id_stix_object;";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_stix_object', $id_stix_object);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         $this->type_name = $row['description'];
         $this->id_stix_object = $id_stix_object;
         $this->name = $row['name'];
         $this->uuid = $row['uuid'];
         $this->id_profile = $row['id_profile'];
         $this->created_date = $row['created_date'];
         $this->modified_date = $row['modified_date'];
         $this->retracted = $row['retracted'];
         $this->json = $row['json'];
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }

   /**
       Retrieve a list of STIX objects according to a search pattern, e.g., 'attack-pattern', or 'infrastructure' (looks the UUID column).
   */
   function retrieveList($search_pattern) {
      global $properties;
      $sql  = "SELECT st.description,name,id_profile,uuid,created_date,modified_date,retracted,json ";
      $sql .= "FROM stix_objects so, stix_types st ";
      $sql .= "WHERE so.id_stix_type=st.id_stix_type AND so.uuid LIKE ':search_pattern';";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':search_pattern', $search_pattern);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         $this->type_name = $row['description'];
         $this->id_stix_object = $id_stix_object;
         $this->name = $row['name'];
         $this->uuid = $row['uuid'];
         $this->id_profile = $row['id_profile'];
         $this->created_date = $row['created_date'];
         $this->modified_date = $row['modified_date'];
         $this->retracted = $row['retracted'];
         $this->json = $row['json'];
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }

   /**
       Retract a STIX object on the DB.
   */
   function retract($id_stix_object=-1,$retracted) {
      global $properties;
      $sql = "UPDATE stix_objects SET retracted=:retracted, modified_date=now() WHERE id_stix_object=:id_stix_object;";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_stix_object', $id_stix_object);
         $stmt->bindValue(':retracted', $retracted);
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
