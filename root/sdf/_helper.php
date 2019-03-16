<?php
function ArrayNotNull($arr)
{
  foreach ($arr as $field) {
    if(strlen($field) == 0)
    {
      return false;
    }
  }
  return true;
}
function getQueryResult($query, $conn)
{
  return $conn->query($query);
}

function createSqlConn()
{
  $conn = new mysqli('127.0.0.1', 'root', 'usbw', 'sdf_db');
  if ($conn->connect_error) {
      die('Connect Error (' . $conn->connect_errno . ') '.$conn->connect_error);
      return null;
  }
  return $conn;
}

function closeSqlConn($conn)
{
  $conn->close();
}

function realSqlString($val, $conn) {
  return $conn->real_escape_string($val);
}

function SqlResultToArray($query, $conn)
{
  $result = getQueryResult($query,$conn);
  $arr = array();
  while($row = $result->fetch_assoc())
  {
    array_push($arr,$row);
  }
  return $arr;
}

function isTableEmpty($tablename, $conn) {
  $q = "SELECT * FROM ".$tablename;
  return sizeof(SqlResultToArray($q,$conn)) == 0;
}

function setAutoIncrement($tablename, $value, $conn)
{
  $q = "ALTER TABLE ".$tablename." AUTO_INCREMENT = ".$value;
  return getQueryResult($q,$conn);
}

function getTableSize($tablename) {
  $conn = createSqlConn();
  $s = sizeof(SqlResultToArray("select * from ".$tablename, $conn));
  closeSqlConn($conn);
  return $s;
}

function findRowInTable($column,$col_value,$table)
{
  $conn = createSqlConn();
  $searchTable = SqlResultToArray("select * from $table where $column=$col_value",$conn);
  closeSqlConn($conn);
  if(sizeof($searchTable) == 0)
  {
    return null;
  }else {
    return $searchTable;
  }
}

function ShowError()
{
  $error = $_SESSION['errorMsg'];
  unset($_SESSION['errorMsg']);
  return $error;
}
function errorExists()
{
  if(isset($_SESSION['errorMsg']))
  {
    return '';
  }else {
    return 'none';
  }
}
function getTodayDate()
{
  $d = getdate();
  return $d['year'].'-'.getZeroFrontDigit($d['mon']).'-'.getZeroFrontDigit($d['mday']);
}
function getZeroFrontDigit($numb)
{
  return (($numb >= 0) && ($numb <= 9)) ? '0'.$numb : $numb;
}

function keepData($val, $final)
{
  if(isset($_SESSION['postdata']))
  {
    $post = $_SESSION['postdata'];
    if($final)
    {
      unset($_SESSION['postdata']);
    }
    foreach ($post as $key => $value) {
      if($key == $val)
      {
        return $post[$key];
      }
    }
    return '';
  }else {
    return '';
  }
}

function startSession() {
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }
}

function rrmdir($dir) { 
   if (is_dir($dir)) {
     $objects = scandir($dir);
     foreach ($objects as $object) {
       if ($object != "." && $object != "..") {
         if (is_dir($dir."/".$object))
           rrmdir($dir."/".$object);
         else
           unlink($dir."/".$object);
       }
     }
     rmdir($dir);
   }
 }

function startsWith($val,$str) {
  return substr($str, 0, strlen($val)) == $val;
}
function endsWith($val,$str) {
  return substr($str, strlen($str) - strlen($val), strlen($str)) == $val;
}

function printval($val) {
  print("<pre style='white-space: pre-wrap; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; word-wrap: break-word;'>".print_r($val,true)."</pre>");
}

function LoggedIn() {
  return isset($_SESSION['student']) || isset($_SESSION['storemember']);
}

function getSessionDivMsg($timeoutMs) {
  if(isset($_SESSION['message']))
  {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
    return 'ShowSessionDivMsg("'.$msg.'");setTimeout("HideSessionDivMsg()",'.$timeoutMs.')';
  }
}

