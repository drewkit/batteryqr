<?php
require('../battery_static/database.inc');
require('../battery_include/battery_utilities.inc');
require('../battery_include/members_only.inc');
$member_db = member_db_connect();
$login_id = $_SESSION['login_id'];

$qr = $_GET['qr'];
$reroute = $_GET['reroute'];
$status_id = $_GET['status_id'];
if (!(is_numeric($status_id))) {
   header("Location: index.php");
   exit();
}
else {
   $command = "SELECT item_id from battery_status where status_id=".$status_id." AND date_deleted<=0 AND login_id=".$login_id;
   $result = mysql_query($command, $member_db);
   
   if (mysql_num_rows($result) == 1) {
      $data = mysql_fetch_object($result);
      $item_id = $data->item_id;
      
      $command = "UPDATE battery_status set date_deleted=UNIX_TIMESTAMP() where status_id=".$status_id;
      $result = mysql_query($command, $member_db);
      $item_name = get_item_name($item_id, $member_db);
      
      if ($qr) {
         echo "<h3>Flag removed for battery '".$item_name."'</h3>";
         exit();
      }
      switch($reroute) {
         case "history":
            header("Location: battery_history.php?item_id=".$item_id."&confirmation=flag_removed");
            exit();
         break;
         default:
            header("Location: index.php?confirmation=flag_removed");
            exit();
      } 
   }
   else {
      $error_message = "Error processing request";
      echo $error_message;
      exit();
   }
   
#establish that status_id row matches login_id, not already deleted
#grab item_id from status_id
#send back to reroute with confirmation and item_id
}

?>