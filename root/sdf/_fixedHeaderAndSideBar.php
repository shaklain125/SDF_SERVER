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
      <input id="searchBar" type="text" placeholder="Search..." name="q" value="">
      <input style="display:none" type="submit">
    </form>
  </div>
</div>
<script type="text/javascript" src="js/script.js"></script>
