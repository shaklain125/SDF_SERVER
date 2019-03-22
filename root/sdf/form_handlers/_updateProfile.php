<?php
include '../_importPhp.php';

if(isset($_POST['userid']))
{
  // var_dump($_POST);
  // echo json_encode(array(
  //   'post' => $_POST
  // ));
  // return;
  startSession();
  // SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
  $user = null;
  if(isset($_SESSION['student']))
  {
    $user = unserialize($_SESSION['student']);
  }else {
    $user = unserialize($_SESSION['storemember']);
  }
  $userid = $_POST['userid'];
  // $input_username = $_POST['input_username'];
  $input_email = $_POST['input_email'];
  $input_fname = $_POST['input_fname'];
  $input_lname = $_POST['input_lname'];
  $input_dob = null;
  $input_university = null;
  $input_graduation = null;
  if(isset($_SESSION['student']))
  {
    $input_dob = $_POST['input_dob'];
    $input_university = $_POST['input_university'];
    $input_graduation = $_POST['input_graduation'];
  }
  $changepw = $_POST['changepw'] == 'true'?true:false;

  $profile_photo = '../users/'.$userid.'/profile.png';
  if(!is_dir('../users/'))
  {
    mkdir('../users/');
  }
  if(!is_dir('../users/'.$userid.'/'))
  {
    mkdir('../users/'.$userid.'/');
    copy('../icons/profile.png', $profile_photo);
  }

  if(!empty($_FILES['profile_image']['tmp_name']))
  {
    unlink($profile_photo);
    move_uploaded_file($_FILES['profile_image']['tmp_name'],$profile_photo);
  }

  if($_POST['removeProfilePhoto'] == 'true')
  {
    if(is_dir('../users/'.$userid.'/'))
    {
      unlink($profile_photo);
      rmdir('../users/'.$userid.'/');
    }
  }

  $categs = getCategories();
  $subCategs = getsubCategories();
  $removeSubCategs = array();
  foreach ($subCategs as $key1 => $value1) {
    $categNum = getCategoryNumb($key1);
    foreach ($categs as $key2 => $value2) {
      if(endsWith($categNum,$key2))
      {
        array_push($removeSubCategs,$key1);
        break;
      }
    }
  }
  foreach ($removeSubCategs as $key => $value) {
    unset($subCategs[$value]);
  }
  $newPass = null;
  if($changepw)
  {
    $currentPass = $_POST['currentPass'];
    $newPw = $_POST['newPw'];
    if($user->getPassword() == $currentPass)
    {
      $newPass = $newPw;
    }else {
      errorMsg('Current password incorrect', $categs, $subCategs);
      return;
    }
  }
  if(isset($_SESSION['student']))
  {
    if($user->updateStudentProfile($input_email,$input_fname,$input_lname,$newPass,$input_dob,$input_university,$input_graduation,$categs,$subCategs))
    {
      echo json_encode(array(
        'message' => 'Updated'
      ));
    }
  }else {
    if($user->updateProfile($input_email,$input_fname,$input_lname,$newPass))
    {
      echo json_encode(array(
        'message' => 'Updated',
        'fn' => $input_fname
      ));
    }
  }
}

function getCategories()
{
  $categs = array();
  foreach ($_POST as $key => $value) {
    if(startsWith("categ",$key))
    {
      $categs[$key] = $value;
    }
  }
  return $categs;
}

function getSubCategories()
{
  $subcategs = array();
  foreach ($_POST as $key => $value) {
    if(startsWith("subCateg",$key))
    {
      $subcategs[$key] = $value;
    }
  }
  return $subcategs;
}

function getCategoryNumb($subCateg) {
  $s = explode("_",$subCateg);
  return $s[1];
}

function errorMsg($msg, $prefC, $prefSc)
{
  echo json_encode(array(
    // 'prefC' => $prefC,
    // 'prefSubC' => $prefSc,
    // 'postdata' => $_POST,
    'errorMsg' => $msg
  ));
}

?>
