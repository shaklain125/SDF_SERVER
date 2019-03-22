<?php
/**
 *
 */
class database
{
  private static $instance;

  public static function getInstance()
  {
    if (self::$instance == null)
    {
      self::$instance = new database();
    }
    return self::$instance;
  }

  public function getQueryResult($query, $conn)
  {
    return $conn->query($query);
  }

  public function createSqlConn()
  {
    $conn = new mysqli('127.0.0.1', 'root', 'usbw', 'sdf_db');
    if ($conn->connect_error) {
        die('Connect Error (' . $conn->connect_errno . ') '.$conn->connect_error);
        return null;
    }
    return $conn;
  }

  public function closeSqlConn($conn)
  {
    $conn->close();
  }

  public function realSqlString($val, $conn) {
    return $conn->real_escape_string($val);
  }

  public function SqlResultToArray($query, $conn)
  {
    $result = $this->getQueryResult($query,$conn);
    $arr = array();
    while($row = $result->fetch_assoc())
    {
      array_push($arr,$row);
    }
    return $arr;
  }

  public function isTableEmpty($tablename, $conn) {
    $q = "SELECT * FROM ".$tablename;
    return sizeof($this->SqlResultToArray($q,$conn)) == 0;
  }

  public function setAutoIncrement($tablename, $value, $conn)
  {
    $q = "ALTER TABLE ".$tablename." AUTO_INCREMENT = ".$value;
    return $this->getQueryResult($q,$conn);
  }

  public function getTableSize($tablename) {
    $conn = $this->createSqlConn();
    $s = sizeof($this->SqlResultToArray("select * from ".$tablename, $conn));
    $this->closeSqlConn($conn);
    return $s;
  }

  public function findRowInTable($column,$col_value,$table)
  {
    $conn = $this->createSqlConn();
    $searchTable = $this->SqlResultToArray("select * from $table where $column=$col_value",$conn);
    $this->closeSqlConn($conn);
    if(sizeof($searchTable) == 0)
    {
      return null;
    }else {
      return $searchTable;
    }
  }

  public function ResetTableAutoincrement($tablename) {
    $conn = $this->createSqlConn();
    if($this->isTableEmpty($tablename,$conn))
    {
      return $this->setAutoIncrement($tablename,1,$conn);
    }else {
      return $this->setAutoIncrement($tablename,$this->getTableSize($tablename)+1,$conn);
    }
    $this->closeSqlConn($conn);
  }

  public function setNextAvailableAutoIncrement($table,$incrCol) {
    $conn = $this->createSqlConn();
    $q = 'SELECT t1.'.$incrCol.'+1 AS MISSING_ID FROM '.$table.' AS t1 LEFT JOIN '.$table.' AS t2 ON t1.'.$incrCol.'+1 = t2.'.$incrCol.' WHERE t2.'.$incrCol.' IS NULL ORDER BY t1.'.$incrCol.' LIMIT 1';
    $incrementVal = $this->SqlResultToArray($q,$conn);
    $incrementVal  = (int) $incrementVal[0]['MISSING_ID'];
    $result = $this->setAutoIncrement($table,$incrementVal,$conn);
    $this->closeSqlConn($conn);
    return $result;
  }
}

?>
