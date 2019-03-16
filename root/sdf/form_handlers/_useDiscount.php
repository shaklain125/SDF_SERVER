<?php
  include '../_importPhp.php';
  startSession();
  if(isset($_POST['usecode']))
  {
    $sclaim = new studentclaim($_POST['studentclaimid']);
    if($sclaim->UseCode())
    {
      $_SESSION['message'] = 'Code Used';
      echo json_encode(array(
        'message' => 'Code Used'
      ));
    }else {
      $_SESSION['message'] = 'Code Not Used';
      echo json_encode(array(
        'message' => 'Code Not Used'
      ));
    }
  }
?>
