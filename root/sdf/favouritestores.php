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
      if(!isset($_SESSION['student']))
      {
        header('Location: index.php');
      }
      startSession();
    ?>
    <div id="container">
      <?php
      include '_fixedHeaderAndSideBar.php'
       ?>
      <div id="contentContainer">
        <div id="content">
          <div>
            <h2 style="margin:0; font-family:arial;margin-bottom:20px;">Favourite Stores</h2>
          </div>
          <?php
            $user = null;
            $conn = null;
            if(isset($_SESSION['student']))
            {
              $user = unserialize($_SESSION['student']);
              $conn = createSqlConn();
              $favStores = unserialize($user->getLikedStores(true));
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
                      echo '<a href="store?id='.$value.'">';
                      echo '<div class="StoreLink">';
                      echo '<div>';
                      echo $store->getName();
                      echo '</div>';
                      echo '<div>';
                      echo $store->getDescription();
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
                if($page <= $number_of_pages && $page >= 1)
                {
                  getFavStoresPagination($totalNofResults, $results_per_page, $page, $number_of_pages);
                }
              }else {
                echo '<div style="margin-top:20px; border-style:solid; border-width:thin; padding: 20px;">';
                echo 'No stores added to favourites';
                echo '</div>';
              }
          ?>
          <?php
              closeSqlConn($conn);
            }
          ?>
        </div>
      </div>
    </div>
  </body>
</html>
