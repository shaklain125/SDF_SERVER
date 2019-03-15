<?php
  include '../_importPhp.php';
  startSession();
  if(isset($_POST['like']))
  {
    SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
    $user = unserialize($_SESSION['student']);
    if($user->ToggleLikes($_POST['storeid']))
    {
      echo "Liked";
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }elseif (isset($_POST['dislike'])) {
    SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
    $user = unserialize($_SESSION['student']);
    if($user->ToggleDisLikes($_POST['storeid']))
    {
      echo "Disliked";
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
  }
?>
