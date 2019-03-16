<?php
  include '../_importPhp.php';
  startSession();
  if(isset($_POST['like']))
  {
    // SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
    $user = unserialize($_SESSION['student']);
    if($user->ToggleLikes($_POST['storeid']))
    {
      $store = new store($_POST['storeid']);
      $rating = array($store->getLikes(),$store->getDislikes());
      returnResp($user,$store,$rating);
    }
    // header('Location: ' . $_SERVER['HTTP_REFERER']);
  }elseif (isset($_POST['dislike'])) {
    // SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
    $user = unserialize($_SESSION['student']);
    if($user->ToggleDisLikes($_POST['storeid']))
    {
      $store = new store($_POST['storeid']);
      $rating = array($store->getLikes(),$store->getDislikes());
      returnResp($user,$store,$rating);
    }
    // header('Location: ' . $_SERVER['HTTP_REFERER']);
  }

  function returnResp($user,$store,$rating) {
    $active = null;
    $active2 = null;
    if($user->hasLikedStore($store->getStoreId()))
    {
      $active = 'activeLikeBtn';
    }
    if($user->hasDislikedStore($store->getStoreId()))
    {
      $active2 = 'activeDislikeBtn';
    }
    echo json_encode(array($rating, $active, $active2));
  }
?>
