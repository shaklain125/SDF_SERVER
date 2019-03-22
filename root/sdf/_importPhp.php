<?php

header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

include 'classes/_registereduser.php';
include 'classes/_student.php';
include 'classes/_studentclaim.php';
include 'classes/_storemember.php';
include 'classes/_store.php';
include 'classes/_discount.php';
include 'classes/_database.php';
include '_helper.php';
include '_updateUserSession.php';
?>
