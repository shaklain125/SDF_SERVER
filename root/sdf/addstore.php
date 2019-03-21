<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <?php include '_importPhp.php';include '_importStyle.php';?>
    <script type="text/javascript">
      function addStoreFrm() {
        var formdata = $("#addStoreForm").serialize();
        var reqData = 'addStore=1&'+ formdata;
        var req = new XMLHttpRequest();
        req.onload = () =>
        {
          var respData = null;
          try {
            respData = JSON.parse(req.responseText)
          } catch (e) {

          }
          if (respData) {
            addStoreFormResultHandler(respData)
          }
        }
        req.open('post', 'form_handlers/_addstore.php');
        req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        req.send(reqData);
      }

      function addStoreFormResultHandler(response) {
        var x = 0
        console.log(response)
        if(response.status == 'true')
        {
          document.location.href = "managestores.php";
          return;
        }
        var errorMsg = response.errorMsg;
        var errDiv = element("errorMsgDiv");
        errDiv.innerHTML = errorMsg;
        errDiv.style.display = ""
        window.scrollTo(0,0);
        var postd = response.postdata;
        for(var i in postd)
        {
          if(x != 0)
          {
            var e = element(i);
            var etagname = e.tagName.toLowerCase()
            if(etagname == "input")
            {
              e.value = postd[i]
            }else if (etagname == "textarea") {
              e.innerHTML = postd[i]
            }
          }
          x++;
        }
      }
    </script>
  </head>
  <body>
    <?php
      startSession();
      include '_checkLoggedIn.php';
      $user = unserialize($_SESSION['storemember']);
    ?>
    <div id="container">
      <div id="main">
        <?php
        include '_fixedHeaderAndSideBar.php'
         ?>
        <div id="contentContainer">
          <div id="content">
            <h3 style="text-align: center;">Add store page</h3>
            <div id="formContentContainer">
              <div id="formContentAddStore">
                <form id="addStoreForm">
                  <div id="errorMsgDiv" style="display:none">
                  </div>
                  <div class="inputWrap">
                    <span class="inputLabel">* Store name</span>
                    <input class="formInput" type="text" id="input_store_name" name="input_store_name" placeholder="Input Store name" value="">
                  </div>
                  <div class="inputWrap">
                    <span class="inputLabel">* Website</span>
                    <input class="formInput" type="text" id="input_store_website" name="input_store_website" placeholder="Input Website" value="">
                  </div>
                  <div class="inputWrap">
                    <span class="inputLabel">* Phone No.</span>
                    <input class="formInput" type="text" id="input_store_phone" name="input_store_phone" placeholder="Input Phone No." maxlength="11" onkeypress="return NumbersOnly(event)" value="">
                  </div>
                  <select id="input_store_category" name="input_store_category" style="border-style:solid; border-width:thin">
                    <?php
                      $conn = createSqlConn();
                      $categ = SqlResultToArray("select * from category",$conn);
                      foreach ($categ as $key => $value) {
                        echo '<option value="'.$value['category_name'].'">'.$value['category_name'].'</option>';
                      }
                      closeSqlConn($conn);
                    ?>
                  </select>
                  <textarea  class="textAreaInput" id="input_store_descr" rows="30" name="input_store_descr" placeholder="Store Description" maxlength="3000" oninput="countDescriptionChar()"></textarea>
                  <div>
                    <span id="descrCharcount">0/3000</span>
                  </div>
                  <input type="button" name="addStore" value="Create Store Page" onclick="addStoreFrm()">
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      function countDescriptionChar()
      {
        element("descrCharcount").innerHTML = (element("input_store_descr").value).length + " / 3000"
      }
    </script>
  </body>
</html>
