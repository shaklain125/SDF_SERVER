<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <?php
      include '_importPhp.php';
      startSession();
      include '_importStyle.php';
    ?>
    <script type="text/javascript">
      function updateProfileFrm() {
        if(!profileForm())
        {
          return;
        }
        var fd = new FormData(element('profileForm'));
        var reqData = fd;
        console.log($('#profileForm').serialize())
        var req = new XMLHttpRequest();
        req.onload = () =>
        {
          var respData = null;
          try {
            respData = JSON.parse(req.responseText)
          } catch (e) {

          }
          console.log(respData)
          if (respData) {
            updateProfileFormResultHandler(respData)
          }
        }
        req.open('post', 'form_handlers/_updateProfile.php');
        req.send(reqData);
      }

      function updateProfileFormResultHandler(response) {
        if(response.message)
        {
          ShowSessionDivMsg(response.message);
          setTimeout("HideSessionDivMsg()",3000);
          element("currentpw").value = ""
          element("newpw").value = ""
          element("checknewpw").value = ""
          changebtntoggle = false;
          element("changepw").value = false;
          pwMatch = false;
          changebtntoggle = false;
          element("checkNewPwDiv").innerHTML = ""
          element("changePwDiv").style.display = "none";
        }else if (response.errorMsg) {
          element("errorMsgDiv").innerHTML = response.errorMsg;
          element("errorMsgDiv").style.display = ""
        }
      }

      function rateFrm(rating, formid) {
        var reqData = null
        if(rating)
        {
          reqData = 'rate=GOOD&'
        }else {
          reqData = 'rate=BAD&'
        }
        reqData += $('#'+formid).serialize();
        var req = new XMLHttpRequest();
        req.onload = () =>
        {
          var respData = null;
          try {
            respData = JSON.parse(req.responseText)
          } catch (e) {

          }
          if (respData) {
            rateFormResultHandler(respData)
          }
        }
        req.open('post', 'form_handlers/_rateDiscount.php');
        req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        req.send(reqData);
      }

      function rateFormResultHandler(response) {
        if(response.message == 'ok')
        {
          location.reload();
        }
      }

    </script>
    <style media="screen">
      #profilePhoto{
        border-radius: 50%;
        border-style: solid;
        border-width: 0.5px;
        border-color: #ccc;
        width: 150px;
        height: 150px;
        background-image: url('<?php echo displayProfilePhoto() ?>');
        background-repeat: no-repeat;
        /* background-size: 150px 150px; */
        background-size: auto 100%;
        background-position: 50% 50%;
        background-clip: padding-box;
        margin-left: auto;
        margin-right: auto;
        <?php echo getBgColor() ?>')
      }
      #profilephotoContainer{
        background: rgb(34,34,34); /* for IE */
        background: rgba(34,34,34,0.8);
        height: 100%;
        padding: 50px;
      }
      #coverProfilePhoto{
        background-image: url('<?php echo displayProfilePhoto() ?>');
        background-repeat: no-repeat;
        /* background-size: 150px 150px; */
        background-size: 100% auto;
        background-position: 50% 50%;
        background-clip: padding-box;
        width:100%;
        text-align:center;
        height:300px;
      }
    </style>
  </head>
  <body>
    <?php
      include '_checkLoggedIn.php';

      function getBgColor() {
        if(isset($_SESSION['student']) || isset($_SESSION['storemember']))
        {
          $user = null;
          if(isset($_SESSION['student']))
          {
            $user = unserialize($_SESSION['student']);
          }else {
            $user = unserialize($_SESSION['storemember']);
          }
          if(!is_dir('users/'.$user->getUserId().'/'))
          {
            return '';
          }else {
            return 'background-color:black;';
          }
        }
      }
      function keepProfileValues($inputname, $alt, $last) {
        $k = keepData($inputname, $last);
        if($k == '')
        {
          return $alt;
        }else {
          return $k;
        }
      }

      function displayProfilePhoto() {
        if(isset($_SESSION['student']) || isset($_SESSION['storemember']))
        {
          $user = null;
          if(isset($_SESSION['student']))
          {
            $user = unserialize($_SESSION['student']);
          }else {
            $user = unserialize($_SESSION['storemember']);
          }
          if(!is_dir('users/'.$user->getUserId().'/'))
          {
            return 'icons/profile.png';
          }else {
            return 'users/'.$user->getUserId().'/profile.png';
          }
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
            <?php
            function getAccountType() {
              if (isset($_SESSION['student'])) {
                return '<div>'.strtoupper(unserialize($_SESSION['student'])->getUsername()).'</div>';
              }elseif (isset($_SESSION['storemember'])) {
                return '<div>'.strtoupper(unserialize($_SESSION['storemember'])->getUsername()).'</div>';
              }
            }
             ?>
            <?php
              $user = null;
              if(isset($_SESSION['student']) || isset($_SESSION['storemember']))
              {
            ?>
            <div class="accountPrefTitle" style="padding:20px; margin-top: 20px;font-size: 15pt;text-align:left; border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;">
              <h4 style="margin:0; font-family:arial;">Profile: <?php echo isset($_SESSION['student'])?'Student':'Store Member'?></h4>
            </div>
            <form style="margin-top: 10px;" id="profileForm" enctype="multipart/form-data">
              <div id="profilePicContainer">
                <a class="linkBtn" id="removeProfilePhotoBtn" style="padding:5;float:right;margin:0" href="javascript:ToggleRemoveProfilePhoto()">Remove Profile Photo</a>
                <div id="coverProfilePhoto">
                  <input type="hidden" id="removeProfilePhoto" name="removeProfilePhoto" value="false">
                  <div id="profilephotoContainer">
                    <div id="profilePhoto"></div>
                  </div>
                  <!-- <img  src="icons/profile.png" width="150px" height="150px" alt="profilePic"> -->
                </div>
                <div style="background-color:white; padding:20px; color:black; padding-bottom:0px;">
                  <h2 style="margin:0;"><?php echo getAccountType()?></h2>
                </div>
                <div style="font-family:arial;color:black;background-color:white;padding:20px;">
                  Upload Profile Photo:
                  <input type="file" name="profile_image" onchange="previewPhoto()" id="profile_image">
                  <div id="imageErrorMsg"></div>
                </div>
              </div>
              <div class="accountPrefTitle" style="padding:10px; margin-top: 20px;font-size: 12pt;text-align:center; border-bottom-left-radius: 0px;border-bottom-right-radius: 0px;">
                <h4 style="margin:0; font-family:arial;">User Details</h4>
              </div>
              <?php
                  if(isset($_SESSION['student']))
                  {
                    $user = unserialize($_SESSION['student']);
                  }else {
                    $user = unserialize($_SESSION['storemember']);
                  }
              ?>
              <input type="hidden" name="userid" value="<?php echo $user->getUserId()?>">
              <!-- <input type="text" name="input_username" placeholder="Username" value="<?php echo $user->getUsername()?>" oninput="handleMsgChange()"> -->
              <div id="formContentContainerAccount" style="padding:0px;">
                <div id="formContentAccount" style="margin-top:0px; padding-top: 20px;border-top-left-radius:0px; border-top-right-radius:0px;">
                  <div class="inputWrap">
                    <span class="inputLabel">Email</span>
                    <input class="formInput" type="email" name="input_email" placeholder="Type Your E-Mail" value="<?php echo $user->getEmail() ?>" oninput="handleMsgChange()" onkeypress="return EmailCharsOnly(event)" pattern="*@*">
                  </div>
                  <div id="ChangePass">
                    <div>
                      <a href="javascript:void(0)" onclick="changePassword()" class="linkBtn">Change Password</a>
                    </div>
                    <div id="changePwDiv" style="margin-bottom: 20px; display:none;padding:20px; border-style:solid;border-width:thin; border-color: black;">
                      <div class="inputWrap">
                        <span class="inputLabel">Current Password</span>
                        <input class="formInput" type="password" id="currentpw" name="currentPass" placeholder="Type Your Current Password" value="" onkeypress="return NoSpaces(event)">
                      </div>
                      <div class="inputWrap">
                        <span class="inputLabel">New Password</span>
                        <input class="formInput" type="password" id="newpw" name="newPw" placeholder="Type a New Password" oninput="CheckNewPW()" value="" oninput="handleMsgChange()"  onkeypress="return NoSpaces(event)">
                      </div>
                      <div class="inputWrap">
                        <span class="inputLabel">Confirm New Password</span>
                        <input class="formInput" type="password" id="checknewpw" placeholder="Confirm Password" oninput="CheckNewPW()" value="" oninput="handleMsgChange()"  onkeypress="return NoSpaces(event)">
                      </div>
                      <div id="checkNewPwDiv">
                      </div>
                    </div>
                  </div>
                  <div class="inputWrap" style="margin-top: 20px;">
                    <span class="inputLabel">First Name</span>
                    <input class="formInput" type="text" name="input_fname" placeholder="Type Your First Name"value="<?php echo $user->getFirstName()?>" oninput="handleMsgChange()" onkeypress="return LettersOnly(event)">
                  </div>
                  <div class="inputWrap">
                    <span class="inputLabel">Last Name</span>
                    <input class="formInput" type="text" name="input_lname" placeholder="Type Your Last Name" value="<?php echo $user->getLastName()?>" oninput="handleMsgChange()" onkeypress="return LettersOnly(event)">
                  </div>
                  <?php
                      if(isset($_SESSION['student']))
                      {
                  ?>
                  <div class="inputWrap">
                    <span class="inputLabel">Date Of Birth</span>
                    <input class="formInput" type="date" name="input_dob" placeholder="Type Your Date Of Birth" value="<?php echo $user->getDob() ?>" max="<?php echo getTodayDate(); ?>" oninput="handleMsgChange()">
                  </div>
                  <div class="inputWrap">
                    <select name="input_university" onchange="handleMsgChange()">
                    <?php
                      $conn = createSqlConn();
                      $university = SqlResultToArray("select * from university",$conn);
                      foreach ($university as $key => $value) {
                        if($value['university_name'] == $user->getUniversity())
                        {
                          echo '<option value="'.$value['university_name'].'" selected>'.$value['university_name'].'</option>';
                        }else {
                          echo '<option value="'.$value['university_name'].'">'.$value['university_name'].'</option>';
                        }
                      }
                      closeSqlConn($conn);
                    ?>
                  </select>
                </div>
                  <div class="inputWrap">
                    <span class="inputLabel">Graduation Date</span>
                    <input class="formInput" type="date" name="input_graduation" placeholder="Type Your Graduation Date" value="<?php echo $user->getGradDate() ?>" min="<?php echo getTodayDate(); ?>" oninput="handleMsgChange()">
                  </div>
              </div>
            </div>
              <?php
                }
              ?>
              <input type="hidden" id="changepw" name="changepw" value="">
              <?php
                if(isset($_SESSION['student']))
                {
              ?>
                <div class="accountPrefTitle" style="">
                  <h4>Preferences</h4>
                </div>
              <?php
                  $conn = createSqlConn();
                  $categ = SqlResultToArray("select * from category",$conn);
                  $subcateg = SqlResultToArray("select * from subcategory",$conn);
                  $categIndx = 1;
                  $db_prefCategs = unserialize($user->getPrefCategories());
                  $db_prefSubCategs = unserialize($user->getPrefSubCategories());
                  // if($db_prefCategs == false || $db_prefSubCategs == false)
                  // {
                  //   $db_prefCategs = array();
                  //   $db_prefSubCategs = array();
                  // }
                  foreach ($subcateg as $key1 => $value1) {
                    foreach ($db_prefCategs as $key2 => $value2) {
                      if($value1['category_name'] == $value2)
                      {
                        array_push($db_prefSubCategs,$value1['subcategory_name']);
                        break;
                      }
                    }
                  }
                  foreach ($categ as $key1 => $value1) {
                    $category = $value1['category_name'];
                    $c = replaceSpacesAndchars($category);
                    echo '<div class="accountPref" style="">';
                    echo '<input type="checkbox" '.setCategSubCategChecked($db_prefCategs,$category).' onchange="ToggleCategory(this)" id="'.$c.'" name="categ'.$categIndx.'" value="'.$category.'" style="float:left;">';
                    echo '<span style="float:left">'.$category.'</span>';
                    echo '</div>';
                    echo '<div class="accountSubPref" id="'.$c.'-subContainer" style="overflow:hidden;">';
                    $indx = 1;
                    foreach ($subcateg as $key2 => $value2) {
                      if($value2['category_name'] == $category)
                      {
                        $subcategory = $value2['subcategory_name'];
                        $s = replaceSpacesAndchars($subcategory);
                        echo '<div id="'.$c.'-subCateg'.$indx.'" style="float:left; margin:5px;">';
                        echo '<input type="checkbox" '.setCategSubCategChecked($db_prefSubCategs,$subcategory).' style="float:left;" onchange="ToggleSubCategory(this)" id="'.$c.'_'.$indx.'" name="subCateg_'.$categIndx.'_'.$indx.'" value="'.$subcategory.'">';
                        echo '<span style="float:left;">'.$subcategory.'</span>';
                        echo '</div>';
                        $indx++;
                      }
                    }
                    echo '</div>';
                    $categIndx++;
                  }
                  closeSqlConn($conn);
                }
                ?>
                <div id="errorMsgDiv" style="display:none">
                </div>
                <div <?php echo isset($_SESSION['student'])? 'class="accountPrefBtn"':''?>>
                  <input type="button" name="saveProfile" value="Save Settings" onclick="updateProfileFrm()">
                </div>
                </form>
                <?php
                if(isset($_SESSION['student']))
                {
                  // echo '<div>Select Categories here</div>';
                  // echo '<div>category name  -------  checkbox</div>';
                  // echo '<div>subcategories  -------  checkboxes</div>';
                  // echo '<div>Save account settings button</div>';
                  echo '<div class="accountPrefBtn">';
                  echo '<div style="margin-right:10px;display:inline-block;"><a href="history.php" class="linkBtn">Store History</a></div>';
                  echo '<div style="display:inline-block;"><a href="favouritestores.php" class="linkBtn">Favourite Stores</a></div>';
                  echo '</div>';
              ?>
              <div class="accountPrefTitle" style="padding:20px; margin-top: 20px;font-size: 15pt;text-align:center;">
                <h4 style="margin:0; font-family:arial;">Claimed Discounts</h4>
              </div>
              <div>
                <?php
                  $claimedL = $user->getStudentClaims();
                  $claimedL = array_reverse($claimedL);
                  if(sizeof($claimedL) == 0)
                  {
                    echo '<div class="accountPref" style="padding:20px; border-top:0;text-align:left; border-style:solid; border-width:thin;">';
                    echo 'No discounts claimed';
                    echo '</div>';
                  }else {
                    $updateclaimedL = $claimedL;
                    $studentclaimidsToRemove = array();
                    foreach ($claimedL as $key => $value) {
                        $discount = $value->getDiscount();
                        $storeName = null;
                        if($value->isDiscountAvailable())
                        {
                          $storeName = (new store($discount->getStoreId()))->getName();
                        }else {
                          $storeName = $value->getStoreName();
                        }
                        // $discount->getDiscountId();
                        // $discount->getCode();
                        // $discount->getStartDate();
                        $cond1 = $value->isDiscountAvailable() && !$value->isUsed();
                        $cond2 = !$value->isDiscountAvailable() && $value->isUsed();
                        $cond3 = $value->isDiscountAvailable() && $value->isUsed();
                        if(($cond1 && $value->isDiscountExpired()) || (!$value->isDiscountAvailable() && !$value->isUsed() && $value->isDiscountExpired()))
                        {
                          unset($updateclaimedL[$key]);
                          array_push($studentclaimidsToRemove, $value->getStudentClaimID());
                          continue;
                        }
                        if($cond1 || $cond2 || $cond3)
                        {
                          echo '<div class="claimedDiscount">';
                          if(!$value->isUsed())
                          {
                            echo '<p style="margin-bottom:20px;margin-top:0px;border-width:thin;border-top:0; border-left:0;border-right:0; border-style:solid; padding-bottom:5px;">Discount Expires: '.$discount->getExpireDate().'</p>';
                          }else {
                            if($value->isDiscountRated())
                            {
                              $dr = $value->getDiscountRating();
                              if($dr == '1')
                              {
                                $dr = 'GOOD';
                              }elseif ($dr == '0') {
                                $dr = 'BAD';
                              }
                              echo '<p style="margin:0;margin-bottom:20px;font-family:arial;font-weight:900;">CODE USED: '.$dr.'</p>';
                            }else {
                              echo '<p style="margin:0;margin-bottom:20px;font-family:arial;font-weight:900;">CODE USED</p>';
                            }
                          }
                          echo '<h2 style="text-align:center; margin-bottom:20px;font-family:arial;">'.$storeName.'</h2>';
                          echo '<h4 style="text-align:center; margin:2px;font-family:arial;">'.$discount->getPercent().'% OFF '.$discount->getName().'</h4>';
                          echo '<p style="text-align:center;font-family:arial;">'.$discount->getSubCategory().'</p>';
                          if($value->isUsed())
                          {
                            echo '<h3 style="text-align:center;font-family:arial;">'.$discount->getCode().'</h3>';
                          }
                  ?>
                  <?php
                          if(!$value->isUsed())
                          {
                  ?>
                            <form id="showDiscountForm<?php echo $value->getStudentClaimID() ?>">
                              <input type="hidden" name="discountid" value="<?php echo $discount->getDiscountId() ?>">
                              <input type="hidden" name="studentclaimid" value="<?php echo $value->getStudentClaimID() ?>">
                              <p style="text-align:center;"><input type="button" name="showcode" value="Show Code" onclick="showDiscountFrm('showDiscountForm<?php echo $value->getStudentClaimID() ?>')"></p>
                            </form>
                  <?php
                          }else{
                            if(!$value->isDiscountRated())
                            {
                              echo '<p>What do you think about this discount?</p>';
                ?>
                <?php
                            }else {
                              echo '<p>Change Rating</p>';
                            }
                ?>
                            <form id="rateForm<?php echo $discount->getDiscountId();?>">
                              <input type="hidden" name="studentclaimid" value="<?php echo $value->getStudentClaimID()?>">
                              <input type="button" style="padding:5px;margin:0;" name="rate" value="GOOD" onclick="rateFrm(true, 'rateForm<?php echo $discount->getDiscountId();?>')">
                              <input type="button" style="padding:5px;margin:0;" name="rate" value="BAD" onclick="rateFrm(false, 'rateForm<?php echo $discount->getDiscountId();?>')">
                            </form>
                <?php
                          }
                          echo '</div>';
                ?>
                  <?php
                        }
                    }
                    $up = array();
                    foreach ($updateclaimedL as $key => $value) {
                      array_push($up, $value->getStudentClaimID());
                    }
                    $up = array_reverse($up);
                    $user->setClaimedListArray($up,$studentclaimidsToRemove);
                    echo '</div>';
                }
                ?>
              </div>
              <?php
                }else {
                  echo '<div style="margin-top:10px"><a href="managestores.php" class="linkBtn">Manage Stores</a></div>';
                }
              ?>
            <?php
              }else {
                echo 'No session set';
              }
              function replaceSpacesAndchars($val) {
                $val = str_replace(' ', '',$val);
                $val = str_replace('&', '_',$val);
                return $val;
              }
              function setCategSubCategChecked($arr, $checkVal)
              {
                $checked = false;
                foreach ($arr as $key => $value) {
                  if($checkVal == $value)
                  {
                    $checked = true;
                    break;
                  }
                }
                if($checked)
                {
                  $checked = 'checked';
                }else {
                  $checked = '';
                }
                return $checked;
              }
            ?>
              <form onsubmit="return removeAccountForm()" id="removeAccountForm" action="form_handlers/_removeAccount.php" method="post">
                <div style="text-align:center">
                  <a href="#" class="linkBtn" style="padding:0px"><input style="width:auto;margin:0px;background-color:transparent;border:none;color:white" type="submit" value="[x] Remove Account"></a>
                </div>
              </form>
          </div>
      </div>
    </div>
    <script type="text/javascript">
      var pwMatch;
      var changebtntoggle;
      var removeAcc;
      window.onload = Load();

      function Load() {
        <?php echo GetXandYScrollPositions();?>
        changebtntoggle = null;
        removeAcc = false;
        <?php echo getSessionDivMsg(3000);?>
      }

      function removeAccountForm() {
        if(!removeAcc)
        {
          var c = new ConfirmDialog("Are you sure you want to remove your account?",RemoveAcc,CancelRemoveAcc);
          c.show();
          return false;
        }else {
          return true;
        }
      }

      function RemoveAcc() {
        removeAll = true;
        var r = document.createElement("input");
        r.type="hidden";
        r.name="removeAccount";
        element("removeAccountForm").appendChild(r);
        element("removeAccountForm").submit();
      }

      function CancelRemoveAcc() {
        removeAcc = false;
      }

      function changePassword() {
        if (element("changePwDiv").style.display == "none") {
          element("currentpw").value = ""
          element("newpw").value = ""
          element("checknewpw").value = ""
          changebtntoggle = true;
        }else {
          changebtntoggle = false;
        }
        element("changePwDiv").style.display = element("changePwDiv").style.display == "none"? "" : "none";
      }

      function ClearDivInputs(divid) {
        var c = element(divid).children;
        for(var i=0; i < c.length; i++)
        {
          if(c[i].tagName == "INPUT" && c[i].type != "button" && c[i].type != "submit")
          {
            c[i].value = ""
          }
        }
      }
      function CheckNewPW() {
        if(element("newpw").value == element("checknewpw").value)
        {
          if(element("newpw").value != "")
          {
            pwMatch = true;
            element("checkNewPwDiv").innerHTML = "New password matches"
          }else {
            element("checkNewPwDiv").innerHTML = ""
            pwMatch = false;
          }
        }else {
          if(element("checknewpw").value && element("checknewpw").value)
          {
            element("checkNewPwDiv").innerHTML = "New password don't match"
          }else {
            element("checkNewPwDiv").innerHTML = ""
          }
          pwMatch = false;
        }
      }

      function update()
      {
        var i = SetImage();
        if(i != -1)
        {
          if(i)
          {
            if(changebtntoggle == null || changebtntoggle == false)
            {
              element("changepw").value = false;
            }else {
              if(!pwMatch)
              {
                return false;
              }else {
                element("changepw").value = true;
              }
            }
            return true;
          }else {
            return false
          }
        }else {
          if(changebtntoggle == null || changebtntoggle == false)
          {
            element("changepw").value = false;
          }else {
            if(!pwMatch)
            {
              return false;
            }else {
              element("changepw").value = true;
            }
          }
          return true;
        }
        // element("profileForm").submit();
      }
      function handleMsgChange()
      {
        element('errorMsgDiv').innerHTML = "";
      }
      function ToggleCategory(e) {
        element('errorMsgDiv').innerHTML = "";
        var el = element(e.id.toString() + '-subContainer').children;
        for(var x = 1; x < el.length+1; x++)
        {
          var childCat = element(e.id.toString()+'-subCateg' + x.toString()).children;
          for(var y = 0; y < childCat.length; y++)
          {
            if(childCat[y].tagName == "INPUT")
            {
              childCat[y].checked = e.checked;
            }
          }
        }
      }
      function ToggleSubCategory(e) {
        element('errorMsgDiv').innerHTML = "";
        var subCId = e.id.toString();
        var categID = subCId.substring(0,subCId.lastIndexOf('_'));
        var el = element(categID + '-subContainer').children;
        for(var x = 1; x < el.length+1; x++)
        {
          var childCat = element(categID +'-subCateg' + x.toString()).children;
          for(var y = 0; y < childCat.length; y++)
          {
            if(childCat[y].tagName == "INPUT")
            {
              if(childCat[y].checked)
              {
                element(categID).checked = true;
              }else {
                element(categID).checked = false;
                return
              }
            }
          }
        }
      }

      function CloseOverlayCode() {
        document.body.removeChild(element("overlay-DiscountCode"));
      }

      function showDiscountFrm(formid) {
        var reqData = 'showcode=&' + $('#' + formid).serialize();
        var req = new XMLHttpRequest();
        req.onload = () =>
        {
          var respData = null;
          try {
            respData = JSON.parse(req.responseText)
          } catch (e) {

          }
          if (respData) {
            ShowOverlayCode(respData)
          }
        }
        req.open('post', 'form_handlers/_showDiscountCode.php');
        req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        req.send(reqData);
      }

      function ShowOverlayCode(response) {
        if(response.message == 'ok')
        {
          var overlay = document.createElement("div")
          overlay.id = "overlay-DiscountCode"
          overlay.style.display = "block"
          overlay.style.padding = "20px"
          overlay.innerHTML = '<h4>Click inside the box to exit</h4>'+
          '<div style="border-style:solid; margin-top:50px;cursor: pointer;" onclick="CloseOverlayCode()">'+
          '<h1>' +response.storename+ '</h1>'+
          '<h2>' +response.dname+ '</h2>'+
          '<h2>' +response.subcateg+ '</h2>'+
          '<h3>' +response.expire+ '</h3>'+
          '<h1>' +response.code+ '</h1>'+
          '</div>'+
          '<form id="useDiscountForm">'+
          '<input type="hidden" name="studentclaimid" value="' +response.sclaimid+ '">'+
          '<div><input type="button" name="usecode" value="Use Discount" onclick="useDiscountFrm()"></div>'+
          '</form>'
          document.body.appendChild(overlay);
        }
      }

      function useDiscountFrm() {
        var reqData = 'usecode=&' + $('#useDiscountForm').serialize();
        var req = new XMLHttpRequest();
        req.onload = () =>
        {
          var respData = null;
          try {
            respData = JSON.parse(req.responseText)
          } catch (e) {

          }
          if (respData) {
            useDiscountFormResultHandler(respData)
          }
        }
        req.open('post', 'form_handlers/_useDiscount.php');
        req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        req.send(reqData);
      }

      function useDiscountFormResultHandler(response) {
        if(response.message)
        {
          location.reload()
        }
      }

      function profileForm() {
        var s = update();
        if(!s)
        {
          return false;
        }
        // AddXandYScrollToForm("profileForm");
        return true;
      }

      function SetImage() {
        if(element("profile_image").value)
        {
          var tmpimagefilepath = element("profile_image").value;
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
        var profileimage = element("profilePhoto");
        var file = element("profile_image").files[0];
        var reader  = new FileReader();
        reader.onloadend = function () {
             profileimage.style.backgroundImage = "url('" +reader.result +"')";
             element("coverProfilePhoto").style.backgroundImage = "url('" +reader.result +"')";
         }
        if(SetImage() != -1)
        {
          if(SetImage())
          {
            reader.readAsDataURL(file);
          }else {
            profileimage.style.backgroundImage = "url('<?php echo displayProfilePhoto() ?>')";
            element("coverProfilePhoto").style.backgroundImage = "url('<?php echo displayProfilePhoto() ?>')";
          }
        }else {
          profileimage.style.backgroundImage = "url('<?php echo displayProfilePhoto() ?>')";
          element("coverProfilePhoto").style.backgroundImage = "url('<?php echo displayProfilePhoto() ?>')";
        }
      }

      function ToggleRemoveProfilePhoto() {
        if(element("removeProfilePhoto").value == 'true')
        {
          element("removeProfilePhotoBtn").innerHTML = "Remove Profile Photo";
          element("removeProfilePhoto").value = 'false';
          element("profilePhoto").style.backgroundImage = "url('<?php echo displayProfilePhoto() ?>')";
          element("coverProfilePhoto").style.backgroundImage = "url('<?php echo displayProfilePhoto() ?>')";
        }else {
          element("removeProfilePhotoBtn").innerHTML = "Undo Remove Profile Photo";
          element("removeProfilePhoto").value = 'true';
          element("profilePhoto").style.backgroundImage = "url('icons/profile.png')";
          element("coverProfilePhoto").style.backgroundImage = "url('icons/profile.png')";
        }
      }

    </script>
  </body>
</html>
