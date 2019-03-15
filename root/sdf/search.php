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
      include '_checkLoggedIn.php';
    ?>
    <div id="container">
      <?php
      include '_fixedHeaderAndSideBar.php'
       ?>
      <div id="contentContainer">
        <div id="content">
          <div>
            <a class="linkBtn" href="index.php">Home</a>
          </div>
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
                echo '<h3 style="font-family:arial; margin:0; margin-bottom:20px;">'.$totalNofResults.' result(s) found for \''.$query.'\'</h3>';
              }
              foreach ($searchResultsArr as $key => $value) {
                $d = $value->GetLatestDiscount($query);
                echo '<a class="StoreLink" href="store?id='.$value->getStoreId().'">';
                echo '<div class="StoreLink" style="margin-bottom:30px;border-style:solid; border-width:thin; padding: 20px;font-family:arial">';
                if($d != null)
                {
                  echo '<div style="margin-bottom:10px;padding-bottom:30px;text-align:center;">'.$d->getPercent().'% OFF '.$d->getName().'</div>';
                }
                echo '<div>'.$value->getName().'</div>';
                echo '</div>';
                echo '</a>';
                // printval($d);
              }
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
  </body>
</html>
