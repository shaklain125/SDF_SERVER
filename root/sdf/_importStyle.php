<html manifest="manifest.appcache">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<?php
startSession();
$user = null;
$val = null;
if(isset($_SESSION['student'])  || isset($_SESSION['storemember']))
{
  if(isset($_SESSION['student']))
  {
    $user = unserialize($_SESSION['student']);
  }else if(isset($_SESSION['storemember'])) {
    $user = unserialize($_SESSION['storemember']);
  }
  $val = $user->getStylePref() == 0?'css/style.css' : 'css/dark_style.css';
}else {
  $val = 'css/style.css';
}

?>
<link id="web_style" rel="stylesheet" type="text/css" href="<?php echo $val?>">
<link rel="stylesheet" href="https://use.typekit.net/bwt3wbq.css">
