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
          <h2 id="RegisterH2">Register <?php echo $_GET['regtype'] == '1'? 'As Student' : 'As Store Member' ?></h2>
          <div id="registrationdiv">
            <form onsubmit="return registrationForm()" id="registrationForm" action="form_handlers/_register.php" method="post">
              <div id="errorMsgDiv" style="display:none">
              </div>
              <input type="text" id="reg_usrname" name="input_username" placeholder="Username" value="" oninput="handleRegFormChange()">
              <input type="text" id="reg_email" name="input_email" placeholder="E-Mail"value="" oninput="handleRegFormChange()">
              <input type="password" id="reg_password" name="input_pw" placeholder="Password"value="" oninput="handleRegFormChange()">
              <input type="text" id="reg_fname" name="input_fname" placeholder="First Name"value="" oninput="handleRegFormChange()">
              <input type="text" id="reg_lname" name="input_lname" placeholder="Last Name" value="" oninput="handleRegFormChange()">
              <?php
              if($_GET['regtype'] == '1')
              {
              ?>
              <input type="date" id="reg_student_dob" name="input_dob" placeholder="Date Of Birth" value="" max="<?php echo getTodayDate(); ?>" oninput="handleRegFormChange()">
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
              <input type="date" id="reg_student_gradDate" name="input_graduation" placeholder="Graduation Date" value="" min="<?php echo getTodayDate(); ?>" oninput="handleRegFormChange()">
              <?php
              }
              ?>
              <div style="border-style:dotted; padding:20px;margin-top:10px">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
              </div>
              <div>
                <input type="checkbox" name="terms" onChange="handleRegFormChange()">Accept terms & conditions</input>
              </div>
              <input type="hidden" id="regType" name="regType" value="<?php echo $_GET['regtype']?>">
              <input type="button" name="register" value="Register" onclick="signupFrm()">
            </form>
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
