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
      $user = unserialize($_SESSION['storemember']);
    ?>
    <div id="container">
      <?php
      include '_fixedHeaderAndSideBar.php'
       ?>
      <div id="contentContainer">
        <div id="content">
          <form onsubmit="return addStoreForm()" id="addStoreForm" action="form_handlers/_addstore.php" method="post">
            <div id="errorMsgDiv" style="display:<?php echo errorExists(); ?>">
              <?php echo ShowError(); ?>
            </div>
            <input type="text" name="input_store_name" placeholder="* Store name" value="<?php echo keepData('input_store_name',false); ?>">
            <input type="text" name="input_store_website" placeholder="* Website" value="<?php echo keepData('input_store_website',false); ?>">
            <input type="text" name="input_store_phone" placeholder="* Phone No." value="<?php echo keepData('input_store_phone',false); ?>">
            <select name="input_store_category" style="border-style:solid; border-width:thin">
              <?php
                $savedVal = keepData('input_store_category', false);
                $conn = createSqlConn();
                $categ = SqlResultToArray("select * from category",$conn);
                foreach ($categ as $key => $value) {
                  if($value['category_name'] == $savedVal)
                  {
                    echo '<option value="'.$value['category_name'].'" selected>'.$value['category_name'].'</option>';
                  }else {
                    echo '<option value="'.$value['category_name'].'">'.$value['category_name'].'</option>';
                  }
                }
                closeSqlConn($conn);
              ?>
            </select>
            <textarea id="store_description" rows="30" name="input_store_descr" placeholder="Store Description" maxlength="3000" oninput="countDescriptionChar()"><?php echo keepData('input_store_descr',true) ?></textarea>
            <div>
              <span id="descrCharcount">0/3000</span>
            </div>
            <input type="submit" name="addStore" value="Create Store Page">
          </form>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      window.onload = Load()
      function Load() {
        <?php echo GetXandYScrollPositions();?>
      }
      function countDescriptionChar()
      {
        element("descrCharcount").innerHTML = (element("store_description").value).length + " / 3000"
      }
      function addStoreForm() {
        AddXandYScrollToForm("addStoreForm");
        return true
      }
    </script>
  </body>
</html>
