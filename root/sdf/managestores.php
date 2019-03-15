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
              <form onsubmit="return RemoveAllStoresForm()" id="removeAllForm" style="display:inline-block; float:right;" action="form_handlers/_removestore.php" method="post">
                <a href="#" class="linkBtn" style="padding:0px"><input style="width:auto;margin:0px;background-color:transparent;border:none;color:white" type="submit" value="Remove All"></a>
              </form>
              <?php
              foreach ($stores as $key => $value) {
                $store1 = new store($value);
                $name = $store1->getName();
                echo '<div style="overflow:hidden;width:100%;border-style:solid;border-width:thin;padding:10px;margin-top:20px;">';
                echo '<div>'.$name.'</div>';
                echo '<input type="button" style="float:left" onclick="location.href=\'modifystore?id='.$store1->getStoreId().'\'" name="modify_store" value="Modify Store">';
                echo '<form onsubmit="return RemoveStoreForm(this)" style="float:right" id="RemoveStoreForm'.$key.'" action="form_handlers/_removestore.php" method="post">';
                echo '<input type="hidden" name="storeid" value="'.$store1->getStoreId().'">';
                echo '<input type="submit" name="removeStore" value="Remove Store">';
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
    <script type="text/javascript">
        window.onload = Load();
        var deleteStore;
        var removestoreformid;
        var removeAll;
        function Load() {
          deleteStore = false;
          removeAll = false;
          <?php echo GetXandYScrollPositions();?>
          <?php echo getSessionDivMsg(3000);?>
        }
        function RemoveStoreForm(e) {
          if(!deleteStore)
          {
            removestoreformid = e.id;
            var c = new ConfirmDialog("Are you sure you want to delete this store?",removeStore,CancelDeleteStore);
            c.show();
            return false;
          }else {
            return true;
          }
        }

        function removeStore() {
          deleteStore = true;
          AddXandYScrollToForm(removestoreformid);
          var r = document.createElement("input");
          r.type="hidden";
          r.name="removeStore";
          element(removestoreformid).appendChild(r);
          element(removestoreformid).submit();
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
            return true;
          }
        }

        function RemoveAll() {
          removeAll = true;
          var r = document.createElement("input");
          r.type="hidden";
          r.name="removeAll";
          element("removeAllForm").appendChild(r);
          element("removeAllForm").submit();
        }

        function cancelRemoveAll() {
          removeAll = false;
        }
    </script>
  </body>
</html>
