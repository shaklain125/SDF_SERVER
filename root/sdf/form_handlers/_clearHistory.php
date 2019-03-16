<?php
  include '../_importPhp.php';
  startSession();
  if(isset($_POST['clearHistory']))
  {
    $user = unserialize($_SESSION['student']);
    $user->clearStoreHistory();
    echo json_encode(array(
      'message' => 'ok'
    ));
  }
?>
