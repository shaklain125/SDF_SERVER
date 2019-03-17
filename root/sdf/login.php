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
    ?>
    <div id="container">
      <?php
      include '_fixedHeaderAndSideBar.php'
       ?>
       <div id="contentContainer">
         <div id="content">
           <div id="formContentContainer">
           <div id="formContent">
               <form id="loginForm">
                 <span class="loginForm-title">
                   Login
                 </span>
                 <div class="inputWrap">
                  <span class="inputLabel">Username</span>
                   <input class="formInput" type="text" name="signin_username" onkeydown="keyHandle(event)" oninput="handlechange()" placeholder="Type Your Username" value="">
                 </div>
                 <br />
                 <br />
                 <div class="inputWrap">
                  <span class="inputLabel">Password</span>
                   <input class="formInput" type="text" name="signin_pw" onkeydown="keyHandle(event)" oninput="handlechange()" placeholder="Type Your Password"value="">
                 </div>

            <br />
            <br />
                 <div id="errorMsgDiv" style="display:none">
                 </div>
                 <div class="wrapLogin-btn">
                   <input class="login-btn" type="button" name="signin" value="Log In" onclick="signinFrm()">
                 </div>
                 <div style="display: flex; flex-direction: column; align-items: center; padding-top: 80px;">
                  <a href="signup.php" class="formText">
                    Sign Up
                  </a>
                </div>
              </form>
           </div>
         </div>
         </div>
       </div>
    </div>
    <script type="text/javascript">
      // window.onload = Load()
      // function Load() {
      //   <?php echo GetXandYScrollPositions();?>
      // }
      // function loginForm() {
      //   AddXandYScrollToForm("loginForm");
      //   return true
      // }
      function signinFrm() {
        var reqData = 'signin=&'+ $('#loginForm').serialize();
        var req = new XMLHttpRequest();
        req.onload = () =>
        {
          var respData = null;
          try {
            respData = JSON.parse(req.responseText)
          } catch (e) {

          }
          if (respData) {
            signinFormResultHandler(respData)
          }
        }
        req.open('post', 'form_handlers/_sign_in.php');
        req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        req.send(reqData);
      }

      function signinFormResultHandler(response) {
        if(response.errorMsg)
        {
          element("errorMsgDiv").style.display = "";
          element("errorMsgDiv").innerHTML = response.errorMsg;
        }else if (response.message == 'success') {
          document.location.href = "index.php";
        }
      }
      function keyHandle(e) {
        if(e.keyCode == 13)
        {
          signinFrm()
        }
      }
      function handlechange() {
        element("errorMsgDiv").style.display = "none";
        element("errorMsgDiv").innerHTML = "";
      }
    </script>
  </body>
</html>
