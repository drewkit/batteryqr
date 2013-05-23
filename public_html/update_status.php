<?php
require('../battery_static/database.inc');
require('../battery_include/battery_utilities.inc');
require('../battery_include/members_only.inc');

$qr = $_GET['qr'];
$item_id = $_GET['item_id'];
$reroute = $_GET['reroute'];

if ($item_id && $reroute && !$qr) {
   header("Location: flag_battery.php?item_id=".$item_id."&reroute=".$reroute);
   exit();
}
else if (!$qr && $item_id) {
   header("Location: flag_battery.php?item_id=".$item_id);
   exit();
}
else if ($qr && $item_id) {
   header("Location: flag_battery.php?item_id=".$item_id."&qr=".$qr);
   exit();
}
else {
   header("Location: index.php");
   exit();
}
?>