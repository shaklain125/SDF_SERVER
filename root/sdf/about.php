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
      // if(isset($_SESSION['student']) || isset($_SESSION['storemember']))
      // {
      //   header('Location: index.php');
      // }
    ?>
    <div id="container">
      <div id="main">
        <?php
          include '_fixedHeaderAndSideBar.php'
         ?>
         <div id="contentContainer">
           <div id="content">
             <h1>Student Discount Finder</h1>
               <p>Home to Thousands of exclusive universitys deals. Sign up now and benefit from the the full range of deals and discounts and you're disposal  </p>
             <div id="formContentContainer">
             <div id="formContent">
                   <div style="display: flex; flex-direction: column; align-items: center; padding-top: 80px;">
                    <a href="login.php" class="formText">
                      Sign In
                    </a>
                  </div>
                  <div style="display: flex; flex-direction: column; align-items: center; padding-top: 80px;">
                   <a class href="signup.php" class="formText">
                     Sign Up
                   </a>
                 </div>
              </div>
              </div>
            </div>
          </div>
      </div>
     </div>
    <script type="text/javascript">
    </script>
  </body>
</html>
