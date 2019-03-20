<?php
  include '../_importPhp.php';
  if(isset($_POST['store_id']))
  {
    startSession();
    // SetXandYScrollSession($_POST['scrollx'],$_POST['scrolly']);
    $user = unserialize($_SESSION['storemember']);
    $storeid = $_POST['store_id'];
    $storename = $_POST['input_store_name'];
    $storewebsite = $_POST['input_store_website'];
    $storephone = $_POST['input_store_phone'];
    $storecateg = $_POST['input_store_category'];
    $storedescr = $_POST['input_store_descr'];
    $newDiscounts = json_decode($_POST['newDiscounts'],true);
    $discountsToRemove = json_decode($_POST['discountsToRemove'],true);
    setStorePhoto($storeid);
    foreach ($discountsToRemove as $key => $value) {
      if($user->DeleteDiscount($value))
      {
        // echo 'Discount '.$value.' deleted <br />';
      }else {
        // echo 'Discount '.$value.' NOT deleted <br />';
      }
    }
    // echo '<br />';
    if($user->EditStorePage($storeid,$storename,$storedescr,$storephone,$storewebsite,$storecateg))
    {
      // echo 'Store info. updated';
    }
    // echo '<br />';
    if(sizeof($newDiscounts) > 0)
    {
      foreach ($newDiscounts as $key => $value) {
        $discountName = $value['input_discount_name'];
        $discountPercent = $value['input_discount_percent'];
        $discountStartD = $value['input_discount_start'];
        $discountEndD = $value['input_discount_expire'];
        $discountSubCateg = $value['input_discount_subcateg'];
        if($user->AddDiscount($storeid,$discountName,$discountPercent,$discountStartD,$discountEndD,$discountSubCateg,15))
        {
          // echo 'created discount: '.$discountName;
        }else {
          // echo 'not created discount: '.$discountName;
        }
      }
    }else {
      // echo 'No new discounts added';
    }
    $_SESSION['message'] = 'Modifications saved';
    echo json_encode(array(
      'message' => 'ok'
    ));

  }

  function setStorePhoto($storeid) {
    $store_photo = '../stores/'.$storeid.'/store.jpg';
    if(!is_dir('../stores/'))
    {
      mkdir('../stores/');
    }
    if(!is_dir('../stores/'.$storeid.'/'))
    {
      mkdir('../stores/'.$storeid.'/');
      copy('../icons/store.jpg', $store_photo);
    }

    if(!empty($_FILES['store_image']['tmp_name']))
    {
      unlink($store_photo);
      move_uploaded_file($_FILES['store_image']['tmp_name'],$store_photo);
    }

    if($_POST['removeStorePhoto'] == 'true')
    {
      if(is_dir('../stores/'.$storeid.'/'))
      {
        unlink($store_photo);
        rmdir('../stores/'.$storeid.'/');
      }
    }
  }
?>
