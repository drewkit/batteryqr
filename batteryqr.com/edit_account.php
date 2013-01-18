<?php
require('../battery_static/database.inc');
require('../battery_include/battery_utilities.inc');
require('../battery_include/members_only.inc');
$login_id = $_SESSION['login_id'];
$login_name = $_SESSION['login_name'];
$repository_id = $_SESSION['repository_id'];
$login_db = login_db_connect();
if (count($_POST) > 0) {
   $old_password = $_POST['old_password'];
   $new_password_1 = $_POST['new_password_1'];
   $new_password_2 = $_POST['new_password_2'];
   $good_password = "^[a-zA-Z0-9_]{3,12}$";
   
   if (!($new_password_1 && $new_password_2 && $old_password)) {
      $error_message = "Please enter all fields";
   }
   else if ($new_password_1 != $new_password_2) {
      $error_message = "You have not entered your new password consistently";
   }
   else if (!(valid_input($old_password, $good_password) && valid_input($new_password_1, $good_password) && valid_input($new_password_2, $good_password))) {
      $error_message = "Please make sure that the passwords entered are at least 3 characters in length and alphanumeric";
   }
   else {
      $command = "SELECT login_id from battery_login where login_password=password('".addslashes($old_password)."') AND date_deleted <=0 
      AND repository_id = ".$repository_id." AND login_id=".$login_id." AND login_name='".$login_name."'";
      $result = mysql_query($command, $login_db);
      if (mysql_num_rows($result) == 1) {
         $command = "UPDATE battery_login SET login_password=password('".addslashes($new_password_1)."') WHERE login_id=".$login_id." AND date_deleted <=0";
         $result = mysql_query($command, $login_db);
         
         header("Location: edit_account.php?confirmation=1");
         exit();
      }
      else {
         $error_message = "You have entered an incorrect password";
      }
   }
}

include('../battery_include/battery_header.inc');
echo "<span style='color:orange;'>";
switch($_GET['confirmation']) {
   case "1":
      echo "Password successfully updated";
   break;
}
echo "</span>";
if ($error_message) {
   echo "<span style='color:red;'>".$error_message."</span>";
}
?>
<form action="edit_account.php" method="post">
<p>
<label>Old Password</label>
<input name="old_password" type="password" size="25" />
<label>New Password</label>
<input name="new_password_1" type="password" size="25" />
<label>Re-Enter Password</label>
<input name="new_password_2" type="password" size="25" />
<br /><br />
<input class="button" type="submit" value="Change Password" />
</p>
</form>
<?
include('../battery_include/battery_footer.inc');
?>