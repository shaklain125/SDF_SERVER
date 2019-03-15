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
          <h2 id="LoginH2">Log in</h2>
          <div id="signindiv">
            <form onsubmit="return loginForm()" id="loginForm" action="form_handlers/_sign_in.php" method="post">
              <input type="text" name="signin_username" placeholder="Username" value="">
              <input type="text" name="signin_pw" placeholder="Password"value="">
              <div id="errorMsgDiv" style="display:<?php echo errorExists(); ?>">
                <?php echo ShowError(); ?>
              </div>
              <input type="submit" name="signin" value="Log In">
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
      function loginForm() {
        AddXandYScrollToForm("loginForm");
        return true
      }
    </script>
  </body>
</html>
