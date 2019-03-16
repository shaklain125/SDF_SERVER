<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <?php include '_importStyle.php'; ?>
  </head>
  <body>
    <?php
      include '_importPhp.php';
      startSession();
    ?>
    <div id="container">
      <?php
      include '_fixedHeaderAndSideBar.php'
       ?>
      <div id="contentContainer">
        <div id="content">
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
                  foreach ($stores as $key => $value) {
                    array_push($storesToList, $value['storeid']);
                  }
                  DisplayStoresNoPref($storesToList);
                }else {
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
                    echo '<div>';
                    echo '<img class="storeLinkImg" src="'.$store->getStorePhotoPath().'" alt="">'.'<br /><br />';
                    echo '</div>';
                    echo '<div class="storeLinkText">';
                    echo $store->getName().'<br /><br />';
                    echo '</div>';
                    foreach ($prefDiscountFromStore as $key => $value) {
                      echo '<div style="text-align:center;">';
                      echo $value->getPercent().'% off '.$value->getName();
                      echo '</div>';
                    }
                    if(sizeof($prefDiscountFromStore) == 0)
                    {
                      echo '<div style="text-align:center;">';
                      echo 'Discounts N/A';
                      echo '</div>';
                    }
                    echo '<div class="overlay">';
                    echo '<div class="overlayDescription">'.substr($store->getDescription(), 0, 80).'</div>';
                    echo '</div><br />';
                    echo '<div class="storeLinkText">';
                    echo 'Likes: '.$store->getLikes().str_repeat('&nbsp;', 7).'Dislikes: '.$store->getDislikes().'<br />';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                  }
                }
              }else {
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
                $discounts = $store->getDiscounts();
                echo '<div>';
                echo '<img class="storeLinkImg" src="'.$store->getStorePhotoPath().'" alt="">'.'<br /><br />';
                echo '</div>';
                echo '<div class="storeLinkText">';
                echo $store->getName().'<br /><br />';
                echo '</div>';
                $d = $store->getDiscounts();
                if(sizeof($d) > 0)
                {
                  $d = $d[0];
                  echo '<div style="text-align:center;">';
                  echo $d->getPercent().'% off '.$d->getName();
                  echo '</div>';
                }else {
                  echo '<div style="text-align:center;">';
                  echo 'Discounts N/A';
                  echo '</div>';
                }
                echo '<div class="overlay">';
                echo '<div class="overlayDescription">'.substr($store->getDescription(), 0, 80).'</div>';
                echo '</div><br />';
                echo '<div class="storeLinkText">';
                echo 'Likes: '.$store->getLikes().str_repeat('&nbsp;', 7).'Dislikes: '.$store->getDislikes().'<br />';
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
  </body>
</html>
