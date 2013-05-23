<?php
require('../battery_static/database.inc');
require('../battery_include/battery_utilities.inc');
require('../battery_include/members_only.inc');

$member_db = member_db_connect();
$login_db = login_db_connect();
$repository_id = $_SESSION['repository_id'];
$login_id = $_SESSION['login_id'];
$item_id = $_GET['item_id'];

if (count($_POST) > 0) {
   $item_id = $_POST['item_id'];
   if (!(is_numeric($item_id))) {
      header("Location: index.php");
      exit();
   }
   $password = $_POST['password'];
   $good_password = "^[a-zA-z0-9_]{3,12}$";
   
   if (!(valid_input($password, $good_password))) {
      $error_message = "incorrect password format";
   }
   $command = "SELECT login_id from battery_login where date_deleted<=0 AND login_id=".$login_id." AND login_password=password('".addslashes($password)."')";
   $result = mysql_query($command, $login_db);
   if (mysql_num_rows($result) <=0) {
      $error_message = "incorrect password";
   }
   
   $command = "SELECT item_id from battery_item where item_id=".$item_id." AND date_deleted<=0 AND repository_id=".$repository_id;
   $result = mysql_query($command, $member_db);
   
   if (mysql_num_rows($result) != 1) {
      $error_message = "This battery has either already been removed, or does not exist in this repository";
   }
   else if ((mysql_num_rows($result) == 1) && (!($error_message))) {
      $success = true;
      
      $command = "SET AUTOCOMMIT=0";
      $result = mysql_query($command, $member_db);
      $command = "BEGIN";
      $result = mysql_query($command, $member_db);
      
      $command = "UPDATE battery_status SET date_deleted = UNIX_TIMESTAMP() where item_id=".$item_id." AND date_deleted<=0";
      $result = mysql_query($command, $member_db);
      if ($result == false) {
         $success = false;
      }
      else {
         $command = "UPDATE battery_item SET date_deleted = UNIX_TIMESTAMP() where item_id=".$item_id." AND date_deleted<=0";
         $result = mysql_query($command, $member_db);
         if ($result == false) {
            $success = false;
         }
      }
      if ($success) {
         $command = "COMMIT";
         $result = mysql_query($command, $member_db);
      }
      else {
         $command = "ROLLBACK";
         $result = mysql_query($command, $member_db);
         $error_message = "There was a database error with this request. Please try again.";
      }
      $command = "SET AUTOCOMMIT=1";
      $result = mysql_query($command);
      if ($success) {
         header("Location: index.php?confirmation=battery_removed");
         exit();
      }
   }
}

include('../battery_include/battery_header.inc');

if ($error_message) {
   echo "<span style='color:red;'>".$error_message."</span><br>";
}

if (!(is_numeric($item_id))) {
   header("Location: index.php");
   exit();
}

echo "<h2>Are you sure you want to mark battery '".get_item_name($item_id, $member_db)."' as removed?</h2>";
?>
<form action='delete_battery.php' method='post' />
<p>
<label>Enter Password for Confirmation: </label>
<input type='password' name='password' />
<input type='hidden' name='item_id' value='<? echo $item_id; ?>' />
<input type='submit' class='button' value='Remove Battery' />
</p>
</form>

<?

include('../battery_include/battery_footer.inc');

?>