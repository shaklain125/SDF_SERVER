<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <?php include '_importPhp.php';include '_importStyle.php'; ?>
  </head>
  <body>
    <?php
      startSession();
    ?>
    <div id="container">

      <div id="main">
        <?php
        if(!LoggedIn())
        {
          echo '<div style="margin-top:50px">Not Logged in</div>';
          header('location:about.php');
          return;
        }
        include '_fixedHeaderAndSideBar.php'
         ?>
        <div id="contentContainer">
          <div id="content">
            <div id="HomeStoresDiv">
              <?php
                $user = null;
                $conn = null;
                if(LoggedIn())
                {
                  if(isset($_SESSION['student']))
                  {
                    $user = unserialize($_SESSION['student']);
                  }else {
                    $user = unserialize($_SESSION['storemember']);
                  }
                  $conn = createSqlConn();
                }
              ?>
              <?php
                if(isset($user))
                {
                  $stores = SqlResultToArray("select storeid,category from store ORDER BY storeid DESC LIMIT 10", $conn);
                  $discounts = SqlResultToArray("select discountid,storeid,subcategory from discount ORDER BY discountid DESC", $conn);
                  $storesToList = array();
                  $db_prefCategs = null;
                  $db_prefSubCategs = null;
                  if(isset($_SESSION['student']))
                  {
                    $db_prefCategs = unserialize($user->getPrefCategories());
                    $db_prefSubCategs = unserialize($user->getPrefSubCategories());
                    $db_prefSubCategs = JoinSubCategs($db_prefCategs,$db_prefSubCategs, $conn);
                    foreach ($discounts as $key => $value) {
                      foreach ($db_prefSubCategs as $key2 => $value2) {
                        if($value['subcategory'] == $value2)
                        {
                          $exists = false;
                          $k = null;
                          foreach ($storesToList as $key3 => $value3) {
                            if($value['storeid'] == $value3)
                            {
                              $exists = true;
                              $k = $key3;
                              break;
                            }
                          }
                          if(!$exists)
                          {
                            array_push($storesToList, $value['storeid']);
                          }
                          break;
                        }
                      }
                    }
                    foreach ($stores as $key => $value) {
                      foreach ($db_prefCategs as $key2 => $value2) {
                        if($value['category'] == $value2)
                        {
                          $exists = false;
                          $k = null;
                          foreach ($storesToList as $key3 => $value3) {
                            if($value['storeid'] == $value3)
                            {
                              $exists = true;
                              $k = $key3;
                              break;
                            }
                          }
                          if(!$exists)
                          {
                            array_push($storesToList, $value['storeid']);
                          }
                          break;
                        }
                      }
                    }
                    if(sizeof($storesToList) == 0)
                    {
                      echo '<div style="margin-bottom:20px"><h3>Recently Added</h3></div>';
                      foreach ($stores as $key => $value) {
                        array_push($storesToList, $value['storeid']);
                      }
                      DisplayStoresNoPref($storesToList);
                    }else {
                      echo '<div style="margin-bottom:20px"><h3>Recommended</h3></div>';
                      foreach ($storesToList as $key1 => $value1) {
                        $store = new store($value1);
                        echo '<a href="store?id='.$value1.'">';
                        echo '<div class="StoreLink">';
                        $discounts = $store->getDiscounts();
                        $discounts = array_reverse($discounts);
                        $prefDiscountFromStore = array();
                        $addedDisc = false;
                        foreach ($discounts as $key2 => $value2) {
                          $s = $value2->getSubCategory();
                          foreach ($db_prefSubCategs as $key3 => $value3) {
                            if($s == $value3)
                            {
                              array_push($prefDiscountFromStore, $value2);
                              $addedDisc = true;
                              break;
                            }
                          }
                          if($addedDisc)
                          {
                            break;
                          }
                        }
                        echo '<div class="StoreLinkImgcontainer">';
                        echo '<img title="'.$store->getDescription().'" class="storeLinkImg" src="'.$store->getStorePhotoPath().'" alt="">';
                        echo '<div class="overlay">';
                        $descr = $store->getDescription();
                        echo '<div class="overlayDescription" title="'.$descr.'">'.substr($store->getDescription(), 0, 80).(strlen($descr) > 80?'...':'').'</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="storeLinkText" title="'.$store->getName().'" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">';
                        echo substr($store->getName(), 0, 30).(strlen($store->getName()) > 30?'...':'');
                        echo '</div>';
                        foreach ($prefDiscountFromStore as $key => $value) {
                          echo '<div class="storeLinkText" title="'.$value->getPercent().'% Off '.$value->getName().'" style="padding:0px; padding-left:10px;text-align:center;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">';
                          echo substr($value->getPercent().'% Off '.$value->getName(), 0, 30).(strlen($value->getPercent().'% Off '.$value->getName()) > 30?'...':'');
                          echo '</div>';
                        }
                        if(sizeof($prefDiscountFromStore) == 0)
                        {
                          echo '<div style="text-align:center;">';
                          echo 'Discounts N/A';
                          echo '</div>';
                        }
                        echo '<div style="text-align:center; margin-top:20px;margin-bottom:20px">';
                        echo '<span class="likebtnDisabled likeBtn"></span><span>'.$store->getLikes().'</span>';
                        echo '<span style="margin-left:20px" class="dislikebtnDisabled dislikeBtn"></span><span>'.$store->getDislikes().'</span>';
                        echo '</div>';
                        echo '</div>';
                        echo '</a>';
                      }
                    }
                  }else {
                    echo '<div style="margin-bottom:20px"><h3>Recently Added</h3></div>';
                    foreach ($stores as $key => $value) {
                      array_push($storesToList, $value['storeid']);
                    }
                    DisplayStoresNoPref($storesToList);
                  }
                }
                function DisplayStoresNoPref($storesToList) {
                  array_reverse($storesToList);
                  foreach ($storesToList as $key1 => $value1) {
                    $store = new store($value1);
                    echo '<a href="store?id='.$value1.'">';
                    echo '<div class="StoreLink">';
                    echo '<div class="StoreLinkImgcontainer">';
                    echo '<img title="'.$store->getDescription().'" class="storeLinkImg" src="'.$store->getStorePhotoPath().'" alt="">';
                    echo '<div class="overlay">';
                    $descr = $store->getDescription();
                    echo '<div class="overlayDescription" title="'.$descr.'">'.substr($store->getDescription(), 0, 80).(strlen($descr) > 80?'...':'').'</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '<div class="storeLinkText" title="'.$store->getName().'" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">';
                    echo substr($store->getName(), 0, 30).(strlen($store->getName()) > 30?'...':'');
                    echo '</div>';
                    $d = $store->getDiscounts();
                    $d = array_reverse($d);
                    if(sizeof($d) > 0)
                    {
                      $d = $d[0];
                      echo '<div class="storeLinkText" title="'.$d->getPercent().'% Off '.$d->getName().'" style="padding:0px; padding-left:10px;text-align:center;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">';
                      echo substr($d->getPercent().'% Off '.$d->getName(), 0, 30).(strlen($d->getPercent().'% Off '.$d->getName()) > 30?'...':'');
                      echo '</div>';
                    }else {
                      echo '<div style="text-align:center;">';
                      echo 'Discounts N/A';
                      echo '</div>';
                    }
                    echo '<div style="text-align:center;  margin-top:20px; margin-bottom:20px">';
                    echo '<span class="likebtnDisabled likeBtn"></span><span>'.$store->getLikes().'</span>';
                    echo '<span style="margin-left:20px" class="dislikebtnDisabled dislikeBtn"></span><span>'.$store->getDislikes().'</span>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                  }
                }

                function JoinSubCategs($categories, $arr, $conn) {
                  $subcateg = SqlResultToArray("select * from subcategory",$conn);
                  foreach ($subcateg as $key1 => $value1) {
                    foreach ($categories as $key2 => $value2) {
                      if($value1['category_name'] == $value2)
                      {
                        array_push($arr,$value1['subcategory_name']);
                        break;
                      }
                    }
                  }
                  return $arr;
                }
              ?>
              <?php
              if(isset($user))
              {
                closeSqlConn($conn);
              }
              ?>
            </div>
          </div>
        </div>
      </div>

    </div>
  </body>
</html>
