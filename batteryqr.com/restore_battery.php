<?php
require('../battery_static/database.inc');
include('../battery_include/battery_utilities.inc');
include('../battery_include/members_only.inc');

$item_id = $_GET['item_id'];
if (!is_numeric($item_id)) {
   header("Location: index.php");
   exit();
}
$member_db = member_db_connect();
$command = "SELECT item_id FROM battery_item WHERE repository_id=".$repository_id." AND item_id=".$item_id." AND date_deleted > 0";
$result = mysql_query($command, $member_db);

if (mysql_num_rows($result) == 1) {
   $success = true;
   $command = "SET AUTOCOMMIT=0";
   $result = mysql_query($command, $member_db);
   
   $command = "BEGIN";
   $result = mysql_query($command, $member_db);
   
   $command = "SELECT date_deleted FROM battery_status where item_id=".$item_id." ORDER BY date_deleted DESC limit 0,1";
   $result = mysql_query($command, $member_db);
   
   $data = mysql_fetch_object($result);
   $date_deleted = $data->date_deleted;
   
   $command = "SELECT date_deleted FROM battery_status WHERE item_id=".$item_id." AND date_deleted=".$date_deleted;
   $result = mysql_query($command, $member_db);
   
   if (mysql_num_rows($result) >= 3) {
      $data = mysql_fetch_object($result);
      $date_deleted = $data->date_deleted;
      $command = "UPDATE battery_status SET date_deleted=0 WHERE item_id=".$item_id." AND date_deleted=".$date_deleted;
      $result = mysql_query($command, $member_db);
      if ($result == false) {
         $success = false;
      }
   }

   $command = "UPDATE battery_item SET date_deleted=0 where item_id=".$item_id;
   $result = mysql_query($command, $member_db);
   if ($result == false) {
      $success = false;
   }
   
   if ($success) {
      $command = "COMMIT";
      $result = mysql_query($command, $member_db);
      
      $command = "SET AUTOCOMMIT=1";
      $result = mysql_query($command, $member_db);
      
      header("Location: index.php?confirmation=battery_restored");
      exit();
   }
   else {
      $command = "ROLLBACK";
      $result = mysql_query($command, $member_db);
      
      $command = "SET AUTOCOMMIT=1";
      $result = mysql_query($command, $member_db);      
   }
}

   echo "There was an error processing this request";
   exit();

?>