<div id="mySidebar" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <?php
    if((!LoggedIn() && (basename($_SERVER['SCRIPT_NAME']) != 'about.php')) || LoggedIn())
    {
?>
  <a href="index.php" class="active">Home</a>
  <?php
      }
    ?>
  <?php
    if(isset($_SESSION['student']) || isset($_SESSION['storemember']))
    {
      $conn = createSqlConn();
      $category = SqlResultToArray("select * from category",$conn);
      $subcategory = SqlResultToArray("select * from subcategory",$conn);
      foreach ($category as $key1 => $value1) {
        echo '<button class="dropdown-btn">'.$value1['category_name'].'</button>';
        echo '<div class="dropdown-container">';
        foreach ($subcategory as $key2 => $value2) {
          if($value1['category_name'] == $value2['category_name'])
          {
            echo '<a href="search.php?subcategory='.urlencode($value2['subcategory_name']).'">'.$value2['subcategory_name'].'</a>';
          }
        }
        echo '</div>';
      }
      closeSqlConn($conn);
    }
  ?>
  <?php
  if(isset($_SESSION['student']) || isset($_SESSION['storemember']))
  {
    echo '<a href="account.php">Account</a>';
    echo '<a href="about.php">About</a>';
    echo '<a href="logout.php">Logout</a>';
  }else {
    echo '<a href="login.php">Log in</a>';
    echo '<a href="signup.php">Sign up</a>';
  }
  ?>

</div>
<div class="topFixedDiv">
  <div style="position: absolute;">
    <button id="menuBtn" class="openbtn" onclick="openNav()">☰</button>
  </div>
  <div>
    <h3 id="logo" onclick="location.href='index.php';">Student Discount Finder</h1>
  </div>
  <?php
    if(LoggedIn())
    {
  ?>
  <div style="position:absolute; top:0;right:0">
    <div id="searchCollapsebtn" onclick="ToggleSearchBar()"></div>
  </div>
  <?php
    }
  ?>
  <div id="searchBarDiv" style="text-align:center; padding:20px; width:100%; display:none;">
    <?php
    $query = null;
    if(isset($_GET['q']))
    {
      if(!empty($_GET['q']))
      {
        $query = $_GET['q'];
      }
    }
    ?>
    <form onsubmit="return searchForm()" id="seachForm" action="search.php" method="get">
      <!-- <?php echo $query == null?'':$query; ?> -->
      <input id="searchBar" type="text" autocomplete="off" placeholder="Search..." oninput="LiveSearch()" name="q" value="">
      <input style="display:none" type="submit">
      <div id="liveSearchDiv" style="height:0px; overflow-y:scroll;">

      </div>
    </form>
  </div>
</div>

<script type="text/javascript">
  function LiveSearch() {
    var q = ValidSearchQuery();
    if(!q)
    {
      element('liveSearchDiv').style.height = '0px';
      return;
    }
    element('liveSearchDiv').style.height = '185px';
    var reqData = 'liveSearch=' + encodeURIComponent(q);
    var req = new XMLHttpRequest();
    req.onload = () =>
    {
      var respData = null;
      try {
        respData = JSON.parse(req.responseText)
      } catch (e) {

      }
      if (respData) {
        LiveSearchResultHandler(respData)
      }
    }
    req.open('post', 'form_handlers/_liveSearch.php');
    req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    req.send(reqData);
  }

  function LiveSearchResultHandler(response) {
    var r = response.results;
    var resultLinks = []
    for(var i in r)
    {
      resultLinks.push('<a class="livesearch" href="store.php?id='+i+'"><div class="livesearch">'+r[i]+'</div></a>')
    }
    element('liveSearchDiv').innerHTML = resultLinks.join("")
  }

  window.addEventListener('onload', addFooter());

  function addCopyRight() {
    var copyrightSpan = '<?php echo '<div id="copyrightInfoBottom">Copyright (c) '.date("Y").' by Student Discount Finder Corporation. All Rights Reserved.</div>';?>';
    return copyrightSpan;
  }

  function addFooter() {
    var footer = document.createElement("footer");
    footer.innerHTML = addCopyRight();
    footer.id = 'footer';
    footer.style.backgroundColor = "<?php echo !LoggedIn()?'rgb(28,28,28)':''?>";
    footer.style.border = "<?php echo !LoggedIn()?'none':''?>";
    document.body.appendChild(footer)
  }

  window.addEventListener('onload',addDarkMode())

  function addDarkMode() {

    var div = document.createElement('div')
    div.innerHTML = addDarkModeSwitch()
    document.getElementById('main').insertBefore(div, document.getElementById('main').childNodes[0]);
  }
  function addDarkModeSwitch() {
    var e = null;
    <?php
    $user = null;
    if(isset($_SESSION['storemember']) || isset($_SESSION['student']))
    {
      if(isset($_SESSION['student']))
      {
        $user = unserialize($_SESSION['student']);
      }else {
        $user = unserialize($_SESSION['storemember']);
      }
    }
      if($user != null)
      {
        if((basename($_SERVER['SCRIPT_NAME']) != 'about.php'))
        {
          ?>
          e = '<div id="darkmodeToggle" style="margin-top:50px; padding:20px; padding-bottom:0px; text-align:right;"><span>Dark Mode: </span><button id="darkmodeSwitchBtn" type="button" name="button" onclick="changeStyle(this)"><?php echo $user->getStylePref() == 1?"ON":"OFF"?></button></div>'
          <?php
        }
      }
    ?>
    return e;
  }

  function changeStyle(e) {
    <?php
    if(LoggedIn())
    {
    ?>
    if(e.innerHTML == 'OFF')
    {
      e.innerHTML = 'ON'
    }else {
      e.innerHTML = 'OFF'
    }
    var reqData = 'style=' + e.innerHTML;
    var req = new XMLHttpRequest();
    req.onload = () =>
    {
      var respData = null;
      console.log(req)
      try {
        respData = JSON.parse(req.responseText)
      } catch (e) {

      }
      if (respData) {
        if(respData.message == 'ok')
        {
          if(e.innerHTML == 'ON')
          {
            document.getElementById('web_style').setAttribute("href", 'css/dark_style.css')
          }else {
            document.getElementById('web_style').setAttribute("href", 'css/style.css')
          }
        }
      }
    }
    req.open('post', 'form_handlers/_styleChange.php');
    req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    req.send(reqData);

    <?php
  }else {
    ?>
    if(e.innerHTML == 'OFF')
    {
      e.innerHTML = 'ON'
      localStorage.setItem('style','ON');
    }else {
      e.innerHTML = 'OFF'
      localStorage.setItem('style','OFF');
    }
    if(e.innerHTML == 'ON')
    {
      document.getElementById('web_style').setAttribute("href", 'css/dark_style.css')
    }else {
      document.getElementById('web_style').setAttribute("href", 'css/style.css')
    }
    <?php
  }
    ?>
  }
</script>

<script type="text/javascript" src="js/script.js"></script>
