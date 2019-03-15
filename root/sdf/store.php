<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <?php include '_importStyle.php'; ?>
    <style media="screen">
    .storePhotoPage{
      object-fit: cover;
      width: 560px;
      height: 336px;
    }
    </style>
  </head>
  <body>
    <?php
      include '_importPhp.php';
      include '_checkLoggedIn.php';
      startSession();
    ?>
    <div id="container">
      <?php
      include '_fixedHeaderAndSideBar.php'
       ?>
      <div id="contentContainer">
        <div id="content">
          <div>
            <a class="linkBtn" href="index.php">Go Home</a>
          </div>
          <?php
          $store = null;
          if(isset($_GET['id']) and $_GET['id'] != null)
          {
            $store = new store($_GET['id']);
            if(!$store->isNull())
            {
          ?>
          <div style="padding:20px; text-align:center;">
            <h1 style="padding:0;margin:0; font-family:arial;"><?php echo $store->getName();?></h1>
          </div>
          <div>
            <?php
            if(isset($_SESSION['student']))
            {
              $user = unserialize($_SESSION['student']);
              $user->addStoreToHistory($store->getStoreId());
              $active = '';
              $active2 ='';
              if($user->hasLikedStore($store->getStoreId()))
              {
                $active = 'activeLikeBtn';
              }
              if($user->hasDislikedStore($store->getStoreId()))
              {
                $active2 = 'activeDislikeBtn';
              }
            ?>
            <form onsubmit="return rateStoreForm()" id="rateStoreForm" style="text-align:center" action="form_handlers/_rateStore.php" method="post">
              <input type="hidden" name="storeid" value="<?php echo $store->getStoreId()?>">
              <div style="text-align:center; font-size: 25pt;display: flex;justify-content: center;font-family:arial;">
                <div style="text-align:center;padding:10px; padding-right:0;padding-left:0;margin-right:10px">
                  <input type="submit" class="likebtn <?php echo $active ?>" name="like" value="">
                  <?php echo $store->getLikes(); ?>
                </div>
                <div style="text-align:center;padding:10px;">
                  <input type="submit" class="dislikebtn <?php echo $active2 ?>" name="dislike" value="">
                  <?php echo $store->getDislikes(); ?>
                </div>
              </div>
            </form>
            <?php
          }else {
            ?>
            <div style="text-align:center; font-size: 25pt;display: flex;justify-content: center;font-family:arial;">
              <div style="text-align:center;padding:10px;display: flex;justify-content: center;">
                <div class="likebtnDisabled"></div>
                <div style="padding:10px; width:50px; height:50px; margin:0;"><?php echo $store->getLikes(); ?></div>
              </div>
              <div style="text-align:center;padding:10px; display: flex;justify-content: center;">
                <div class="dislikebtnDisabled"></div>
                <div style="padding:10px; width:50px; height:50px; margin:0;"><?php echo $store->getDislikes(); ?></div>
              </div>
            </div>
            <?php
          }
            ?>
          </div>
          <div style="padding:20px; text-align:center;">
            <img class="storePhotoPage" src="<?php echo $store->getStorePhotoPath();?>" alt="">
          </div>
          <div style="padding:20px; text-align:center;">
            <?php echo $store->getWebsite();?>
          </div>
          <div style="padding:20px; text-align:center;">
            <?php echo $store->getPhone();?>
          </div>
          <div style="padding:20px; text-align:center;">
            <?php echo $store->getCategory();?>
          </div>
          <div style="padding:20px; text-align:center; border-style:solid; border-width:thin;">
            <?php echo $store->getDescription() == '' ? 'No Store Description' : $store->getDescription();?>
          </div>
          <?php
            if(isset($_SESSION['student']))
            {
           ?>
          <div id="storeDiscounts">
            <?php
              $discounts = $store->getDiscounts();
              $discounts = array_reverse($discounts);
              foreach ($discounts as $key => $value) {
                echo '<div id="discount'.$value->getDiscountId().'" style="padding:20px;border-style:solid; border-width:thin; margin-top:20px; text-align:center;">';
                echo 'Discount Name: '.$value->getName().'</br>';
                echo 'Percentage: '.$value->getPercent().'%</br>';
                // echo 'Code: '.$value->getCode().'</br>';
                echo 'Start Date: '.$value->getStartDate().'</br>';
                echo 'Expire Date: '.$value->getExpireDate().'</br>';
                echo 'Subcategory: '.$value->getSubCategory().'</br>';
            ?>
                <form onsubmit="return claimDiscountForm()" id="claimDiscountForm" action="form_handlers/_claimDiscount.php" method="post">
                  <input type="hidden" name="discountid" value="<?php echo $value->getDiscountId()?>">
                  <input type="submit" name="claimDiscount" value="Claim Discount">
                </form>
            <?php
                echo '</div>';
              }
            ?>
          </div>
          <?php
            }
          ?>
          <div id="StoreLocations">

          </div>
          <div id="Store Photos">

          </div>
          <?php
              }else {
                echo 'No page found';
              }
            }else {
              echo 'No page found';
            }
          ?>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      window.onload = Load();
      function Load() {
        <?php echo GetXandYScrollPositions();?>
        <?php echo getSessionDivMsg(3000);?>
      }
      function rateStoreForm() {
        AddXandYScrollToForm("rateStoreForm");
        return true
      }
      function claimDiscountForm() {
        AddXandYScrollToForm("claimDiscountForm");
        return true
      }
    </script>
  </body>
</html>
