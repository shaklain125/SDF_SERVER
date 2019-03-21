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
      include '_checkLoggedIn.php';
    ?>
    <div id="container">
      <div id="main">
        <?php
        include '_fixedHeaderAndSideBar.php'
         ?>
        <div id="contentContainer">
          <div id="content">
            <div id="SearchResultsDiv">
              <?php
                if(isset($_GET['q']) XOR isset($_GET['subcategory']))
                {
                  if(isset($_GET['subcategory']))
                  {
                    $query = $_GET['subcategory'];
                  }
                  $user = null;
                  $page = getPageNumber();
                  $results_per_page = 10;
                  if(isset($_SESSION['student']))
                  {
                    $user = unserialize($_SESSION['student']);
                  }else {
                    $user = unserialize($_SESSION['storemember']);
                  }
                  $totalNofResults = null;
                  $totalNofResults = $user->getSearchNumberofRows($query,  isset($_GET['subcategory']));
                  $number_of_pages = ceil($totalNofResults/$results_per_page);
                  $searchResultsArr = array();
                  if($page <= $number_of_pages && $page >= 1)
                  {
                    $searchResultsArr = $user->SearchStore($query,$page,$results_per_page, isset($_GET['subcategory']));
                  }
                  if($page != 1 && ($page <= 0 || $page > $number_of_pages))
                  {
                    if($totalNofResults >= 0)
                    {
                      echo '<div>No Page Found</div>';
                    }
                  }else {
                    echo '<h3 style="margin:0; margin-bottom:20px;">'.$totalNofResults.' result(s) found for \''.$query.'\'</h3>';
                  }
                  foreach ($searchResultsArr as $key => $value) {
                    $d = $value->GetLatestDiscount($query);
                    echo '<a style="text-decoration:none;" href="store?id='.$value->getStoreId().'">';
                    echo '<div class="StoreLink searchLink">';
                      echo '<div>';
                      echo '<img class="searchLinkImg" src="'.$value->getStorePhotoPath().'" alt="">';
                      echo '</div>';
                      echo '<div>';
                      echo '<div class="searchLinkText" title="'.$value->getName().'" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">';
                      echo $value->getName();
                      echo '</div>';
                      if($d != null)
                      {
                        echo '<div class="searchLinkText" title="'.$d->getPercent().'% Off '.$d->getName().'" style="text-align:center;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">';
                        echo $d->getPercent().'% Off '.$d->getName();
                        echo '</div>';
                      }else {
                        echo '<div class="searchLinkText" style="text-align:center;">';
                        echo 'Discounts N/A';
                        echo '</div>';
                      }
                      echo '<div style="text-align:center; margin-bottom:20px">';
                      echo '<span class="likebtnDisabled likeBtn"></span><span>'.$value->getLikes().'</span>';
                      echo '<span style="margin-left:20px" class="dislikebtnDisabled dislikeBtn"></span><span>'.$value->getDislikes().'</span>';
                      echo '</div>';
                      echo '</div>';
                    echo '</div>';
                    echo '</a>';
                    // printval($d);
                  }
                  ?>

                  </div>
                  <?php
                  if($page <= $number_of_pages && $page >= 1)
                  {
                    getSearchPagination($query, $totalNofResults, $results_per_page, $page, $number_of_pages);
                  }
                }else {
                  header('Location: index.php');
                }
              ?>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
