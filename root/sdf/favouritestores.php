<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <?php include '_importPhp.php';include '_importStyle.php';?>
    <style>
    .wordwrap {
   white-space: pre-wrap;      /* CSS3 */
   white-space: -moz-pre-wrap; /* Firefox */
   white-space: -pre-wrap;     /* Opera <7 */
   white-space: -o-pre-wrap;   /* Opera 7 */
   word-wrap: break-word;      /* IE */
}
    </style>
  </head>
  <body>
    <?php

      if(!isset($_SESSION['student']))
      {
        header('Location: index.php');
      }
      startSession();
    ?>
    <div id="container">
      <div id="main">
        <?php
        include '_fixedHeaderAndSideBar.php'
         ?>
        <div id="contentContainer">
          <div id="content">
            <div>
              <h2 style="margin:0; font-family:arial;margin-bottom:20px;">Favourite Stores</h2>
            </div>
            <div id="SearchResultsDiv">
              <?php
                $user = null;
                $conn = null;
                if(isset($_SESSION['student']))
                {
                  $user = unserialize($_SESSION['student']);
                  $conn = createSqlConn();
                  $favStores = $user->getLikedStores(true);
                  if($favStores == null)
                  {
                    $favStores = array();
                  }
                  $favStores = array_reverse($favStores);
                  if(sizeof($favStores) > 0)
                  {
                    $page = getPageNumber();
                    $results_per_page = 10;
                    $totalNofResults = sizeof($favStores);
                    $number_of_pages = ceil($totalNofResults/$results_per_page);
                    if($page <= $number_of_pages && $page >= 1)
                    {
                      $favStores = array_slice($favStores, ($page-1)*$results_per_page, $results_per_page);
                      foreach ($favStores as $key => $value) {
                        if($user->StoreExists($value))
                        {
                          $store = new store($value);
                          echo '<a style="text-decoration:none;" href="store?id='.$store->getStoreId().'">';
                          echo '<div class="StoreLink searchLink">';
                            echo '<div>';
                            echo '<img class="searchLinkImg" src="'.$store->getStorePhotoPath().'" alt="">';
                            echo '</div>';
                            echo '<div class="searchLinkText" title="'.$store->getName().'" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">';
                            echo $store->getName();
                            echo '</div>';
                            echo '<div title="'.$store->getDescription().'" class="searchLinkText" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">';
                            echo substr($store->getDescription(), 0, 80).(strlen($store->getDescription()) > 80?'...':'');
                            echo '</div>';
                            echo '<div style="text-align:center; margin-bottom:20px">';
                            echo '<span class="likebtnDisabled likeBtn"></span><span>'.$store->getLikes().'</span>';
                            echo '<span style="margin-left:20px" class="dislikebtnDisabled dislikeBtn"></span><span>'.$store->getDislikes().'</span>';
                            echo '</div>';
                          echo '</div>';
                          echo '</a>';
                        }
                      }
                    }
                    if($page != 1 && ($page <= 0 || $page > $number_of_pages))
                    {
                      if($totalNofResults >= 0)
                      {
                        echo '<div>No Page Found</div>';
                      }
                    }
              ?>
            </div>
            <?php
                if($page <= $number_of_pages && $page >= 1)
                {
                  getFavStoresPagination($totalNofResults, $results_per_page, $page, $number_of_pages);
                }
                }else {
                  echo '<div style="margin-top:20px; border-style:solid; border-width:thin; padding: 20px;">';
                  echo 'No stores added to favourites';
                  echo '</div>';
                }
                closeSqlConn($conn);
              }
            ?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
