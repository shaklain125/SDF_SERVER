<?php
include '../_importPhp.php';

if (isset($_POST['signin'])) {
  $username = $_POST['signin_username'];
  $password = $_POST['signin_pw'];
  $reguser = new registereduser();
  if($reguser->SignIn($username,$password))
  {
    session_unset();
    startSession();
    SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
    $userid = $reguser->getUserId();
    if(findRowInTable('userid',$userid,'student') != null)
    {
      $reguser = new student($userid);
      $_SESSION['student'] = serialize($reguser);
    }elseif (findRowInTable('userid',$userid,'storemember') != null) {
      $reguser = new storemember($userid);
      $_SESSION['storemember'] = serialize($reguser);
    }
    echo 'Login Success';
    header('Location: ' . '../index.php');
  }else {
    echo 'Login Failed';
    errorMsg('Login failed');
  }
}else {
  header('Location: ../index.php');
}

function errorMsg($msg)
{
  session_unset();
  startSession();
  $_SESSION['errorMsg'] = $msg;
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>
