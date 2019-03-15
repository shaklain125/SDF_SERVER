<?php
  include '../_importPhp.php';
  startSession();
  if(isset($_POST['usecode']))
  {
    SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
    $sclaim = new studentclaim($_POST['studentclaimid']);
    if($sclaim->UseCode())
    {
      $_SESSION['message'] = 'Code Used';
    }else {
      $_SESSION['message'] = 'Code Not Used';
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
?>
