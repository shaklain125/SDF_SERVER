<?php
$database1 = database::getInstance();

function getQueryResult($query, $conn)
{
  return $GLOBALS['database1']->getQueryResult($query, $conn);
}

function createSqlConn()
{
  return $GLOBALS['database1']->createSqlConn();
}

function closeSqlConn($conn)
{
  $GLOBALS['database1']->closeSqlConn($conn);
}

function realSqlString($val, $conn) {
  return $GLOBALS['database1']->realSqlString($val, $conn);
}

function SqlResultToArray($query, $conn)
{
  return $GLOBALS['database1']->SqlResultToArray($query, $conn);
}

function isTableEmpty($tablename, $conn)
{
  return $GLOBALS['database1']->isTableEmpty($tablename, $conn);
}

function setAutoIncrement($tablename, $value, $conn)
{
  return $GLOBALS['database1']->setAutoIncrement($tablename, $value, $conn);
}

function getTableSize($tablename) {
  return $GLOBALS['database1']->getTableSize($tablename);
}

function findRowInTable($column,$col_value,$table)
{
  return $GLOBALS['database1']->findRowInTable($column,$col_value,$table);
}

function ResetTableAutoincrement($tablename) {
  return $GLOBALS['database1']->ResetTableAutoincrement($tablename);
}

function setNextAvailableAutoIncrement($table,$incrCol) {
  return $GLOBALS['database1']->setNextAvailableAutoIncrement($table,$incrCol);
}

//Page X,Y Coordinates

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


//Directory

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

//Session

function startSession() {
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }
}

function LoggedIn() {
  return isset($_SESSION['student']) || isset($_SESSION['storemember']);
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

function getSessionDivMsg($timeoutMs) {
  if(isset($_SESSION['message']))
  {
    $msg = $_SESSION['message'];
    unset($_SESSION['message']);
    return 'ShowSessionDivMsg("'.$msg.'");setTimeout("HideSessionDivMsg()",'.$timeoutMs.')';
  }
}



//String/Date/Integer/Array Functions

function startsWith($val,$str) {
  return substr($str, 0, strlen($val)) == $val;
}

function endsWith($val,$str) {
  return substr($str, strlen($str) - strlen($val), strlen($str)) == $val;
}

function printval($val) {
  print("<pre style='white-space: pre-wrap; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; word-wrap: break-word;'>".print_r($val,true)."</pre>");
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

function searchArr($arr, $val) {
  foreach ($arr as $key => $value) {
    if($value == $val)
    {
      return true;
    }
  }
  return false;
}



//Pagination

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
