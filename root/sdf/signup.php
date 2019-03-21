<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <?php include '_importPhp.php';include '_importStyle.php'; ?>
  </head>
  <body>
    <?php
    startSession();
    if(isset($_SESSION['student']) || isset($_SESSION['storemember']))
    {
      header('Location: index.php');
    }
    // if(isset($_SESSION['postdata']))
    // {
    //   print_r($_SESSION['postdata']);
    //   echo '<br />'.sizeof($_SESSION['postdata']).'<br />';
    //   foreach ($_SESSION['postdata'] as $key => $value) {
    //     echo $key.' > '.$value.' > LEN:'.strlen($value).'<br />';
    //   }
    // }
    ?>
    <div id="container" style="margin-top:50px; padding-top:5%;">
      <div id="main">
        <?php
        if(!isset($_GET['regtype']))
        {
          header('location: signup.php?regtype=1');
        }else {
          if($_GET['regtype'] != '1' && $_GET['regtype'] != '2')
          {
            header('location: signup.php?regtype=1');
          }
        ?>
        <?php
        include '_fixedHeaderAndSideBar.php'
         ?>
        <div id="contentContainer">
          <div id="content">
            <div id="formContentContainer">
              <div id="formContent">
              <form  id="registrationForm" action="#">
                <div style="text-align:center; margin-bottom:20px">
                  <a id="registerALink" href="signup.php?regtype=<?php echo $_GET['regtype'] == '1'? '2' : '1' ?>">Register <?php echo $_GET['regtype'] == '1'? 'As Store Member' : 'As Student' ?></a>
                </div>
                <span class="loginForm-title">Register <?php echo $_GET['regtype'] == '1'? 'As Student' : 'As Store Member' ?></span>
                <div class="inputWrap">
                  <span class="inputLabel">Username</span>
                <input class="formInput" type="text" id="reg_usrname" name="input_username" placeholder="Type Your Username" value="" onkeypress="return NoSpaces(event) && (LettersOnly(event) || NumbersOnly(event))" oninput="handleRegFormChange()" required>
                </div>
                <br />
                <br />
                <div class="inputWrap">
                  <span class="inputLabel">Email</span>
                <input class="formInput" type="email" id="reg_email" name="input_email" placeholder="Type Your E-Mail"value="" onkeypress="return EmailCharsOnly(event)" oninput="handleRegFormChange()" required pattern="*@*">
                </div>
                <br />
                <br />
                <div class="inputWrap">
                  <span class="inputLabel">Password</span>
                <input class="formInput" type="password" id="reg_password" name="input_pw" placeholder="Type Your Password"value="" onkeypress="return NoSpaces(event)" oninput="handleRegFormChange()" required>
                </div>
                <br />
                <br />
                <div class="inputWrap">
                  <span class="inputLabel">First Name</span>
                <input class="formInput" type="text" id="reg_fname" name="input_fname" placeholder="Type Your First Name"value="" onkeypress="return LettersOnly(event)" oninput="handleRegFormChange()" required>
                </div>
                <br />
                <br />
                <div class="inputWrap">
                  <span class="inputLabel">Last Name</span>
                <input class="formInput" type="text" id="reg_lname" name="input_lname" placeholder="Type Your Last Name" value="" onkeypress="return LettersOnly(event)" oninput="handleRegFormChange()" required>
                </div>
                <?php
                if($_GET['regtype'] == '1')
                {
                ?>
                <br />
                <br />
                <div class="inputWrap">
                  <span class="inputLabel">Date Of Birth</span>
                <input  class="formInput" type="date" id="reg_student_dob" name="input_dob" placeholder="Date Of Birth" value="" max="<?php echo getTodayDate(); ?>" oninput="handleRegFormChange()" required>
                </div>
                <br />
                <br />
                <select id="reg_student_university" name="input_university" onchange="handleRegFormChange()">
                  <?php
                    $conn = createSqlConn();
                    $university = SqlResultToArray("select * from university",$conn);
                    foreach ($university as $key => $value) {
                      echo '<option value="'.$value['university_name'].'">'.$value['university_name'].'</option>';
                    }
                    closeSqlConn($conn);
                  ?>
                </select>
                <br />
                <br />
                <br />
                <div class="inputWrap">
                  <span class="inputLabel">Graduation Date</span>
                <input  class="formInput" type="date" id="reg_student_gradDate" name="input_graduation" placeholder="Graduation Date" value="" min="<?php echo getTodayDate(); ?>" oninput="handleRegFormChange()" required>
                </div>
                <?php
                }
                ?>
                <br />
                <br />
                <div id="termsAndPolicy" style="border-style:dotted;padding:20px;margin-top:10px" required>
                  Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
                </div>
                <br />
                <br />
                <span class="inputLabel">Accept terms & conditions</span>
                <input type="checkbox" name="terms" style="margin-top 20px; margin-left:10px;" onChange="handleRegFormChange()"></input>
                <input type="hidden" id="regType" name="regType" value="<?php echo $_GET['regtype']?>">
                <br />
                <br />
                <div class="wrapRegistration-btn">
                <input class="registration-btn" type="button" name="register" value="Register" onclick="signupFrm()">
                </div>
                <div id="errorMsgDiv" style="display:none">
                </div>
                <div style="display: flex; flex-direction: column; align-items: center; padding-top: 30px; font-size:12pt; font-weight:normal; font-family:arial;">
                <span>Already registered?  <a href="login.php" class="formText">Sign in</a></span>
               </div>
              </form>
            </div>
            </div>
            <?php
            }
            ?>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
    window.onload = Load()
    function Load() {
      <?php echo GetXandYScrollPositions();?>
    }
    function signupFrm() {
      var reqData = 'register=1&'+ $('#registrationForm').serialize();
      var req = new XMLHttpRequest();
      req.onload = () =>
      {
        var respData = null;
        try {
          respData = JSON.parse(req.responseText)
        } catch (e) {

        }
        if (respData) {
          signupFormResultHandler(respData)
        }
      }
      req.open('post', 'form_handlers/_register.php');
      req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
      req.send(reqData);
    }

    function signupFormResultHandler(response) {
      if(response.errorMsg)
      {
        element('errorMsgDiv').style.display = '';
        element('errorMsgDiv').innerHTML = response.errorMsg;
      }else if (response.message) {
        document.location.href = 'index.php'
      }
      console.log(response);
    }

      function handleRegFormChange()
      {
        if(element('errorMsgDiv').innerHTML != null)
        {
          element('errorMsgDiv').style.display = 'none'
        }
      }
    </script>
  </body>
</html>
