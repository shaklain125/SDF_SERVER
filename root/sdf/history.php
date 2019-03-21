<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <?php include '_importPhp.php';include '_importStyle.php'; ?>
    <script type="text/javascript">
      function clearAllHistoryFrm() {
        var reqData = 'clearHistory=';
        var req = new XMLHttpRequest();
        req.onload = () =>
        {
          var respData = null;
          try {
            respData = JSON.parse(req.responseText)
          } catch (e) {

          }
          if (respData) {
            clearAllHistoryFormResultHandler(respData)
          }
        }
        req.open('post', 'form_handlers/_clearHistory.php');
        req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        req.send(reqData);
      }

      function clearAllHistoryFormResultHandler(response) {
        if(response.message == 'ok')
        {
          location.reload();
        }
      }
    </script>
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
              <h2 style="margin:0; font-family:arial;">Store History</h2>
            </div>
            <form style="margin-bottom:20px;" id="clearHistoryForm">
              <input type="button" name="clearHistory" value="Clear History" onclick="clearAllHistoryFrm()">
            </form>
            <div id="SearchResultsDiv">
              <?php
                $user = null;
                $conn = null;
                if(isset($_SESSION['student']))
                {
                  $user = unserialize($_SESSION['student']);
                  $conn = createSqlConn();
                  $storehistory = $user->getStoreHistory();
                  if($storehistory == null)
                  {
                    $storehistory = array();
                  }
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
                          echo '<a style="text-decoration:none;" href="store?id='.$store->getStoreId().'">';
                          echo '<div class="StoreLink searchLink">';
                            echo '<div>';
                            echo '<img class="searchLinkImg" src="'.$store->getStorePhotoPath().'" alt="">';
                            echo '</div>';
                            echo '<div class="searchLinkText" title="'.$store->getName().'" style="overflow: hidden;white-space: nowrap;text-overflow: ellipsis;">';
                            echo $store->getName();
                            echo '</div>';
                            echo '<div title="'.$store->getDescription().'" class="searchLinkText wordwrap">';
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
                  getHistoryPagination($totalNofResults, $results_per_page, $page, $number_of_pages);
                }
                }else {
                  echo '<div style="margin-top:20px; border-style:solid; border-width:thin; padding: 20px;">';
                  echo 'No recent store history';
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
