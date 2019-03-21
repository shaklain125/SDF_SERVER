<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <?php include '_importPhp.php';include '_importStyle.php'; ?>
    <script type="text/javascript">
      function removeStoreFrm(formid) {
        if(!RemoveStoreForm(formid))
        {
          return;
        }
        var formdata = $("#" + formid).serialize();
        var reqData = 'removeStore=&' + formdata;
        var req = new XMLHttpRequest();
        req.onload = () =>
        {
          var respData = null;
          try {
            respData = JSON.parse(req.responseText)
          } catch (e) {

          }
          if (respData) {
            removeStoreFormResultHandler(respData)
          }
        }
        req.open('post', 'form_handlers/_removestore.php');
        req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        req.send(reqData);
      }

      function removeStoreFormResultHandler(response) {
        if(response.message)
        {
          if(storesCount > 0)
          {
            storesCount -= 1;
          }
          console.log(storesCount)
          if(storesCount == 0)
          {
            element("ManageStoresList").style.display = "none";
            var d = document.createElement("div")
            d.innerHTML = "No stores added";
            d.style.margin = "20px";
            element("content").appendChild(d);
          }else {
            element('store' + response.storeid).style.display = 'none';
          }
          ShowSessionDivMsg(response.message);
          setTimeout("HideSessionDivMsg()",3000);
        }
      }

      function removeAllStoresFrm() {
        if(!RemoveAllStoresForm())
        {
          return;
        }
        var reqData = 'removeAll=';
        var req = new XMLHttpRequest();
        req.onload = () =>
        {
          var respData = null;
          try {
            respData = JSON.parse(req.responseText)
          } catch (e) {

          }
          if (respData) {
            removeAllStoresFormResultHandler(respData)
          }
        }
        req.open('post', 'form_handlers/_removestore.php');
        req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        req.send(reqData);
      }

      function removeAllStoresFormResultHandler(response) {
        if(response.message)
        {
          storesCount = 0;
          element("ManageStoresList").style.display = "none";
          var d = document.createElement("div")
          d.innerHTML = "No stores added";
          d.style.margin = "20px";
          element("content").appendChild(d);
          ShowSessionDivMsg(response.message);
          setTimeout("HideSessionDivMsg()",3000);
        }
      }
    </script>
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
            <div>
              <a class="linkBtn" href="account.php">Account</a>
            </div>
            <a href="addstore.php" class="linkBtn">+ Add Store Page</a>
              <?php
              $user = unserialize($_SESSION['storemember']);
              $stores = unserialize($user->getStores());
              if($stores != null)
              {
                ?>
                <div id="ManageStoresList" style="padding-left:5%;padding-right:5%;">
                <form id="removeAllForm" style="display:inline-block; float:right;">
                  <a href="#" class="linkBtn" style="padding:0px"><input style="width:auto;margin:0px;background-color:transparent;border:none;color:white" type="button" value="Remove All" onclick="javascript:removeAllStoresFrm()"></a>
                </form>
                <?php
                $stores = array_reverse($stores);
                foreach ($stores as $key => $value) {
                  $store1 = new store($value);
                  $name = $store1->getName();
                  echo '<div class="manageStoreStyle" id="store'.$store1->getStoreId().'" style="overflow:hidden;width:100%;padding:10px;margin-top:20px;">';
                  echo '<div>ID: '.$store1->getStoreId().' | '.$name.'</div>';
                  echo '<input type="button" style="float:left" onclick="location.href=\'modifystore?id='.$store1->getStoreId().'\'" name="modify_store" value="Modify Store">';
                  echo '<form style="float:right" id="RemoveStoreForm'.$key.'">';
                  echo '<input type="hidden" name="storeid" value="'.$store1->getStoreId().'">';
                  echo '<input type="button" name="removeStore" value="Remove Store" onclick="removeStoreFrm(\'RemoveStoreForm'.$key.'\')">';
                  echo '</form>';
                  echo '</div>';
                }
                ?>
                </div>
                <?php
              }else {
                echo '<div style="margin:20px">No stores added</div>';
              }
              ?>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
        window.onload = Load();
        var deleteStore;
        var removestoreformid;
        var removeAll;
        var storesCount;
        function Load() {
          deleteStore = false;
          removeAll = false;
          storesCount = <?php $s = unserialize(unserialize($_SESSION['storemember'])->getStores()); echo $s == null ? 0 : sizeof($s)?>;
          console.log(storesCount);
          <?php echo GetXandYScrollPositions();?>
          <?php echo getSessionDivMsg(3000);?>
        }
        function RemoveStoreForm(e) {
          if(!deleteStore)
          {
            removestoreformid = e;
            var c = new ConfirmDialog("Are you sure you want to delete this store?",removeStore,CancelDeleteStore);
            c.show();
            return false;
          }else {
            deleteStore = false;
            removestoreformid = null;
            return true;
          }
        }

        function removeStore() {
          deleteStore = true;
          // AddXandYScrollToForm(removestoreformid);
          var r = document.createElement("input");
          r.type="hidden";
          r.name="removeStore";
          element(removestoreformid).appendChild(r);
          removeStoreFrm(removestoreformid);
        }

        function CancelDeleteStore() {
          deleteStore = false;
          removestoreformid = null;
        }

        function RemoveAllStoresForm(e) {
          if(!removeAll)
          {
            var c = new ConfirmDialog("Are you sure you want to remove all?",RemoveAll,cancelRemoveAll);
            c.show();
            return false;
          }else {
            removeAll = false;
            return true;
          }
        }

        function RemoveAll() {
          removeAll = true;
          var r = document.createElement("input");
          r.type="hidden";
          r.name="removeAll";
          element("removeAllForm").appendChild(r);
          removeAllStoresFrm();
        }

        function cancelRemoveAll() {
          removeAll = false;
        }
    </script>
  </body>
</html>
