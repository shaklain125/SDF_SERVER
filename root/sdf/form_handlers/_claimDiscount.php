<?php
  include '../_importPhp.php';
  startSession();
  if(isset($_POST['claimDiscount']))
  {
    SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
    $user = unserialize($_SESSION['student']);
    $exists = $user->isDiscountIDInClaimedList($_POST['discountid']);
    if($exists)
    {
      $_SESSION['message'] = 'Discount is already claimed';
    }else {
      if($user->claimDiscount($_POST['discountid']))
      {
        $_SESSION['message'] = 'Store discount claimed';
      }else {
        $_SESSION['message'] = 'Store discount not claimed';
      }
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
?>
