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
    <div id="container">
      <?php
      include '_fixedHeaderAndSideBar.php'
       ?>
      <div id="contentContainer">
        <div id="content">
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


          <div id="formContentContainer">
            <div id="formContent">
            <form onsubmit="return registrationForm()" id="registrationForm" action="form_handlers/_register.php" method="post">
              <a href="signup.php?regtype=<?php echo $_GET['regtype'] == '1'? '2' : '1' ?>">Register <?php echo $_GET['regtype'] == '1'? 'As Store Member' : 'As Student' ?></a>
              <span class="loginForm-title">Register <?php echo $_GET['regtype'] == '1'? 'As Student' : 'As Store Member' ?></span>
              <div id="errorMsgDiv" style="display:none">
              </div>
              <div class="inputWrap">
                <span class="inputLabel">Username</span>
              <input class="formInput" type="text" id="reg_usrname" name="input_username" placeholder="Type Your Username" value="" oninput="handleRegFormChange()">
              </div>
              <br />
              <br />
              <div class="inputWrap">
                <span class="inputLabel">Email</span>
              <input class="formInput" type="text" id="reg_email" name="input_email" placeholder="Type Your E-Mail"value="" oninput="handleRegFormChange()">
              </div>
              <br />
              <br />
              <div class="inputWrap">
                <span class="inputLabel">Password</span>
              <input class="formInput" type="password" id="reg_password" name="input_pw" placeholder="Type Your Password"value="" oninput="handleRegFormChange()">
              </div>
              <br />
              <br />
              <div class="inputWrap">
                <span class="inputLabel">First Name</span>
              <input class="formInput" type="text" id="reg_fname" name="input_fname" placeholder="Type Your First Name"value="" oninput="handleRegFormChange()">
              </div>
              <br />
              <br />
              <div class="inputWrap">
                <span class="inputLabel">Last Name</span>
              <input class="formInput" type="text" id="reg_lname" name="input_lname" placeholder="Type Your Last Name" value="" oninput="handleRegFormChange()">
              </div>
              <?php
              if($_GET['regtype'] == '1')
              {
              ?>
              <br />
              <br />
              <div class="inputWrap">
                <span class="inputLabel">Date Of Birth</span>
              <input type="date" id="reg_student_dob" name="input_dob" placeholder="Date Of Birth" value="" max="<?php echo getTodayDate(); ?>" oninput="handleRegFormChange()">
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
              <input type="date" id="reg_student_gradDate" name="input_graduation" placeholder="Graduation Date" value="" min="<?php echo getTodayDate(); ?>" oninput="handleRegFormChange()">
              </div>
              <?php
              }
              ?>
              <br />
              <br />
              <div style="border-style:dotted; color: black; padding:20px;margin-top:10px">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
              </div>
              <br />
              <br />
              <span class="inputLabel">Accept terms & conditions</span>
                <input type="checkbox" name="terms" onChange="handleRegFormChange()"></input>
              <input type="hidden" id="regType" name="regType" value="<?php echo $_GET['regtype']?>">
              <br />
              <br />
              <div class="wrapRegistration-btn">
              <input class="registration-btn" type="button" name="register" value="Register" onclick="signupFrm()">
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
