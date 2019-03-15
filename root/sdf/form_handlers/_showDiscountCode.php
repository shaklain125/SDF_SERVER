<?php
  include '../_importPhp.php';
  startSession();
  if(isset($_POST['showcode']))
  {
    SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
    $discount = new discount($_POST['discountid']);
    $sclaim = new studentclaim($_POST['studentclaimid']);
    $_SESSION['dcodeData'] = serialize(array($discount,$sclaim));
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
?>
