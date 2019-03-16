<?php
  include '../_importPhp.php';
  startSession();
  if(isset($_POST['showcode']))
  {
    // SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
    $discount = new discount($_POST['discountid']);
    $sclaim = new studentclaim($_POST['studentclaimid']);
    $_SESSION['dcodeData'] = serialize(array($discount,$sclaim));
    echo json_encode(array(
      'message' => 'ok',
      'storename' => (new store($discount->getStoreId()))->getName(),
      'dname' => $discount->getPercent().'% OFF '.$discount->getName(),
      'subcateg' => $discount->getSubCategory(),
      'expire' => 'Expires: '.$discount->getExpireDate(),
      'code' => $discount->getCode(),
      'sclaimid' => $sclaim->getStudentClaimID()
    ));
  }
?>
