<?php

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include 'classes/_registereduser.php';
include 'classes/_student.php';
include 'classes/_studentclaim.php';
include 'classes/_storemember.php';
include 'classes/_store.php';
include 'classes/_discount.php';
include '_helper.php';
include '_updateUserSession.php';
?>
