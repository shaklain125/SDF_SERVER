<div id="mySidebar" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
  <a href="index.php" class="active">Home</a>
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
    echo '<a href="javascript:void(0)">About</a>';
    echo '<a href="logout.php">Logout</a>';
  }else {
    echo '<a href="login.php">Log in</a>';
    echo '<a href="signup.php">Sign up</a>';
    echo '<a href="javascript:void(0)">About</a>';
  }
   echo '<span id="copyrightInfo">Copyright (c) '.date("Y").' by Student Discount Finder Corporation. All Rights Reserved.</span>';
  ?>

</div>
<div class="topFixedDiv">
  <div style="position: absolute;">
    <button id="menuBtn" class="openbtn" onclick="openNav()">☰</button>
  </div>
  <div>
    <h3 id="logo" onclick="location.href='index.php';">Student Discount Finder</h1>
    <h3 id="stylePref"></h3>
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

  window.addEventListener('onload', addCopyRight());

  function addCopyRight() {
    var copyrightSpan = '<?php echo 'Copyright (c) '.date("Y").' by Student Discount Finder Corporation. All Rights Reserved.';?>';
    var footer = document.createElement("footer");
    footer.id = "copyrightInfoBottom"
    footer.innerHTML = copyrightSpan;
    document.body.appendChild(footer)
  }

  window.addEventListener("load",function () {
    SetStylePref();
  });

  function SetStylePref() {
    <?php
    if(isset($_SESSION['student']) || isset($_SESSION['storemember']))
    {
      $user = null;
      if(isset($_SESSION['student']))
      {
        $user = unserialize($_SESSION['student']);
      }else {
        $user = unserialize($_SESSION['storemember']);
      }
      $stylePref = $user->getStylePref();
    ?>
    var stylePref = <?php echo $stylePref ?>;
    if(stylePref == 0)
    {
      document.body.style.backgroundColor = '#f2f2f2';
      document.body.style.color = 'black';
      changeClassStyle('overlay', 'background-color', 'black');
    }else if (stylePref == 1) {
      document.body.style.backgroundColor = 'white';
    }
    <?php
    }
    ?>
  }
</script>

<script type="text/javascript" src="js/script.js"></script>
