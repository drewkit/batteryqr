<?php
session_start();
//blocks out admin from attempting to access members only pages

if ($_SESSION['login_admin'] == true) {
   header("Location: admin.php");
   exit();
}

if (!($_SESSION['login_id'] && $_SESSION['login_name'] && $_SESSION['repository_id'])) {
   $navigation_page = basename($_SERVER['PHP_SELF']);
   if ($navigation_page == 'update_status.php') {
      header("Location: login.php?qr=1");
      exit();
   }
   else {
   
      header("Location: login.php");
      exit();
   }
}
else {
   $login_id = $_SESSION['login_id'];
   $login_name = $_SESSION['login_name'];
   $repository_id = $_SESSION['repository_id'];
   $repository_name = get_repository_name($repository_id);
   $tz = get_time_zone($repository_id);
   date_default_timezone_set($tz);
}
$page = 1;
$page = $_GET['page'];
if (!is_numeric($page)){
  $page = 1;
}

?>