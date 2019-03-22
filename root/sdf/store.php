<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <?php
      include '_importPhp.php';
      include '_importStyle.php';
      include '_checkLoggedIn.php';
      startSession();
    ?>
    <style media="screen">
    </style>
    <script type="text/javascript">

      function rateStore(val) {
        var reqData = null;
        var req = new XMLHttpRequest();
        var formdata = $("#rateStoreForm").serialize();
        if(val)
        {
          reqData = 'like=&' + formdata;
        }else {
          reqData = 'dislike=&' + formdata;
        }
        req.onload = () =>
        {
          var respData = null;
          try {
            respData = JSON.parse(req.responseText)
          } catch (e) {

          }
          if (respData) {
            rateFormResultHandler(respData)
          }
        }
        req.open('post', 'form_handlers/_rateStore.php');
        req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        req.send(reqData);
      }

      function rateFormResultHandler(response) {
        if(response[1])
        {
          element("like").className = "likebtn activeLikeBtn"
        }else {
          element("like").className = "likebtn"
        }
        if(response[2])
        {
          element("dislike").className = "dislikebtn activeDislikeBtn"
        }else {
          element("dislike").className = "dislikebtn"
        }
        element("likes").innerHTML = response[0][0]
        element("dislikes").innerHTML = response[0][1]
      }

      function claimDiscountFrm(formid) {
        var formdata = $("#" + formid).serialize();
        var reqData = 'claimDiscount=&' + formdata;
        var req = new XMLHttpRequest();
        req.onload = () =>
        {
          var respData = null;
          try {
            respData = JSON.parse(req.responseText)
          } catch (e) {

          }
          if (respData) {
            claimDiscountFormResultHandler(respData)
          }
        }
        req.open('post', 'form_handlers/_claimDiscount.php');
        req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        req.send(reqData);
      }

      function claimDiscountFormResultHandler(response) {
        ShowSessionDivMsg(response.message);
        setTimeout("HideSessionDivMsg()",3000);
      }
    </script>
  </head>
  <body>
    <div id="container">
      <div id="main">
        <?php
        include '_fixedHeaderAndSideBar.php'
         ?>
        <div id="contentContainer">
          <div id="content">
            <div>
              <a class="linkBtn" href="index.php">Home</a>
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
              <form id="rateStoreForm" style="text-align:center">
                <input type="hidden" name="storeid" value="<?php echo $store->getStoreId()?>">
                <div style="text-align:center; font-size: 25pt;display: flex;justify-content: center;font-family:arial;">
                  <div style="text-align:center;padding:10px; padding-right:0;padding-left:0;margin-right:10px">
                    <input type="button" class="likebtn <?php echo $active ?>" name="like" id="like" value="" onclick="rateStore(true)">
                    <span id="likes"><?php echo $store->getLikes(); ?></span>
                  </div>
                  <div style="text-align:center;padding:10px;">
                    <input type="button" class="dislikebtn <?php echo $active2 ?>" name="dislike" id="dislike" value="" onclick="rateStore(false)">
                    <span id="dislikes"><?php echo $store->getDislikes(); ?></span>
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
            <div class="storePageText" style="padding-bottom:50px;">
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
              <div style="padding:50px">
                <h3 style="padding-bottom:10px;border-bottom:solid;border-width:thin;border-color:#d9d9d9; width:50%; margin:0px auto;margin-bottom:20px;">About Store</h3>
                <?php echo $store->getDescription() == '' ? 'No Store Description' : $store->getDescription();?>
              </div>
              <div style="padding:50px">
                <?php
                $storememb = new storemember($store->getStorememberId());
                ?>
                <div style="margin-bottom:20px">
                  <img src="<?php echo $storememb->getProfilePicturePath()?>" style="border-radius:100px;border:none;object-fit: cover;" width="100px" height="100px"alt="">
                </div>
                <?php
                echo '<div>Added by</div>'.$storememb->getFirstName().' '.$storememb->getLastName();
                ?>
              </div>
            </div>
            <?php
              if(isset($_SESSION['student']))
              {
             ?>
            <div id="storeDiscounts">
              <?php
                $discounts = $store->getDiscounts();
                $discounts = array_reverse($discounts);
                if(sizeof($discounts) > 0)
                {
                  ?>
                  <h3 style="text-align:center;border-bottom:solid;border-width:thin;border-color:#d9d9d9; width:50%; margin:0px auto;margin-top:50px; margin-bottom:30px; padding-bottom:20px;">Discounts</h3>
                  <?php
                }
                foreach ($discounts as $key => $value) {
                  echo '<div id="discount'.$value->getDiscountId().'" class="storePageText" style="padding:20px; margin-top:20px; text-align:center;">';
                  echo 'Discount Name: '.$value->getName().'</br>';
                  echo 'Percentage: '.$value->getPercent().'%</br>';
                  // echo 'Code: '.$value->getCode().'</br>';
                  echo 'Start Date: '.$value->getStartDate().'</br>';
                  echo 'Expire Date: '.$value->getExpireDate().'</br>';
                  echo 'Subcategory: '.$value->getSubCategory().'</br>';
              ?>
                  <form id="claimDiscountForm<?php echo $value->getDiscountId()?>">
                    <input type="hidden" name="discountid" value="<?php echo $value->getDiscountId()?>">
                    <input type="button" name="claimDiscount" value="Claim Discount" onclick="claimDiscountFrm('claimDiscountForm<?php echo $value->getDiscountId()?>')">
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
