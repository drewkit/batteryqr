<?php
session_start();
require('../battery_static/database.inc');
require('../battery_include/battery_utilities.inc');
if ($_SESSION['login_id'] && $_SESSION['login_name'] && $_SESSION['repository_id']) {
   header("Location: index.php");
   exit();
}

if (count($_POST) > 0) {
   $login_db = login_db_connect();
   $good_name = "^[a-zA-Z0-9_]{3,19}$";
   $good_password = "^[a-zA-Z0-9_!]{3,12}$";
   $user_password1 = $_POST['user_password1'];
   $user_password2 = $_POST['user_password2'];
   $repository_name = $_POST['repository_name'];
   $tz = $_POST['tz'];
   
   if (!($repository_name && $user_password1 && $user_password2 && $tz)) {
      $error_message = "Please make sure you've filled out both fields";
   }
   else if (!($user_password1 == $user_password2)) {
      $error_message = "Please enter the same password in the fields";
   }
   else if (!(valid_input($repository_name, $good_name))) {
      $error_message = "Repository name must be 3 to 19 characters in length and alphanumeric. No spaces.";
   }
   else if (!(valid_input($user_password1, $good_password))) {
      $error_message = "Password must be no greater than 12 characters in length and in alphanumeric format";
   }
   else {
      $success = true;
      $command = "SET AUTOCOMMIT=0";
      $result = mysql_query($command, $login_db);
      
      $command = "BEGIN";
      $result = mysql_query($command, $login_db);
      
      $command = "SELECT login_name from battery_login WHERE login_name='".$repository_name."'";
      $result = mysql_query($command, $login_db);
      
      if (mysql_num_rows($result) > 0) {
         $success = false;
      }
      
      $command = "SELECT repository_name FROM battery_repository where repository_name='".addslashes($repository_name)."'";
      $result = mysql_query($command, $login_db);
      
      if (mysql_num_rows($result) > 0) {
         $success = false;
         $error_message = "There is already a battery repository with this name.  Please choose another";
      }
      
      $command = "INSERT INTO battery_repository(repository_name, repository_time_zone) values('".addslashes($repository_name)."','".addslashes($tz)."')";
      $result = mysql_query($command, $login_db);
      $repository_id = mysql_insert_id($login_db);
      
      if ($result == false) {
         $success = false;
      }
      
      $command = "INSERT INTO battery_login values('','".addslashes($repository_name)."',PASSWORD('".addslashes($user_password1)."'),1,".$repository_id.",0)";
      $result = mysql_query($command, $login_db);
      $login_id = mysql_insert_id($login_db);  
      if ($result == false) {
         $success = false;
          if (!$error_message) {
	         $error_message = "There was an error processing your request.";
	      }
      } 
      
      if ($result) {
         $command = "COMMIT";
         $result = mysql_query($command, $login_db);
         
         $command = "SET AUTOCOMMIT=1";
         $result = mysql_query($command, $login_db);
         
         $_SESSION['login_name'] = addslashes($repository_name);
         $_SESSION['repository_id'] = $repository_id;
         $_SESSION['login_id'] = $login_id;
         $_SESSION['login_admin'] = true;
         
         header("Location: admin.php");
         exit();
         
      
      }
      else {
         $command = "ROLLBACK";
         $result = mysql_query($command, $login_db);
         
         $command = "SET AUTOCOMMIT=1";
         $result = mysql_query($command, $login_db);
      }      
   }
}

include('../battery_include/login_header.inc');
if ($error_message) {
   echo "<span style='color:red;'>".$error_message."</span>";
}
?>
<h2>Use QR codes to track and eliminate bad batteries once and for all.</h2>
      <form action='signup.php' method='post'>
      <p>
      <label>Repository Name: </label>
      <input type='text' name='repository_name' value='<? echo $repository_name; ?>' size='25' max='25' />
      <label>Time Zone: </label>
      <select name="tz">
	<option value="Pacific/Honolulu">(GMT-10:00) Hawaii</option>
	<option value="America/Anchorage">(GMT-09:00) Alaska</option>
	<option selected="yes" value="America/Los_Angeles">(GMT-08:00) Pacific Time (US &amp; Canada)</option>
	<option value="America/Phoenix">(GMT-07:00) Arizona</option>
	<option value="America/Denver">(GMT-07:00) Mountain Time (US &amp; Canada)</option>
	<option value="America/Chicago">(GMT-06:00) Central Time (US &amp; Canada)</option>
	<option value="America/New_York">(GMT-05:00) Eastern Time (US &amp; Canada)</option>
	<option value="America/Indiana/Indianapolis">(GMT-05:00) Indiana (East)</option>
	</select>
      <label>Create password for admin account: </label>
      <input type='password' name='user_password1' value='<? echo $user_password1; ?>' size='12' max='12' />
      <label>Re-enter Password: </label>
      <input type='password' name='user_password2' value='<? echo $user_password2; ?>' size='12' max='12' />      
      <br /><br />
      <input type='submit' class='button' value='Create Battery Repository' />
      </p>
      </form>
   <p>Already set up? Login <a href='login.php'>here</a>.</p>
<?
include('../battery_include/battery_footer.inc');

?>