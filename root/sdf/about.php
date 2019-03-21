<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <?php include '_importPhp.php';include '_importStyle.php'; ?>
    <style>
      body{
        background-image: url("./css/aboutBackground.gif");
        background-repeat: no-repeat;
        background-size:cover;
        background-position:center;
         display: flex;
        justify-content: center;

        position: relative;
        padding: 0;
        min-height: calc(100vh - 40px);
        flex-direction: row;
      }
      .tint {
        z-index: 1;
        height: 100%;
        width: 100%;
        position: absolute;
        top: 0px;
        left: 0px;
        background: rgba(0, 0, 0, 0.7);
      }
      .aboutBtn{
        text-decoration: none;
        background-color: green;
        color: white;
        font-size: 20px;
        padding: 15px 50px 15px 50px;
        margin: 20px;
        border-right: 1px solid #333333;
        border-bottom: 1px solid #333333;
        border-radius:5px;
        float:left;
      }
    </style>
  </head>
  <body>
    <?php
      startSession();
      // if(isset($_SESSION['student']) || isset($_SESSION['storemember']))
      // {
      //   header('Location: index.php');
      // }
    ?>
    <div class = "tint" id="container">
      <div id="main">
        <?php
          include '_fixedHeaderAndSideBar.php'
         ?>
         <div id="contentContainer">
           <div style="display: flex; flex-direction: column; align-items: center; padding-top: 80px;" id="content">
             <h1 style="margin: 20px;">Student Discount Finder</h1>
               <p style="margin: 20px;">Home to Thousands of Exclusive Student Deals. Sign Up Now and Benefit From a Large Range of Deals and Discounts Afforded to You!</p>
                   <div>
                    <a class="aboutBtn" href="signup.php" class="formText">
                     Sign Up
                   </a>
                   <a class="aboutBtn"style="
  "href="login.php" class="formText">
                      Sign In
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
