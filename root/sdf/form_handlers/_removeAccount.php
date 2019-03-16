<?php
  include '../_importPhp.php';
  if(isset($_POST['removeAccount']))
  {
    startSession();
    if(isset($_SESSION['student']))
    {
      $user = unserialize($_SESSION['student']);
      rrmdir('../users/'.$user->getUserId().'/');
      $user->removeStudentAccount();
      // setNextAvailableAutoIncrement('store','storeid');
      // setNextAvailableAutoIncrement('discount','discountid');
      // setNextAvailableAutoIncrement('studentclaim','studentclaim_id');
      // setNextAvailableAutoIncrement('registereduser','userid')
    }elseif(isset($_SESSION['storemember']))
    {
      $user = unserialize($_SESSION['storemember']);
      foreach (unserialize($user->getStores()) as $key => $value) {
        rrmdir('../stores/'.$value.'/');
      }
      rrmdir('../users/'.$user->getUserId().'/');
      $user->RemoveAllStores();
      $user->removeStoreMemberAccount();
    }
  }
?>
