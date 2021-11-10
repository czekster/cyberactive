 <?php

require_once("globals.php");


class Profile {

   function __construct() {
   }
   
   /**
      Create a new profile.
   */
   public function create($description) {
      global $properties;
      try {
         $sql = "SELECT MAX(id_profile) as max FROM profiles;";
         $conn = new PDO($properties->getStrConnection(), $properties->getUserName(), $properties->getPassword());
         // set the PDO error mode to exception
         $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $stmt = $conn->prepare($sql);
         $stmt->execute();
         $row = $stmt->fetch(PDO::FETCH_ASSOC);
         $id_profile = $row['max'] + 1; // takes next value

         $sql = "INSERT INTO profiles (id_profile, description) VALUES (:id_profile,:description);";
         $stmt = $conn->prepare($sql);
         $stmt->bindParam(":id_profile", $id_profile);
         $stmt->bindParam(":description", $description);
         $stmt->execute();
      } catch(PDOException $e) {
         echo "Error: query was '" . $sql . "'<br>Message: '" . $e->getMessage() . "'<br>\n";
         exit();
      } finally {
         $conn = null;     
         return 1;
      }
   }


}

?> 