<?php
if(isset($_SESSION['student']) || isset($_SESSION['storemember']))
{

}else {
  header('Location: index.php');
}
?>
