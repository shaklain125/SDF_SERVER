<?php
    include '../_importPhp.php';
    startSession();
    if(isset($_POST['style']))
    {
      $user = null;
      if(isset($_SESSION['student']))
      {
        $user = unserialize($_SESSION['student']);
      }elseif (isset($_SESSION['storemember'])) {
        $user = unserialize($_SESSION['storemember']);
      }
      $user->setStylePref($_POST['style'] == 'ON'?true:false);
      echo json_encode(array(
        'message' => 'ok'
      ));
    }else {
    }
?>