function SetXandYScrollSession($x,$y) {
  $_SESSION['scrollx'] = $x;
  $_SESSION['scrolly'] = $y;
}

function GetXandYScrollPositions() {
  startSession();
  if(isset($_SESSION['scrollx']) && isset($_SESSION['scrolly']))
  {
    $x = $_SESSION['scrollx'];
    $y = $_SESSION['scrolly'];
    unset($_SESSION['scrollx']);
    unset($_SESSION['scrolly']);
    return 'window.scrollTo('.$x.', '.$y.');';
  }else {
    return '';
  }
}

function ResetTableAutoincrement($tablename) {
  $conn = createSqlConn();
  if(isTableEmpty($tablename,$conn))
  {
    return setAutoIncrement($tablename,1,$conn);
  }else {
    return setAutoIncrement($tablename,getTableSize($tablename)+1,$conn);
  }
  closeSqlConn($conn);
}

function setNextAvailableAutoIncrement($table,$incrCol) {
  $conn = createSqlConn();
  $q = 'SELECT t1.'.$incrCol.'+1 AS MISSING_ID FROM '.$table.' AS t1 LEFT JOIN '.$table.' AS t2 ON t1.'.$incrCol.'+1 = t2.'.$incrCol.' WHERE t2.'.$incrCol.' IS NULL ORDER BY t1.'.$incrCol.' LIMIT 1';
  $incrementVal = SqlResultToArray($q,$conn);
  $incrementVal  = (int) $incrementVal[0]['MISSING_ID'];
  $result = setAutoIncrement($table,$incrementVal,$conn);
  closeSqlConn($conn);
  return $result;
}

function searchArr($arr, $val) {
  foreach ($arr as $key => $value) {
    if($value == $val)
    {
      return true;
    }
  }
  return false;
}

function getSearchPagination($query, $totalNofResults, $results_per_page, $page, $number_of_pages) {
  echo '<div style="text-align:center;">';
  if(($page-1) > 0)
  {
    echo '<a href="search.php?q='.urlencode($query).'&page='.($page-1).'" style="margin-right:10px;" class="linkBtn">Previous</a>';
  }
  echo '<span>Page '.$page.' of '.$number_of_pages.'</span>';
  if(($page+1) <= $number_of_pages)
  {
    echo '<a href="search.php?q='.urlencode($query).'&page='.($page+1).'" style="margin-left:10px;" class="linkBtn">Next</a>';
  }
  echo '</div>';
}

function getHistoryPagination($totalNofResults, $results_per_page, $page, $number_of_pages) {
  echo '<div style="text-align:center;">';
  if(($page-1) > 0)
  {
    echo '<a href="history.php?page='.($page-1).'" style="margin-right:10px;" class="linkBtn">Previous</a>';
  }
  echo '<span>Page '.$page.' of '.$number_of_pages.'</span>';
  if(($page+1) <= $number_of_pages)
  {
    echo '<a href="history.php?page='.($page+1).'" style="margin-left:10px;" class="linkBtn">Next</a>';
  }
  echo '</div>';
}

function getFavStoresPagination($totalNofResults, $results_per_page, $page, $number_of_pages) {
  echo '<div style="text-align:center;">';
  if(($page-1) > 0)
  {
    echo '<a href="favouritestores.php?page='.($page-1).'" style="margin-right:10px;" class="linkBtn">Previous</a>';
  }
  echo '<span>Page '.$page.' of '.$number_of_pages.'</span>';
  if(($page+1) <= $number_of_pages)
  {
    echo '<a href="favouritestores.php?page='.($page+1).'" style="margin-left:10px;" class="linkBtn">Next</a>';
  }
  echo '</div>';
}


function getPageNumber() {
  if(isset($_GET['page']))
  {
    return $_GET['page'];
  }else {
    return 1;
  }
}
?>
