<?php
require('../battery_static/database.inc');
require('../battery_include/battery_utilities.inc');
require('../battery_include/members_only.inc');

include('../battery_include/battery_header.inc');
$member_db = member_db_connect();

if (count($_POST)) {

}


$item_id = $_GET['item_id'];
if (is_numeric($item_id)) {
   $command = "SELECT item_name from battery_item where item_id=".$item_id." AND date_deleted<=0";
   $result = mysql_query($command, $member_db);
   $data = mysql_fetch_object($result);
   $item_name = $data->item_name;
   
   if (mysql_num_rows($result) == 1) {
      echo "<div id='qr_code'>".$item_name."<br>";
      $url = "http://".$_SERVER['SERVER_NAME']."/update_status.php?qr=1&item_id=".$item_id;
      generate_QR($url);
      echo "</div>";
   }
}
else {
?>
   <p>Form for selecting battery qr print codes goes here</p>
<?
}


include('../battery_include/battery_footer.inc');

?>