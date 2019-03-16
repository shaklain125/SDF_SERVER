<?php
  include '../_importPhp.php';
  startSession();
  if(isset($_POST['removeStore']))
  {
    $storeid = $_POST['storeid'];
    $store = new store($storeid);
    $user = unserialize($_SESSION['storemember']);
    rrmdir('../stores/'.$_POST['storeid'].'/');
    $user->removeStore($storeid);
    echo json_encode(array(
      'message' => 'Store '.$storeid.' removed',
      'storeid' => $storeid
    ));
  }elseif (isset($_POST['removeAll'])) {
    $user = unserialize($_SESSION['storemember']);
    foreach (unserialize($user->getStores()) as $key => $value) {
      rrmdir('../stores/'.$value.'/');
    }
    $user->RemoveAllStores();
    echo json_encode(array(
      'message' => "Al stores removed"
    ));
  }
?>
