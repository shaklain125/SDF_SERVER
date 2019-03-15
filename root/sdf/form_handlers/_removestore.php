<?php
  include '../_importPhp.php';
  if(isset($_POST['removeStore']))
  {
    startSession();
    SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
    $storeid = $_POST['storeid'];
    $store = new store($storeid);
    $user = unserialize($_SESSION['storemember']);
    $user->removeStore($storeid);
    $_SESSION['message'] = '\"'.$store->getName().'\" removed';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }elseif (isset($_POST['removeAll'])) {
    $user = unserialize($_SESSION['storemember']);
    $user->RemoveAllStores();
    $_SESSION['message'] = "Al stores removed";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
?>
