<?php
  include '../_importPhp.php';
  startSession();
  if(isset($_POST['clearHistory']))
  {
    SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
    $user = unserialize($_SESSION['student']);
    $user->clearStoreHistory();
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
?>
