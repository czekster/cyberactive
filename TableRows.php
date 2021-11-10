<?php
//Source: https://www.w3schools.com/php/php_mysql_select.asp


//TODO: here it's printing static two columns; make it print N columns...
class TableRows extends RecursiveIteratorIterator {
   private $nCols;

   function __construct($it) {
      parent::__construct($it, self::LEAVES_ONLY);
      echo "<table style='border: solid 1px black;'>\n";
   }

   function current() {
      return "  <td style='width:150px;border:1px solid black;'>" . parent::current(). "</td>\n";
   }
   
   function setNCols($ncols) {
      $this->nCols = $ncols;
   }
   
   function beginHeader($fields) {
      $str = "<tr>\n";
      foreach ($fields as $field) {
         $str .= "<th>".$field."</th>\n";
      }
      $str .= "</tr>\n";
      echo $str;
   }

   function beginChildren() {
      echo " <tr>\n";
   }

   function endChildren() {
      echo " </tr>" . "\n";
   }
   
   function __destruct() {
      echo "</table>\n";
   }
}
