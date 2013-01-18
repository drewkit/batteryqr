<?php
require('../battery_static/database.inc');
require('../battery_include/battery_utilities.inc');
require('../battery_include/members_only.inc');
$member_db = member_db_connect();

$repository_id = $_SESSION['repository_id'];
$qr = $_GET['qr'];
$item_id = $_GET['item_id'];
$login_id = $_SESSION['login_id'];
$login_name = $_SESSION['login_name'];
$reroute = $_GET['reroute'];

#check inputs
if (!(is_numeric($item_id))) {
   header("Location: index.php");
   exit();
}

$command = "SELECT item_id, item_name from battery_item where item_id=".$item_id." AND date_deleted<=0 AND repository_id=".$repository_id;
$result = mysql_query($command, $member_db);

if (mysql_num_rows($result) != 1) {
   $command = "SELECT item_id from battery_item where item_id=".$item_id." AND repository_id=".$repository_id;
   $result = mysql_query($command, $member_db);
   if (mysql_num_rows($result) == 1) {
      $error_message = "<h3>This battery was previously marked as removed</h3>
      <h3>Click <a href='restore_battery.php?item_id=".$item_id."'>here</a> to restore the battery to your repository</h3>";
   }
   else {
      $error_message = "This battery is not associated with the current repository";
   }
}
else {
   $data = mysql_fetch_object($result);
   $item_name = $data->item_name;
   $command = "SELECT status_flagged, login_id from battery_status where date_deleted<=0 AND item_id=".$item_id." ORDER BY status_flagged DESC LIMIT 0,1";
   $result = mysql_query($command, $member_db);
   
   if (mysql_num_rows($result) == 1) {
      $data = mysql_fetch_object($result);
      $status_flagged = $data->status_flagged;
      $three_days_ago = time() - (60 * 60 * 24 * 3); //current time - 3 days
      if ($status_flagged > $three_days_ago) {
         $status_login_id = $data->login_id;
         $command = "SELECT login_name from battery_login where login_id=".$status_login_id;
         $result = mysql_query($command, $member_db);
         $data = mysql_fetch_object($result);
         $status_name = $data->login_name;
         $time_left = ($status_flagged + (60 * 60 * 24 * 3)) - time();
         
         $error_message = "<h3>Batteries can only be flagged once every three days</h3>
         <h3>Battery name: '".$item_name."'</h3>
         <h3>Last flagged on ".date('n-j-y g:i A T', $status_flagged)." by '".$status_name."'</h3>
         <h3>".time_left($time_left)." remaining</h3>";
      }
   }
   
   if (!$error_message) {
      $command = "INSERT INTO battery_status VALUES('',".$login_id.",'".$item_id."', UNIX_TIMESTAMP(), 0)";
      $result = mysql_query($command, $member_db);
      
      $status_id = mysql_insert_id($member_db);
      $now = date('n-j-y g:i A T');
      if ($qr) {
         $message = "<h3>Battery '".$item_name."' Successfully Flagged</h3>
         <h3>at ".$now."</h3>
         <h3>by user '".$login_name."'</h3>
         <h3>click <a href='delete_history.php?qr=1&status_id=".$status_id."'>here</a> to undo battery flag</h3>
		 <h3>This battery has been flagged ".get_total_flags($item_id, $repository_id, $member_db)." times in the last 90 days.</h3>";
      }
      else if ($reroute) {
         header("Location: ".$reroute."?item_id=".$item_id."&confirmation=flagged");
         exit();
      }
      else {
         header("Location: index.php?confirmation=flagged");
         exit();
      }
   }
}
include('../battery_include/battery_header.inc');
echo $error_message;
echo "<br />";
echo $message;

#establish that item_id exists and is not deleted
#establish that item_id belongs in the same repository as the user
// $error_message = this item_id is not associated with your battery repository

#establish that item has not been flagged in the last 3 days
// $error_message = this battery has already been flagged in the last 3 days


include('../battery_include/battery_footer.inc');

?>