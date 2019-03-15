<?php
  startSession();
  if(isset($_SESSION['student']))
  {
    $_SESSION['student'] = serialize(new student(unserialize($_SESSION['student'])->getUserId()));
  }elseif (isset($_SESSION['storemember'])) {
    $_SESSION['storemember'] = serialize(new storemember(unserialize($_SESSION['storemember'])->getUserId()));
  }
?>
