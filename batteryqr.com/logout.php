<?php

session_start();
$_SESSION['login_id'] = '';
$_SESSION['login_user'] = '';
$_SESSION['repository_id'] = '';
$_SESSION['login_admin'] = '';

setcookie("login_id", "", time()-3600);
setcookie("login_password", "", time()-3600);

header("Location: login.php?confirmation=1");
exit();

?>