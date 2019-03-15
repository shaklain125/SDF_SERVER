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
            <h2 style="margin:0; font-family:arial;">Store History</h2>
          </div>
          <form onsubmit="return clearHistoryForm()" style="margin-bottom:20px;" id="clearHistoryForm" action="form_handlers/_clearHistory.php" method="post">
            <input type="submit" name="clearHistory" value="Clear History">
          </form>
          <?php
            $user = null;
            $conn = null;
            if(isset($_SESSION['student']))
            {
              $user = unserialize($_SESSION['student']);
              $conn = createSqlConn();
              $storehistory = unserialize($user->getStoreHistory());
              $storehistory = array_reverse($storehistory);
              if(sizeof($storehistory) > 0)
              {
                $page = getPageNumber();
                $results_per_page = 10;
                $totalNofResults = sizeof($storehistory);
                $number_of_pages = ceil($totalNofResults/$results_per_page);
                if($page <= $number_of_pages && $page >= 1)
                {
                  $storehistory = array_slice($storehistory, ($page-1)*$results_per_page, $results_per_page);
                  foreach ($storehistory as $key => $value) {
                    if($user->StoreExists($value))
                    {
                      $store = new store($value);
                      echo '<a class="StoreLink" href="store?id='.$value.'">';
                      echo '<div class="StoreLink" style="margin-bottom:20px; border-style:solid; border-width:thin; padding: 20px;">';
                      echo '<div>';
                      echo 'ID: '.$store->getStoreId();
                      echo '</div>';
                      echo '<div>';
                      echo $store->getName();
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
                  getHistoryPagination($totalNofResults, $results_per_page, $page, $number_of_pages);
                }
              }else {
                echo '<div style="margin-top:20px; border-style:solid; border-width:thin; padding: 20px;">';
                echo 'No recent store history';
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
    <script type="text/javascript">
      window.onload = Load()
      function Load() {
      <?php echo GetXandYScrollPositions();?>
      }
      function clearHistoryForm() {
        AddXandYScrollToForm("clearHistoryForm");
        return true
      }
    </script>
  </body>
</html>
