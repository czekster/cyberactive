 <?php

require_once("globals.php");


class MyPDO {

   function __construct() {
   }
   
   /**
      This function will print *any* table, taking three parameters:
      1. table name
      2. limit of records (number of entries)
      3. start (offset) entry number
   */
   function dumpTable($tableName, $LIMIT=25, $OFFSET=0) {
      global $properties;
      $sql = "SELECT * FROM ".$tableName." LIMIT ".$LIMIT." OFFSET ".$OFFSET.";";
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->execute();

         // set the resulting array to associative
         $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
         $tableRow = new TableRows(new RecursiveArrayIterator($stmt->fetchAll()));
         $tableRow->setNCols($stmt->columnCount());
         $columnNames = $this->getColumnNames("users");
         $tableRow->beginHeader($columnNames);
         foreach($tableRow as $k=>$v) {
            echo $v;
         }
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      }
      $conn = null;
   }
   
   //Get Columns
   private function getColumnNames($tableName) {
      global $properties;
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

         // get column names
         $stmt = $conn->prepare("DESCRIBE $tableName");
         $stmt->execute();
         $columnNames = $stmt->fetchAll(PDO::FETCH_COLUMN);
         $conn = null;
         return $columnNames;
      } catch(PDOExcepetion $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      }
   }

   /**
      Function to clean all data from all tables (administration only).
   */
   function truncateAllTables() {
      global $properties;
      try {
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         // begin a transaction
         $conn->beginTransaction();
         // set sql
         $sql = "SET FOREIGN_KEY_CHECKS = 0;";
         $sql .= "TRUNCATE TABLE models_objects;";
         $sql .= "TRUNCATE TABLE stix_objects;";
         $sql .= "TRUNCATE TABLE stix_models;";
         $sql .= "TRUNCATE TABLE users_stix_models;";
         $sql .= "TRUNCATE TABLE profiles;";
         $sql .= "TRUNCATE TABLE users;";
         $sql .= "TRUNCATE TABLE user_profiles;";
         $sql .= "SET FOREIGN_KEY_CHECKS = 1;";
         $stmt = $conn->prepare($sql);
         $stmt->execute();
         // commit transaction
         $conn->commit();
         return 1;
      } catch(PDOException $e) {
         $conn->rollback();
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;
      }
   }
   
   /**
      Function to dump (save) all the db (before truncating, maybe?)
   */
   function DumpDB() {
      return 1;
   }

}

?> 