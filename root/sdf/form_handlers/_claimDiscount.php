<?php
  include '../_importPhp.php';
  startSession();
  if(isset($_POST['claimDiscount']))
  {
    // SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
    $user = unserialize($_SESSION['student']);
    $message = null;
    $exists = $user->isDiscountIDInClaimedList($_POST['discountid']);
    if($exists)
    {
      $message = 'Discount is already claimed';
    }else {
      if($user->claimDiscount($_POST['discountid']))
      {
        $message = 'Store discount claimed';
      }else {
        $message = 'Store discount not claimed';
      }
    }
    echo json_encode(array(
      'message' => $message
    ));
    // header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
?>
