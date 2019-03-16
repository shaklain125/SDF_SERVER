<?php
  include '../_importPhp.php';
  startSession();
  if(isset($_POST['liveSearch']))
  {
    $q = urldecode($_POST['liveSearch']);
    $conn = createSqlConn();
    $results = SqlResultToArray('SELECT name, storeid from discount where name like "%'.$q.'%" AND discount.expire_date > "'.date('Y-m-d').'" union select name, storeid from store where name like "%'.$q.'%"',$conn);
    closeSqlConn($conn);
    $resultsNames = array();
    foreach ($results as $key => $value) {
      $resultsNames[$value['storeid']] = $value['name'];
    }
    echo json_encode(array(
      'results' => $resultsNames
    ));
  }
?>
