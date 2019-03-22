<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <?php include '_importPhp.php';include '_importStyle.php'; ?>

    <script type="text/javascript">
        function modifyStoreFrm() {
          if(!modifystoreForm())
          {
            return;
          }
          var fd = new FormData(element('modifystoreForm'));
          var reqData = fd;
          var req = new XMLHttpRequest();
          req.onload = () =>
          {
            var respData = null;
            try {
              respData = JSON.parse(req.responseText)
            } catch (e) {

            }
            if (respData) {
              modifyStoreFormResultHandler(respData)
            }
          }
          req.open('post', 'form_handlers/_modifystore.php');
          // req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
          req.send(reqData);
        }

        function modifyStoreFormResultHandler(response) {
          if(response.message == 'ok')
          {
            location.reload();
          }
        }
    </script>

    <style media="screen">
      .storePhoto{
        border-style: solid;
        border-width: 0.5px;
        border-color: #ccc;
        object-fit: cover;
      }
      #StorePhotoPage{
        width: 560px;
        height: 336px;
      }

      #StorePhotoLink{
        width: 255px;
        height: 202px;
      }
    </style>
  </head>
  <body>
    <?php
      startSession();
      include '_checkLoggedIn.php';
      function displayStorePhoto() {
        $store = null;
        if(isset($_GET['id']) and $_GET['id'] != null)
        {
          $store = new store($_GET['id']);
        }
        if(!is_dir('stores/'.$store->getStoreId().'/'))
        {
          return 'icons/store.jpg';
        }else {
          return 'stores/'.$store->getStoreId().'/store.jpg';
        }
      }
    ?>
    <div id="container">
      <div id="main">
        <?php
        include '_fixedHeaderAndSideBar.php'
         ?>
        <div id="contentContainer">
          <div id="content">
            <div>
              <a class="linkBtn" href="managestores.php">Manage Stores</a>
            </div>
            <?php
            $store = null;
            if(isset($_GET['id']) and $_GET['id'] != null)
            {
              $store = new store($_GET['id']);
              if(!$store->isNull())
              {
            ?>
            <h3 style="text-align: center;">Edit store page</h3>
            <div id="formContentContainer">
              <div id="formContentEditStore">
                <a class="linkBtn" id="removeStorePhotoBtn" style="padding:5;float:right;margin:0" href="javascript:ToggleRemoveStorePhoto()">Remove Store Photo</a>
                <form id="modifystoreForm" enctype="multipart/form-data">
                  <input type="hidden" name="store_id" value="<?php echo $store->getStoreId();?>">

                  <input type="hidden" id="removeStorePhoto" name="removeStorePhoto" value="false">
                  <div style="margin-top:50px; text-align:center; width:100%; background-color:rgb(28,28,28);">
                    <img class="storePhoto" id="StorePhotoLink" src="<?php echo displayStorePhoto() ?>" alt="">
                  </div>
                  <div style="font-family:arial;color:black;background-color:white;padding:20px;border-style:solid;border-width:thin;border-left:0;border-right:0;border-bottom:0;">
                    <div id="imageErrorMsg"></div>
                    Upload Store Photo:
                    <input type="file" name="store_image" onchange="previewPhoto()" id="store_image">
                  </div>
                  <div class="inputWrap" style="margin-top:20px;">
                    <span class="inputLabel">Store name</span>
                    <input class="formInput" type="text" id="storename" name="input_store_name" placeholder="* Store name" value="<?php echo $store->getName() ?>">
                  </div>
                  <div class="inputWrap">
                    <span class="inputLabel">Store website</span>
                    <input class="formInput" type="text" name="input_store_website" placeholder="Website" value="<?php echo $store->getWebsite() ?>">
                  </div>
                  <div class="inputWrap">
                    <span class="inputLabel">Store phone number</span>
                    <input class="formInput" type="text" name="input_store_phone" placeholder="Phone No." maxlength="11" onkeypress="return NumbersOnly(event)" value="<?php echo $store->getPhone() ?>">
                  </div>
                  <select name="input_store_category" style="border-style:solid; border-width:thin">
                    <?php
                      $conn = createSqlConn();
                      $categ = SqlResultToArray("select * from category",$conn);
                      foreach ($categ as $key => $value) {
                        if($value['category_name'] == $store->getCategory())
                        {
                          echo '<option value="'.$value['category_name'].'" selected>'.$value['category_name'].'</option>';
                        }else {
                          echo '<option value="'.$value['category_name'].'">'.$value['category_name'].'</option>';
                        }
                      }
                      closeSqlConn($conn);
                    ?>
                  </select>
                  <textarea class="textAreaInput" id="store_description" rows="30" name="input_store_descr" placeholder="Store Description" maxlength="3000" oninput="countDescriptionChar()"><?php echo $store->getDescription() ?></textarea>
                  <div>
                    <span id="descrCharcount">0/3000</span>
                  </div>
                  <div id="StoreLocations">

                  </div>
                  <div id="Store Photos">

                  </div>
                  <div>
                    <input type="button" id="undoRemoveDiscountBtn" onclick="UndoRemoveFromDiscount()" value="Undo Delete" disabled="true">
                  </div>
                  <div id="storeDiscounts">
                    <?php
                      $discounts = $store->getDiscounts();
                      foreach ($discounts as $key => $value) {
                        echo '<div id="discount'.$value->getDiscountId().'" style="padding:20px;border-style:solid; border-width:thin; margin-top:20px">';
                        echo '<div style="float:right">';
                        echo '<a href="javascript:void(0)" style="margin-top:0" class="linkBtn" onclick="removeFromDiscount(this)" id="removeDiscount'.$value->getDiscountId().'">[X] Remove</a>';
                        echo '</div>';
                        echo 'Discount Name: '.$value->getName().'</br>';
                        echo 'Percentage: '.$value->getPercent().'%</br>';
                        echo 'Code: '.$value->getCode().'</br>';
                        echo 'Start Date: '.$value->getStartDate().'</br>';
                        echo 'Expire Date: '.$value->getExpireDate().'</br>';
                        echo 'Subcategory: '.$value->getSubCategory().'</br>';
                        echo '</div>';
                      }
                    ?>
                  </div>
                  <div>
                    <a href="javascript:void(0)" onclick="addDiscountFields()" class="linkBtn">+ Add Discount</a>
                  </div>
                  <div id="addDiscountsList">
                  </div>
                  <div id="addDiscount" style="display:none; margin-top: 20px;border-style:solid; border-width:thin; padding: 10px;">
                    <div style="float:right">
                      <a href="javascript:void(0)" style="margin-top:0" class="linkBtn" onclick="removeFromDiscountList(this)" id="removeListDiscount">[X] Remove</a>
                    </div>
                    <input type="text" placeholder="Type In Discount Name" name="input_discount_name" oninput="handleChange()"  onkeypress="return LettersOnly(event)" value="">
                    <input type="text" placeholder="Type In Discount Percent" name="input_discount_percent" oninput="handleChange()" onkeypress="return NumbersOnly(event)" max="100" min="0" value="">
                    <input type="date" placeholder="Enter Discount Start Date" name="input_discount_start" oninput="handleChange()" min="<?php echo getTodayDate(); ?>" value="">
                    <input type="date" placeholder="Enter Discount Expire Date" name="input_discount_expire" oninput="handleChange()" min="<?php echo getTodayDate(); ?>" value="">
                    <select name="input_discount_subcateg" style="border-style:solid; border-width:thin">
                      <?php
                        $conn = createSqlConn();
                        $subcateg = SqlResultToArray("select * from subcategory",$conn);
                        foreach ($subcateg as $key => $value) {
                          echo '<option value="'.$value['subcategory_name'].'">'.$value['subcategory_name'].'</option>';
                        }
                        closeSqlConn($conn);
                      ?>
                    </select>
                  </div>
                  <input id="newDiscounts" type="hidden" name="newDiscounts" value="">
                  <input id="discountsToRemove" type="hidden" name="discountsToRemove" value="">
                  <div id="errorMsg">
                  </div>
                  <input type="button" name="modify" value="Apply changes" onclick="modifyStoreFrm()">
                </form>
              </div>
            </div>
            <?php
                }else {
                  echo 'No page found';
                }
              }else {
                echo 'No page found';
              }
            ?>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      window.onload = Load();
      function Load() {
        <?php echo GetXandYScrollPositions();?>
        <?php echo getSessionDivMsg(3000);?>
      }
      function modifystoreForm() {
        var i = SetImage();
        if((i == -1 || i == false) && element("removeStorePhoto") == "false")
        {
          return false;
        }
        // AddXandYScrollToForm("modifystoreForm");
        return ApplyChanges();
      }
      var discountsToRemove = [];
      var discountsToRemoveDiv = [];
      function countDescriptionChar()
      {
        element("descrCharcount").innerHTML = (element("store_description").value).length + " / 3000"
      }
      function addDiscountFields()
      {
        var addDisountDiv = element("addDiscount");
        var childrenAddDiscount = addDisountDiv.children;
        for(var i =0; i < childrenAddDiscount.length; i++)
        {
          if(childrenAddDiscount[i].tagName == 'INPUT')
          {
            childrenAddDiscount[i].value = "";
          }
        }
        var newDiscountDiv = addDisountDiv.cloneNode(true)
        var addDiscChild = element("addDiscountsList").children
        newDiscountDiv.style.display = '';
        var removeBtn = getChildWithID(newDiscountDiv.children,"removeListDiscount")
        removeBtn.id = "removeListDiscount" + (addDiscChild.length+1).toString()
        newDiscountDiv.id = "addDiscount" + (addDiscChild.length+1).toString()
        element("addDiscountsList").appendChild(newDiscountDiv);
        // for(var i = 0; i < addDiscChild.length; i++)
        // {
        //   console.log(addDiscChild[i].id);
        // }
      }

      function getChildWithID(e, id)
      {
        for(var i = 0; i < e.length; i++)
        {
          if(e[i].id == id)
          {
            return e[i];
          }
          if(e[i].hasChildNodes())
          {
            var childrenChild = getChildWithID(e[i].children,id);
            if(childrenChild != null)
            {
              return childrenChild;
            }
          }
        }
        return null;
      }

      function removeFromDiscountList(e)
      {
        var addDiscountID = "addDiscount" +(e.id.replace("removeListDiscount","")).toString()
        element("addDiscountsList").removeChild(element(addDiscountID))
        element("errorMsg").innerHTML = ""
      }

      function UndoRemoveFromDiscount() {
        if(discountsToRemove.length > 0)
        {
          discountsToRemove.pop()
          element("storeDiscounts").appendChild(discountsToRemoveDiv.pop())
          if(discountsToRemove.length == 0)
          {
            element("undoRemoveDiscountBtn").disabled = "true";
          }
        }
      }

      function removeFromDiscount(e) {
        var DiscountID = "discount" +(e.id.replace("removeDiscount","")).toString()
        discountsToRemove.push((Number(e.id.replace("removeDiscount",""))));
        discountsToRemoveDiv.push(element(DiscountID));
        element("storeDiscounts").removeChild(element(DiscountID))
        element("undoRemoveDiscountBtn").disabled = "";
      }

      function ApplyChanges()
      {
        var arr = []
        var validNewDiscounts = []
        if(!element("storename").value)
        {
          element("errorMsg").innerHTML = "Please enter a store name";
          return false;
        }
        var addDiscChild = element("addDiscountsList").children;
        var datindx = 0;
        for(var i = 0; i < addDiscChild.length; i++)
        {
          var DiscountInputs = addDiscChild[i].children
          var newDiscount = {
            input_discount_name:null,
            input_discount_percent:null,
            input_discount_start: null,
            input_discount_expire: null
          }
          var sdate = null;
          var edate = null;
          for(var x = 0; x < DiscountInputs.length; x++)
          {
            if(DiscountInputs[x].tagName == 'INPUT' || DiscountInputs[x].tagName == 'SELECT')
            {
              if(DiscountInputs[x].value == null || DiscountInputs[x].value == "")
              {
                arr.push(addDiscChild[i])
                newDiscount = null;
                if(DiscountInputs[x].type == 'date')
                {
                    element("errorMsg").innerHTML = "Please fill in the date input";
                }else {
                    element("errorMsg").innerHTML = "Please fill all fields in the new discounts";
                }
                return false;
                // break;
              }else {
                if(DiscountInputs[x].name == "input_discount_percent")
                {
                  if(!isNaN(DiscountInputs[x].value))
                  {
                    if(Number(DiscountInputs[x].value) < 0 || Number(DiscountInputs[x].value) > 100)
                    {
                      element("errorMsg").innerHTML = "Please enter discount percentage value between 0 and 100";
                      return false;
                    }
                  }else {
                    element("errorMsg").innerHTML = "Please enter discount percentage value between 0 and 100";
                    return false;
                  }
                }
                if(DiscountInputs[x].type == 'date')
                {
                  var today = new Date();
                  today = new Date(today.getFullYear().toString() + '-' + (today.getMonth()+1).toString() + '-' + today.getDate().toString())
                  var d = new Date(DiscountInputs[x].value);
                  if(d < today)
                  {
                    element("errorMsg").innerHTML = "Please enter a valid date";
                    return false;
                  }
                  if(datindx == 0)
                  {
                    sdate = d;
                    datindx++;
                  }else if (datindx == 1) {
                    edate = d;
                    datindx++;
                  }
                }
                newDiscount[DiscountInputs[x].name] = DiscountInputs[x].value
              }
            }
          }
          if(newDiscount != null)
          {
            if(edate > sdate)
            {
              validNewDiscounts.push(newDiscount)
            }else {
              element("errorMsg").innerHTML = "Expire date is earlier than start date";
              return false
            }
          }
        }
        for(var emptyDiscount in arr)
        {
          element("addDiscountsList").removeChild(arr[emptyDiscount])
        }
        if(element("addDiscount"))
        {
          element("addDiscount").remove()
        }
        element("newDiscounts").value = JSON.stringify(validNewDiscounts);
        element("discountsToRemove").value = JSON.stringify(discountsToRemove);
        return true;
        // element("modifystoreForm").submit();
      }
      function handleChange()
      {
        element("errorMsg").innerHTML = ""
      }

      function SetImage() {
        if(element("store_image").value)
        {
          var tmpimagefilepath = element("store_image").value;
          var extension = tmpimagefilepath.split('.').pop().toLowerCase();
          var validImages = ['jpg','jpeg','png'];
          //0.5mb
          if(validImages.includes(extension))
          {
            element("imageErrorMsg").innerHTML = "Valid image";
            return true;
          }else {
            element("imageErrorMsg").innerHTML = "Invalid image";
            return false;
          }
        }else {
          element("imageErrorMsg").innerHTML = "";
          return -1;
        }
      }

      function previewPhoto() {
        var storeImageL = element("StorePhotoLink");
        var file = element("store_image").files[0];
        var reader  = new FileReader();
        reader.onloadend = function () {
             storeImageL.src = reader.result;
         }
        if(SetImage() != -1)
        {
          if(SetImage())
          {
            reader.readAsDataURL(file);
          }else {
            storeImageL.src = "'"+<?php echo displayStorePhoto() ?>+"'"
          }
        }else {
          storeImageL.src = "'"+<?php echo displayStorePhoto() ?>+"'"
        }
      }

      function ToggleRemoveStorePhoto() {
        if(element("removeStorePhoto").value == 'true')
        {
          element("removeStorePhotoBtn").innerHTML = "Remove Store Photo";
          element("removeStorePhoto").value = 'false';
          element("StorePhotoLink").src = "'"+<?php echo displayStorePhoto() ?>+"'"
        }else {
          element("removeStorePhotoBtn").innerHTML = "Undo Remove Store Photo";
          element("removeStorePhoto").value = 'true';
          element("StorePhotoLink").src = "icons/store.jpg";
        }
      }
    </script>
  </body>
</html>
