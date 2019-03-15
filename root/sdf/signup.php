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
    function displayReg()
    {
      if(isset($_SESSION['display'][0]))
      {
        $val = $_SESSION['display'][0];
        return $val;
      }else {
        return 'none';
      }
    }
    function displayStudentDiv()
    {
      if(isset($_SESSION['display'][1]))
      {
        $val = $_SESSION['display'][1];
        unset($_SESSION['display']);
        return $val;
      }else {
        return 'none';
      }
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
          <h2 id="RegisterH2">Register</h2>
          <div id="registrationdiv">
            <div style="overflow:hidden">
              <button type="button" class="regUserBtn" onclick="ViewStoreMRegInputs()">Store Member</button>
              <button type="button" class="regUserBtn" onclick="ViewStudentRegInputs()">Student</button>
            </div>
            <form onsubmit="return registrationForm()" id="registrationForm" action="form_handlers/_register.php" method="post" style="display:<?php echo displayReg(); ?>">
              <div id="errorMsgDiv" style="display:<?php echo errorExists(); ?>">
                <?php echo ShowError(); ?>
              </div>
              <div id="reg_default_inputs" style="display:<?php echo displayReg(); ?>">
                <input type="text" id="reg_usrname" name="input_username" placeholder="Username" value="<?php echo keepData('input_username',false); ?>" oninput="handleRegFormChange()">
                <input type="text" id="reg_email" name="input_email" placeholder="E-Mail"value="<?php echo keepData('input_email',false); ?>" oninput="handleRegFormChange()">
                <input type="password" id="reg_password" name="input_pw" placeholder="Password"value="<?php echo keepData('input_pw',false); ?>" oninput="handleRegFormChange()">
                <input type="text" id="reg_fname" name="input_fname" placeholder="First Name"value="<?php echo keepData('input_fname',false); ?>" oninput="handleRegFormChange()">
                <input type="text" id="reg_lname" name="input_lname" placeholder="Last Name" value="<?php echo keepData('input_lname',false); ?>" oninput="handleRegFormChange()">
              </div>
              <div id="reg_student" style="display:<?php echo displayStudentDiv(); ?>">
                <input type="date" id="reg_student_dob" name="input_dob" placeholder="Date Of Birth" value="<?php echo keepData('input_dob',false); ?>" max="<?php echo getTodayDate(); ?>" oninput="handleRegFormChange()">
                <select id="reg_student_university" name="input_university" onchange="handleRegFormChange()">
                  <?php
                    $savedVal = keepData('input_university', false);
                    $conn = createSqlConn();
                    $university = SqlResultToArray("select * from university",$conn);
                    foreach ($university as $key => $value) {
                      if($value['university_name'] == $savedVal)
                      {
                        echo '<option value="'.$value['university_name'].'" selected>'.$value['university_name'].'</option>';
                      }else {
                        echo '<option value="'.$value['university_name'].'">'.$value['university_name'].'</option>';
                      }
                    }
                    closeSqlConn($conn);
                  ?>
                </select>
                <input type="date" id="reg_student_gradDate" name="input_graduation" placeholder="Graduation Date" value="<?php echo keepData('input_graduation', false); ?>" min="<?php echo getTodayDate(); ?>" oninput="handleRegFormChange()">
              </div>
              <div style="border-style:dotted; padding:20px;margin-top:10px">
                Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.
              </div>
              <div>
                <input type="checkbox" name="terms" onChange="handleRegFormChange()">Accept terms & conditions</input>
              </div>
              <input type="hidden" id="regType" name="regType" value="<?php echo keepData('regType', true); ?>">
              <input type="submit" name="register" value="Register">
            </form>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      window.onload = Load()
      function Load() {
        <?php echo GetXandYScrollPositions();?>
      }
      function registrationForm() {
        AddXandYScrollToForm("registrationForm");
        return true
      }
      function ViewStudentRegInputs()
      {
        element("regType").value = "1"
        if(element("registrationForm").style.display == "none")
        {
          element("registrationForm").style.display = "";
        }
        element("reg_default_inputs").style.display = ""
        element("reg_student").style.display = ""
      }
      function ViewStoreMRegInputs()
      {
        element("regType").value = "2"
        element("reg_student").style.display = "none"
        if(element("registrationForm").style.display == "none")
        {
          element("registrationForm").style.display = "";
        }
        element("reg_default_inputs").style.display = element("registrationForm").style.display
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
