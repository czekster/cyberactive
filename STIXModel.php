<?php
require_once("globals.php");

class STIXModel {
   private $id_stix_model;
   private $name;
   private $created_date;
   private $modified_date;
   private $id_user;
   private $stix_json_model;

   function __construct() {
   }
   
   function getId() {
      return $this->id_stix_model;
   }
   
   function getName() {
      return $this->name;
   }
   
   function getCreatedDateSTIXFmt() {
      return gmdate("Y-m-d\TH:i:s\Z", strtotime($this->created_date));
   }

   function getCreatedDateFmt() {
      return gmdate("d/m/Y H:i", strtotime($this->created_date));
   }

   function getModifiedDateSTIXFmt() {
      return gmdate("Y-m-d\TH:i:s\Z", strtotime($this->modified_date));
   }

   function getModifiedDateFmt() {
      return gmdate("d/m/Y H:i", strtotime($this->modified_date));
   }

   function getIdUser() {
      return $this->id_user;
   }

   function getJSON() {
      return $this->stix_json_model;
   }

   /**
       Insert a new STIX model.
       Return the id that was inserted.
   */
   function insert($name,$id_user,$json="") {
      global $properties;
      $lastID = -1;
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         // begin a transaction
         $conn->beginTransaction();
         // first sql
         $sql = "INSERT INTO stix_models (`name`,`created_date`,`modified_date`,`id_user`,`stix_json_model`) VALUES (:name, now(), now(), :id_user, :json);";
         $stmt = $conn->prepare($sql);
         $stmt->bindParam(":name", $name);
         $stmt->bindParam(":id_user", $id_user);
         $stmt->bindParam(":json", $json);
         $stmt->execute();
         $lastID = $conn->lastInsertId();
         // second sql
         $sql = "INSERT INTO users_stix_models (`id_user`,`id_stix_model`) VALUES (:id_user, :id_stix_model);";
         $stmt = $conn->prepare($sql);
         $stmt->bindParam(":id_user", $id_user);
         $stmt->bindParam(":id_stix_model", $lastID);
         $stmt->execute();
         // commit transaction
         $conn->commit();
      } catch(PDOException $e) {
         $conn->rollback();
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         // rollback transaction
         $conn = null;
         return $lastID;
      }
   }

   /**
       Return an array with all STIX models names for this user.
   */
   function getSTIXModelNames($id_profile) {
      global $properties;
      try {
         $sql = "SELECT sm.name as name ".
                "FROM stix_models sm, profiles p, users u ".
                "WHERE u.id_profile=p.id_profile AND sm.id_user=u.id_user AND p.id_profile=:id_profile;";
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_profile', $id_profile);
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

   /**
       Return an array with all (non-retracted) STIX objects for this model.
   */
   function getAllSTIXObjects($id_user, $id_stix_model) {
      global $properties;

      $sql = "SELECT so.id_stix_object, so.uuid, st.description,so.json ".
             "FROM stix_objects so, users_stix_models usm, stix_models sm, models_objects mo, stix_types st, users u, profiles p ".
             "WHERE sm.id_stix_model=usm.id_stix_model AND ".
             "	  mo.id_stix_model=sm.id_stix_model AND ".
             "   st.id_stix_type=so.id_stix_type AND ".
             "   u.id_profile=p.id_profile AND sm.id_user=u.id_user AND ".
             "   mo.id_stix_object=so.id_stix_object AND ".
             "   sm.id_stix_model=:id_stix_model AND so.retracted=0; "; // removed an usm.id_user=:id_user AND clause here to allow users from the profile to edit the model
      try {
         //echo "sql=".$sql;
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         //$stmt->bindValue(':id_user', $id_user);
         $stmt->bindValue(':id_stix_model', $id_stix_model);
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
   
   public function getModelsDropdown($id_profile) {
      global $properties;
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $sql = "SELECT sm.id_stix_model, sm.name ".
                "FROM stix_models sm, profiles p, users u ".
                "WHERE u.id_profile=p.id_profile AND sm.id_user=u.id_user AND p.id_profile=:id_profile;";
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_profile', $id_profile);
         $stmt->execute();
         $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
         if ($rows && count($rows)>0) {
            $aux  = "\n<select name=\"id_stix_model\" style=\"width:40%;\">\n";
            foreach ($rows as $row) {
               $aux .= " <option value=\"".$row['id_stix_model']."\">".$row['name']."</option>\n";
            }
            $aux .= "</select>\n";
         } else return "No models were found.";
         return $aux;
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }
   
   /**
       Retrieve a STIX model from the DB, storing in this user.
   */
   function get($id_stix_model=-1) {
      global $properties;
      $sql = "SELECT name,created_date,modified_date,stix_json_model,id_user FROM stix_models WHERE id_stix_model=:id_stix_model;";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_stix_model', $id_stix_model);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         $this->name = $row['name'];
         $this->created_date = $row['created_date'];
         $this->modified_date = $row['modified_date'];
         $this->stix_json_model = $row['stix_json_model'];
         $this->id_user = $row['id_user'];
         $this->id_stix_model = $id_stix_model;
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }

   /**
       Update a STIX model on the DB.
   */
   function updateModifiedDate($id_stix_model=-1) {
      global $properties;
      $sql = "UPDATE stix_models SET modified_date=now() WHERE id_stix_model=:id_stix_model;";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_stix_model', $id_stix_model);
         $stmt->execute();
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
   function updateName($name, $id_stix_model=-1) {
      global $properties;
      $sql = "UPDATE stix_models SET name=:name,modified_date=now() WHERE id_stix_model=:id_stix_model;";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':name', $name);
         $stmt->bindValue(':id_stix_model', $id_stix_model);
         $stmt->execute();
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;         
      }
   }
   
   function fetchModelJSON() {
      $objs = $this->getAllSTIXObjects($this->id_user, $this->id_stix_model);
      $arr_jsons = array();
      foreach ($objs as $objects) {
         array_push($arr_jsons, $objects['json']);
      }
      $concat_json = "";
      for($i=0; $i < count($arr_jsons)-1; $i++) {
         $concat_json .= $arr_jsons[$i].",\n";
      }
      if (count($arr_jsons) > 0)
         $concat_json .= $arr_jsons[count($arr_jsons)-1]."";
      $aux = "".$this->getSTIXBundle($concat_json);
      return $aux;
   }
   

   private function getSTIXBundle($json) {
      $new_uuid = $this->generate_uuid("bundle");
      $aux = "{".
             "\"type\": \"bundle\",".
             "\"id\": \"".$new_uuid."\",".
             "\"objects\": [".$json."]}";
      return json_encode(json_decode($aux), JSON_PRETTY_PRINT);
   }

   function STIXTime() {
      return gmdate("Y-m-d\TH:i:s\Z");  // following RFC3339
      //date("Y-m-d\TH:i:sP");
      // Links:  PHP - https://www.php.net/manual/en/class.datetime.php 
      //        STIX - https://docs.oasis-open.org/cti/stix/v2.1/os/stix-v2.1-os.html#_ksbm2nost85y
   }

   function retrieveVocabulary($identifier, $name, $id_profile, $json) {
      // set a name to ease processing
      $name = "property_".$name;

      $json_file = file_get_contents("json/STIX2.1-vocabularies.json");
      $json_data = json_decode($json_file, true);

      $ids = explode(":", $identifier);
      $total_ids = count($ids);
      $type_identifier = $ids[$total_ids-1];
      //echo "total_ids=".$total_ids." ids[0]=".$ids[0];
      
      if ($total_ids == 1) {
         return $this->process1param($identifier, $name, $json);
      } else
      if ($total_ids == 2) {
         return $this->process2param($ids, $id_profile, $json_data, $name, $json);
      } else
      if ($total_ids == 3) {
         return $this->process3param($ids, $id_profile, $json_data, $name, $json);
      } else
      if ($total_ids == 4) {
         return $this->process4param($ids, $id_profile, $json_data, $name, $json);
      } else {
         return "The number of parameters is invalid.";         
      }

   }

   /**
       Return an array with all (non-retracted) STIX identifiers.
   */
   private function fetchSTIXIdentifers($id_profile) {
      global $properties;
      $sql = "SELECT so.id_stix_object, so.uuid ".
             "FROM stix_objects so, stix_types st, profiles p ".
             "WHERE so.id_stix_type=st.id_stix_type AND p.id_profile=:id_profile AND so.retracted=0 ".
             "ORDER by so.modified_date DESC;";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_profile', $id_profile);
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

   /**
      Function gets a SDO, e.g., 'attack-pattern', and it returns a list with (non-retracted) entries.
   */
   private function fetchSTIXObjectsByType($id_profile, $obj_types) {
      global $properties;
      $sql = "SELECT so.id_stix_object, so.uuid ".
             "FROM stix_objects so, stix_types st, profiles p ".
             "WHERE so.id_stix_type=st.id_stix_type AND p.id_profile=:id_profile AND so.retracted=0 AND (";
      foreach ($obj_types as $key => $value) {
         $sql .= "SUBSTRING(so.uuid, 1, locate(\"--\", so.uuid)-1) = '".$value."' OR ";
      }
      $sql = substr($sql, 0, -3);
      $sql .= ") ";
      $sql .= "ORDER by so.modified_date DESC;";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_profile', $id_profile);
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

   /**
       Return an array with all (non-retracted) STIX identifiers.
   */
   private function fetchSTIXIdentifersByType($id_profile, $arr_id_stix_type) {
      global $properties;
      $sql = "SELECT so.id_stix_object, so.uuid ".
             "FROM stix_objects so, stix_types st, profiles p ".
             "WHERE so.id_stix_type=st.id_stix_type AND p.id_profile=:id_profile AND so.retracted=0 AND (";
      foreach ($arr_id_stix_type as $value) {
         $sql .= "st.id_stix_type=".$value." OR ";
      }
      $sql = substr($sql, 0, -3); // remove the 'AND ' (from sql above)      
      $sql .= ") ";
      $sql .= "ORDER by so.modified_date DESC;";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_profile', $id_profile);
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

   /**
       Return an array with all (non-retracted) STIX identifiers.
   */
   private function fetchSTIXDictionary($id_profile,$dict_types) {
      global $properties;
      $sql = "SELECT so.id_stix_object, so.uuid ".
             "FROM stix_objects so, stix_types st, profiles p ".
             "WHERE so.id_stix_type=st.id_stix_type AND p.id_profile=:id_profile AND so.retracted=0 AND (";
      foreach ($dict_types as $key => $value) {
         $sql .= "SUBSTRING(so.uuid, 1, locate(\"--\", so.uuid)-1) = '".$value."' OR ";
      }
      $sql = substr($sql, 0, -3);
      $sql .= ");";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->bindValue(':id_profile', $id_profile);         
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

   private function process1param($identifier, $name, $json) {
      $json_decoded = json_decode($json, true);
      $aux = explode("property_", $name);
      if (isset($json_decoded[$aux[1]])) 
         $value = $json_decoded[$aux[1]];
      else $value = "";
      
      if ($identifier == "binary") 
         return "BINARY (look manual)";
      else
      if ($identifier == "RESERVED") 
         return "RESERVED (look manual)";
      else
      if ($identifier == "boolean") {
         $select  = "<select class=\"w3-input\" name=\"".$name."\">\n";
         $select .= " <option class=\"w3-input\" ".($value==""?"selected":"")."></option>\n";
         $select .= " <option class=\"w3-input\" value=\"0\" ".($value!=""&&$value==0?"selected":"").">False</option>\n";
         $select .= " <option class=\"w3-input\" value=\"1\" ".($value!=""&&$value==1?"selected":"").">True</option>\n";
         $select .= "</select>\n";
         return $select;      
      } else 
      if ($identifier == "timestamp") // just a little helper for UTC timestamps (filling the value
         return "<input class=\"w3-input\" type=\"text\" name=\"".$name."\" value=\"".($value==""?$this->STIXTime():$value)."\">\n";
      else // float, hex, integer, observable-container, string, timestamp
         return "<input class=\"w3-input\" type=\"text\" name=\"".$name."\" value=\"".$value."\">\n";
   }

   private function process2param($ids, $id_profile, $json_data, $name, $json) {
      global $STIX_TYPE_SDO;
      global $STIX_TYPE_SRO;
      global $STIX_TYPE_SCO;

      $json_decoded = json_decode($json, true);
      $aux = explode("property_", $name);
      if (isset($json_decoded[$aux[1]])) 
         $value = $json_decoded[$aux[1]];
      else $value = "";

      $type_identifier = $ids[count($ids)-1]; // discover the actual type (last value on parameter), e.g., list:string (yields 'string')
      if ($ids[0] == "identifier" && $ids[1] == "observable-container") {
         return "<input type=\"text\" name=\"".$name."\">\n";
      } else
      if ($ids[0] == "list" && $ids[1] == "string") {
         $aux2 = "";
         if ($value) // enter only on edit, not on new
            foreach ($value as $a) {
               $aux2 .= $a."\n";
            }
         return "<textarea name=\"ta_".$name."\" rows=\"3\">".$aux2."</textarea>\n";
      } else
      if ($ids[0] == "dictionary" && ($ids[1] == "string" || $ids[1] == "integer" || $ids[1] == "IPFIX-property")) {
         return "<textarea name=\"ta_".$name."\" rows=\"3\">".$value."</textarea>\n";
      } else
      //if ($ids[0] == "dictionary") { // TODO: I need to revisit the 'dictionary'...
      //   $dict_types = explode(",", $ids[1]);
      //   $arr = $this->fetchSTIXDictionary($id_profile, $dict_types);
      //} else
      if ($ids[0] == "identifier") {
         $arr_id_stix_type = array();
         if ($ids[1] == "STIX-SCO")
            $arr_id_stix_type[0] = $STIX_TYPE_SCO;
         elseif ($ids[1] == "STIX-SDO")
            $arr_id_stix_type[0] = $STIX_TYPE_SDO;
         elseif ($ids[1] == "STIX-SDO-SCO") {
            $arr_id_stix_type[0] = $STIX_TYPE_SDO;
            $arr_id_stix_type[1] = $STIX_TYPE_SCO;
         }
         $arr = $this->fetchSTIXIdentifersByType($id_profile,$arr_id_stix_type);
         $select = "<select class=\"w3-input\" name=\"sel_".$name."\">\n"; // removed the [] on the name and the 'multiple', because it's a single select here
         foreach ($arr as $objects) { //TODO: maybe one should not allow 'grouping' identifiers in the list when adding a 'grouping' STIX object
            $id_stix_object = $objects['id_stix_object'];
            $uuid = $objects['uuid'];
            //$select .= " <option value=\"".$uuid."\" ".($value!=null && array_search($uuid, $value) !== false ? "selected" : "").">".$uuid."</option>\n";
            $select .= " <option value=\"".$uuid."\" ".($value!=null && $uuid == $value ? "selected" : "").">".$uuid."</option>\n";
         }
         $select .= "</select>\n";
         return $select;
      } else
      if ($ids[0] == "list" && $ids[1] == "identifier") {
         $arr = $this->fetchSTIXIdentifers($id_profile);
         $select = "<select class=\"w3-input\" name=\"sel_".$name."[]\" multiple>\n";
         foreach ($arr as $objects) { //TODO: maybe one should not allow 'grouping' identifiers in the list when adding a 'grouping' STIX object
            $id_stix_object = $objects['id_stix_object'];
            $uuid = $objects['uuid'];
            $select .= " <option value=\"".$uuid."\" ".($value!=null && array_search($uuid, $value) !== false ? "selected" : "").">".$uuid."</option>\n";
         }
         $select .= "</select>\n";
         return $select;
      } else
      if ($ids[0] == "open-vocab" || $ids[0] == "enum" || $ids[0] == "hashes") {
            $keys = array_keys($json_data['STIX Vocabularies'][$type_identifier]);
            $select = "\n<select class=\"w3-input\" name=\"sel_".$name."[]\">\n";
            foreach ($keys as $key => $v) {
               $select .= " <option value=\"".$v."\" ".($value!=null && array_search($v, $value) !== false ? "selected" : "").">".$v."</option>\n";
            }
            $select .= "</select>\n";
            return $select;
      } else
         return "<font face=\"Courier new\">&lt;undergoing implementation - 2&gt;</font>";
   }

   private function process3param($ids, $id_profile, $json_data, $name, $json) {
      global $STIX_TYPE_SDO;
      global $STIX_TYPE_SRO;
      global $STIX_TYPE_SCO;
      $type_identifier = $ids[count($ids)-1]; // discover the actual type (last value on parameter), e.g., list:string (yields 'string')

      $json_decoded = json_decode($json, true);
      $aux = explode("property_", $name);
      if (isset($json_decoded[$aux[1]])) 
         $value = $json_decoded[$aux[1]];
      else $value = null;

      //if ($ids[0] == "identifier") {
      //   return "<input type=\"text\" name=\"".$name."\">\n";
      //} else
      if ($ids[0] == "list" && $ids[1] == "open-vocab") {
         $keys = array_keys($json_data['STIX Vocabularies'][$type_identifier]);
         $select = "\n<select class=\"w3-input\" name=\"sel_".$name."[]\" multiple>\n";
         foreach ($keys as $key => $v) {
            $select .= " <option value=\"".$v."\" ".($value!=null && array_search($v, $value) !== false ? "selected" : "").">".$v."</option>\n";
         }
         $select .= "</select>\n";
         return $select;
      } else
      if ($ids[0] == "list" && $ids[1] == "identifier" && ($ids[2] == "marking-definition" || $ids[2] == "kill-chain-phase")) // TODO: implementation needed here to fix this!
         return "<font face=\"Courier new\">&lt;undergoing implementation - 3&gt;</font>";
      else
      if ($ids[0] == "list" && $ids[1] == "identifier") {
         $arr_id_stix_type = array();
         if ($ids[2] == "STIX-SCO")
            $arr_id_stix_type[0] = $STIX_TYPE_SCO;
         elseif ($ids[2] == "STIX-SCO-SRO") {
            $arr_id_stix_type[0] = $STIX_TYPE_SCO;
            $arr_id_stix_type[1] = $STIX_TYPE_SRO;
         } elseif ($ids[2] == "STIX-SDO-SCO") {
            $arr_id_stix_type[0] = $STIX_TYPE_SDO;
            $arr_id_stix_type[1] = $STIX_TYPE_SCO;
         }
         $arr = $this->fetchSTIXIdentifersByType($id_profile,$arr_id_stix_type);
         $select = "<select class=\"w3-input\" name=\"sel_".$name."[]\" multiple>\n";
         foreach ($arr as $objects) { //TODO: maybe one should not allow 'grouping' identifiers in the list when adding a 'grouping' STIX object
            $id_stix_object = $objects['id_stix_object'];
            $uuid = $objects['uuid'];
            $select .= " <option value=\"".$uuid."\" ".($value!=null && array_search($uuid, $value) !== false ? "selected" : "").">".$uuid."</option>\n";
         }
         $select .= "</select>\n";
         return $select;
      } else 
      if ($ids[0] == "identifier" && ($ids[1] == "SCO" || $ids[1] == "SDO")) {
         $obj_types = explode(",", $ids[2]);
         $arr = $this->fetchSTIXObjectsByType($id_profile, $obj_types);
         $select = "<select class=\"w3-input\" name=\"sel_".$name."\">\n";
         $select .= " <option class=\"w3-input\" ".($value==""?"selected":"")."></option>\n";
         foreach ($arr as $objects) { //TODO: maybe one should not allow 'grouping' identifiers in the list when adding a 'grouping' STIX object
            $id_stix_object = $objects['id_stix_object'];
            $uuid = $objects['uuid'];
            if (is_array($value))
               $select .= " <option value=\"".$uuid."\" ".($value!=null && array_search($uuid, $value) !== false ? "selected" : "").">".$uuid."</option>\n";
            else
               $select .= " <option value=\"".$uuid."\" ".($value!=null && $uuid==$value ? "selected" : "").">".$uuid."</option>\n";
         }
         $select .= "</select>\n";
         return $select;
      } else
         return "<font face=\"Courier new\">&lt;undergoing implementation - 3&gt;</font>";
   }

   private function process4param($ids, $id_profile, $json_data, $name, $json) {
      $json_decoded = json_decode($json, true);
      $aux = explode("property_", $name);
      if (isset($json_decoded[$aux[1]])) 
         $value = $json_decoded[$aux[1]];
      else $value = null;

      if ($ids[0] == "list" && $ids[1] == "identifier" && ($ids[2] == "SCO" || $ids[2] == "SDO")) {
         $obj_types = explode(",", $ids[3]);
         $arr = $this->fetchSTIXObjectsByType($id_profile, $obj_types);
         $select = "<select class=\"w3-input\" name=\"sel_".$name."[]\" multiple>\n";
         foreach ($arr as $objects) { //TODO: maybe one should not allow 'grouping' identifiers in the list when adding a 'grouping' STIX object
            $id_stix_object = $objects['id_stix_object'];
            $uuid = $objects['uuid'];
            $select .= " <option value=\"".$uuid."\" ".($value!=null && array_search($uuid, $value) !== false ? "selected" : "").">".$uuid."</option>\n";
         }
         $select .= "</select>\n";
         return $select;
      } else
         return "<font face=\"Courier new\">&lt;undergoing implementation - 4&gt;</font>";
   }

   /**
      Generates unique identifiers (UUID)
   */
   function generate_uuid($STIX_type) {
      return $this->generate_stix_uuid(null, $STIX_type);
   }
   
   function generate_stix_uuid($data = null, $STIX_type) {
       // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
       $data = $data ?? random_bytes(16);
       assert(strlen($data) == 16);

       // Set version to 0100
       $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
       // Set bits 6-7 to 10
       $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

       // Output the 36 character UUID.
       return $STIX_type."--".vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
   }

}

?>
