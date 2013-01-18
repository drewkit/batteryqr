<?php
session_start();
require('../battery_static/database.inc');
require('../battery_include/battery_utilities.inc');

if (isset($_COOKIE['login_id']) && isset($_COOKIE['login_password'])) {
   $login_db = login_db_connect();
   $login_password = $_COOKIE['login_password'];
   $login_id = $_COOKIE['login_id'];
   
   $command = "SELECT bl.login_id,bl.login_name,bl.repository_id from battery_login bl, battery_repository br 
   where br.repository_id=bl.repository_id AND bl.login_password='".addslashes($login_password)."' 
   AND bl.login_id='".addslashes($login_id)."' AND bl.date_deleted<=0 AND br.date_deleted<=0";
   $result = mysql_query($command, $login_db);

//   $command2 = "SELECT repository_id from battery_repository WHERE date_deleted<=0"

   if (mysql_num_rows($result) == 1) {     
      $data = mysql_fetch_object($result);
      $_SESSION['login_id'] = $data->login_id;
      $_SESSION['login_name'] = $data->login_name;
      $_SESSION['repository_id'] = $data->repository_id;
      $qr = $_GET['qr'];
      
      //reset timer on cookie
      //thirty day expiration
      setcookie('login_id', $login_id, time() + (60 * 60 * 24 * 30));
      setcookie('login_password', $login_password, time() + (60 * 60 * 24 * 30));
      
      $_SESSION['login_admin'] = false;
      if (isset($qr)) {
         echo "<h3>You are now logged in</h3>
         <h3>Please try your QR code again</h3>";
         exit();
      }
      else {
         header("Location: index.php");
         exit();
      }
   }
}
if ($_SESSION['login_id'] && $_SESSION['login_name'] && $_SESSION['repository_id']) {
   header("Location: index.php");
   exit();
}
if (count($_POST) > 0) {
   $login_db = login_db_connect();
   
   $qr = $_POST['qr'];
   $login_name = $_POST['login_name'];
   $login_password = $_POST['login_password'];
   $good_name = "^[a-zA-Z0-9_]{3,25}$";
   $good_password = "^[a-zA-Z0-9_!]{3,12}$";
   $cookie = $_POST['cookie'];
   
   if (!($login_name && $login_password)) {
      $error_message = "Please make sure you've filled out both fields";
   }
   else if (!(valid_input($login_name, $good_name))) {
      $error_message = "Login must be 3 to 25 characters in length and alphanumeric";
   }
   else if (!(valid_input($login_password, $good_password))) {
      $error_message = "Password must be no greater than 12 characters in length";
   }
   else {
	   $command = "SELECT bl.login_id,bl.login_admin,bl.login_name,bl.repository_id from battery_login bl, battery_repository br 
	   where br.repository_id=bl.repository_id AND bl.login_name='".addslashes($login_name)."' AND bl.login_password=password('".addslashes($login_password)."') 
	   AND bl.date_deleted<=0 AND br.date_deleted<=0";
      $result = mysql_query($command, $login_db);
      
      if (mysql_num_rows($result) <= 0) {
         $error_message = "You have entered either an incorrect password or login.  Please contact your account administrator if you are unable to access your account.<br>";
      }
      else {
         $data = mysql_fetch_object($result);
         $_SESSION['login_id'] = $data->login_id;
         $_SESSION['login_name'] = $login_name;
         $_SESSION['repository_id'] = $data->repository_id;
         $login_admin = $data->login_admin;
         
         if ($login_admin) {
            $_SESSION['login_admin'] = true;
            header("Location: admin.php");
            exit();
         }
         else {
         
            if ($cookie) {
               $command = "SELECT login_password from battery_login where login_id=".$_SESSION['login_id'];
               $result = mysql_query($command, $login_db);
               $data = mysql_fetch_object($result);
               $login_password = $data->login_password;
               
               $expiration = time() + (60 * 60 * 24 * 30); //thirty day expiration
               setcookie('login_id', $_SESSION['login_id'], time() + (60 * 60 * 24 * 30));
               setcookie('login_password', $login_password, time() + (60 * 60 * 24 * 30));
               
               if ($qr) {

                  header("Location: login.php?qr=1");
                  exit();
               }
               else {
                  header("Location: login.php");
                  exit();
               }
               /* $test1 = $_COOKIE['login_id'];
               $test2 = $_COOKIE['login_password'];
               echo "test print cookies <br>1: ".$test1." <br>and 2: ".$test2."
               <br>click to <a href='logout.php'>logout</a> here and try again";
               exit(); */
            }
         
            $_SESSION['login_admin'] = false;
            if ($qr) {
               echo "<h3>You are now logged in</h3>
               <h3>Please try your QR code again</h3>";
               exit();
            }
            else {
               header("Location: index.php");
               exit();
            }
        }
      }
   }
}
include('../battery_include/login_header.inc');
?>
<?

if ($error_message) {
   echo "<span style='color:red;'>".$error_message."</span>";
}
?>
<span style="color:orange;">
<?
$confirmation = $_GET['confirmation'];
switch($confirmation) {
   case "1":
      echo "You have logged out successfully.<br />";
   break;
}
?>
</span>
<? 
   echo "<h2>Use QR codes to track and eliminate bad batteries once and for all.</h2>
   <hr />";

   ?>
   <form action="login.php" method="post">
   <p>
   <label for="login_name">Login: </label>
   <input type="text" name="login_name" title="login name goes here" size="12" />
   <label for="login_password">Password: </label>
   <input type="password" name="login_password" size="12" title= "login password goes here" />
   <label for="cookie">Keep me logged in: <input type="checkbox" name="cookie" title="cookie is here" /></label>
   <?
   if ($_GET['qr']) {
      ?>
      <input type='hidden' name='qr' value='1'>
      <?
   }
   ?>
   <br /><br />
   <input class="button" type="submit" value="Sign In" />
   </p>
   </form>
   <p>New to BatteryQR? Setup your battery repository <a href='signup.php'>here</a>.</p>
   <?
   
include('../battery_include/battery_footer.inc');
?>