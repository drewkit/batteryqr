<?php
session_start();
require('../battery_static/database.inc');
include('../battery_include/battery_utilities.inc');
include('../battery_include/members_only.inc');

$member_db = member_db_connect();
$repository_id = $_SESSION['repository_id'];
$login_id = $_SESSION['login_id'];
$login_name = $_SESSION['login_name'];
$mode = $_GET['mode'];

include('../battery_include/battery_header.inc');

echo "<span style='color:orange;'>";
switch($_GET['confirmation']) {
   case "flagged":
      echo "battery has been flagged";
   break;
   case "flag_removed":
      echo "flag has been removed";
   break;
   case "battery_added":
      echo "battery has been added to the respository";
   break;
   case "battery_edited":
      echo "battery was successfully edited";
   break;
   case "battery_removed":
      echo "battery was removed from repository";
   break;
   case "battery_restored":
      echo "battery was successfully restored";
   break;
}
echo "</span>";


$three_months = (60 * 60 * 24) * 90;
$three_months_ago = time() - $three_months;
$per_page = 12;

$command = "SELECT count(item_id) as total_items from battery_item where date_deleted<=0 AND repository_id=".$repository_id;
$result = mysql_query($command, $member_db);
$data = mysql_fetch_object($result);
$total_items = $data->total_items;
if (mysql_num_rows($result) <=0) {
   $total_items = 0;
}
$total_pages = ceil($total_items/$per_page);
$item_start_point = ($page - 1) * $per_page;

?>
<div class="mobile_site">
	<p><strong><? echo $login_name; ?></strong> | <a <? echo menu_nav('edit_account.php'); ?> href="edit_account.php">account settings</a> | <a href="logout.php">log out</a></p>
</div>
<?
echo "<div id='repository_box'>";

if ($mode == 'search') {

$search_query = $_GET['search_query'];

$member_db = member_db_connect();
$command = "SELECT bi.item_id,bi.item_name,count(bs.status_flagged) AS flag_count FROM battery_item AS bi LEFT OUTER JOIN battery_status AS bs ON 
bs.item_id=bi.item_id AND bs.status_flagged > ".$three_months_ago." AND bs.date_deleted<=0 WHERE bi.date_deleted<=0 AND bi.repository_id=".$repository_id." AND bi.item_name LIKE ('%".addslashes($search_query)."%') GROUP BY bi.item_id";
$result = mysql_query($command, $member_db);

echo "<h2>Search Results for \"".$search_query."\"</h2>
<table>
<tr><th class='first'>ID/Name</th><th># of flags</th><th>QR Code</th><th>&nbsp;</th></tr>";
$i=0;
while ($array = mysql_fetch_assoc($result)) {
   
   if ($i%2 ==0) {
      echo "<tr class='row-a'>";
   }
   else {
      echo "<tr class='row-b'>";
   }
   ?>
   <td class='first'><a href="battery_history.php?item_id=<? echo $array['item_id']; ?>"><? echo $array['item_name']; ?></a></td>
   <td><? echo $array['flag_count']; ?></td>
   <td><a href="qr_code.php?item_id=<? echo $array['item_id']; ?>">View QR</a></td>
   <td><a href="delete_battery.php?item_id=<? echo $array['item_id']; ?>">Remove</a></td>
   <?
   echo "</tr>";
   $i++;
}
echo "</table>";


}
else {

$command = "SELECT bi.item_id,bi.item_name,count(bs.status_flagged) AS flag_count FROM battery_item AS bi LEFT OUTER JOIN battery_status AS bs ON 
bs.item_id=bi.item_id AND bs.status_flagged > ".$three_months_ago." AND bs.date_deleted<=0 WHERE bi.date_deleted<=0 AND bi.repository_id=".$repository_id." GROUP BY bi.item_id";
if ($mode == 'recent') {
   $command .= " ORDER BY bi.item_id DESC LIMIT ".$item_start_point.",".$per_page;
}
else if ($mode == 'alphabetical') {
   $command .= " ORDER BY CAST(bi.item_name as SIGNED) LIMIT ".$item_start_point.",".$per_page;
}
else {
   $command .= " ORDER BY flag_count DESC, CAST(bi.item_name as SIGNED) LIMIT ".$item_start_point.",".$per_page;
}
$result = mysql_query($command, $member_db);

if (mysql_num_rows($result)<=0) {
   echo "<h2>There are no batteries currently in this repository. Click the link above to begin adding batteries to your repository.</h2>";
}
else {

echo "<h2>\"".$repository_name."\" (".$total_items." batteries)</h2>";
echo "<p>Order By: <a ".mode_nav($mode, 'flag_count')." href='index.php'>Flagged</a> | <a ".mode_nav($mode, 'recent')." href='index.php?mode=recent'>Recent</a> | <a ".mode_nav($mode, 'alphabetical')." href='index.php?mode=alphabetical'>Name</a></p>";
echo "<p>".index_page_nav_setup($mode, $total_pages, $page)."</p>";
echo "<table>
<tr><th class='first'>ID/Name</th><th>flags</th><th>QR Code</th><th>&nbsp;</th></tr>";
$i=0;
while ($array = mysql_fetch_assoc($result)) {
   
   if ($i%2 ==0) {
      echo "<tr class='row-a'>";
   }
   else {
      echo "<tr class='row-b'>";
   }
   ?>
   <td class='first'><a href="battery_history.php?item_id=<? echo $array['item_id']; ?>"><? echo $array['item_name']; ?></a></td>
   <td><? echo $array['flag_count']; ?></td>
   <td><a href="qr_code.php?item_id=<? echo $array['item_id']; ?>">View QR</a></td>
   <td><a href="delete_battery.php?item_id=<? echo $array['item_id']; ?>">Remove</a></td>
   <?
   echo "</tr>";
   $i++;
}
echo "</table>";
}
}
echo "</div>";
include('../battery_include/battery_footer.inc');
?>