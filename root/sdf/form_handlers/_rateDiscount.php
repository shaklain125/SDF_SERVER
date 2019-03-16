<?php
  include '../_importPhp.php';
  startSession();
  if(isset($_POST['rate']))
  {
    $rated = $_POST['rate'] == 'GOOD'?true:false;
    $stclaim = new studentclaim($_POST['studentclaimid']);
    $setR = $stclaim->setDiscountRating($rated);
    if($setR)
    {
      $stclaim = new studentclaim($_POST['studentclaimid']);
      $r = $stclaim->getDiscountRating();
      if($r == '1')
      {
        $_SESSION['message'] = 'GOOD rating set';
      }elseif ($r == '0') {
        $_SESSION['message'] = 'BAD rating set';
      }else{
        $_SESSION['message'] = 'Removed Discount Rating';
      }
      echo json_encode(array(
        'message' => 'ok'
      ));
    }else {
    }
  }
?>
