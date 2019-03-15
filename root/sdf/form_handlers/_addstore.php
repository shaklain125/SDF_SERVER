<?php
include '../_importPhp.php';

if(isset($_POST['addStore']))
{
  SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
  $postdata = $_POST;
  if($postdata['input_store_descr'] == null)
  {
    unset($postdata['input_store_descr']);
  }
  if(ArrayNotNull($postdata))
  {
    $postdata = $_POST;
    startSession();
    $user = unserialize($_SESSION['storemember']);
    $storename = $postdata['input_store_name'];
    $storedescr = $postdata['input_store_descr'];
    $storephone = $postdata['input_store_phone'];
    $storewebsite = $postdata['input_store_website'];
    $storecateg = $postdata['input_store_category'];
    if($user->AddStore($storename,$storedescr,$storephone,$storewebsite,$storecateg))
    {
      echo 'Added';
      header('Location: ../managestores.php');
    }else {
      errorMsg('Not Added');
    }
  }else {
    errorMsg('Please fill the fields required');
  }
}

function errorMsg($msg)
{
  startSession();
  $_SESSION['postdata'] = $_POST;
  $_SESSION['errorMsg'] = $msg;
  header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>
