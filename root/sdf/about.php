<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <style>
    #myVideo {
      position: fixed;
      top:50px;
      min-width: 100%;
      min-height: 100%;
      width: 100%;
      height: auto;
      z-index: -5;
      object-position: 58% 50%;
    }
    video {
      object-fit: cover;
    }

      /* body{
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
      } */
      /* .tint {
        z-index: 1;
        height: 100%;
        width: 100%;
        position: absolute;
        top: 0px;
        left: 0px;
        background: rgba(0, 0, 0, 0.7);
      } */
      .aboutBtn{
        text-decoration: none;
        background-color: green;
        color: white;
        font-size: 12pt;
        padding: 15px 20px 15px 20px;
        margin: 20px;
        border-right: 1px solid #333333;
        border-bottom: 1px solid #333333;
        border-radius:5px;
        overflow: hidden;
        float:left;
      }
    </style>
    <?php include '_importPhp.php';include '_importStyle.php'; ?>
  </head>
  <body>
    <div id="container" style="background: rgba(0, 0, 0, 0.6)">
      <div id="main">
        <?php
          include '_fixedHeaderAndSideBar.php'
         ?>
         <div id="contentContainer" style="margin-top:50px;text-align:center; color:white;">
           <div  style="display: flex; flex-direction: column; align-items: center; padding-top: 80px;" id="content">
             <video autoPlay muted loop id="myVideo">
                <source src="media/video.mp4" type="video/mp4"/>
            </video>
             <h1 style="margin: 20px; text-align:center; color:white;">Student Discount Finder</h1>
               <p style="margin: 20px;text-align:center; color:white; font-family:arial; font-size:12pt;">Home to Thousands of Exclusive Student Deals. Sign Up Now and Benefit From a Large Range of Deals and Discounts Afforded to You!</p>
               <?php
               if(!LoggedIn())
               {
                 ?>
                 <div id="aboutBtnLinks">
                   <a class="aboutBtn" href="signup.php" class="formText">Sign Up</a>
                   <a class="aboutBtn" href="login.php" class="formText">Sign In</a>
                 </div>
                 <?php
               }
               ?>
            </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
    window.addEventListener('onload', function () {
      element("footer").style.backgroundColor = "rgb(28,28,28)";
    })
    </script>
  </body>
</html>
