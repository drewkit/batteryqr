<?php
require('../battery_static/database.inc');
require('../battery_include/battery_utilities.inc');
require('../battery_include/members_only.inc');

include('../battery_include/battery_header.inc');
$member_db = member_db_connect();
$three_months = (60 * 60 * 24) * 90;
$three_months_ago = time() - $three_months;

echo "<div id='repository_box'>
<h2>Recent Activity for Repository '".$repository_name."'</h2>";
$command = "SELECT bi.item_name,bi.item_id,bs.status_id,bs.status_flagged,bs.login_id from battery_item as bi, battery_status as bs where bi.item_id=bs.item_id 
AND bs.date_deleted<=0 AND bi.repository_id=".$repository_id." AND bs.status_flagged > ".$three_months_ago." ORDER BY bs.status_flagged DESC LIMIT 0,12";
$result = mysql_query($command, $member_db);
if (mysql_num_rows($result) <=0) {
   echo "<h2>No batteries have been flagged in the last 90 days.</h2>";
}
else {
echo "<table>
<tr><th class='first'>ID/Name</th><th>Flagged By</th><th>Flagged On</th><th>&nbsp;</th></tr>";
while ($array = mysql_fetch_assoc($result)) {
   if ($i%2 ==0) {
      echo "<tr class='row-a'>";
   }
   else {
      echo "<tr class='row-b'>";
   }
   ?>
   <td class='first'><a href='battery_history.php?item_id=<? echo $array['item_id']; ?>'><? echo $array['item_name']; ?></a></td>
   <td><a href='user_history.php?user_id=<? echo $array['login_id']; ?>'><? echo get_login_name($array['login_id'], $member_db); ?></a></td>
   <td><? echo date('n-j-y',$array['status_flagged']); ?></td>
   <?
   if ($array['login_id'] == $login_id) {
   ?>
   <td><a href="delete_history.php?status_id=<? echo $array['status_id']; ?>">Unflag</a></td>
   <?
   }
   else {
      ?>
      <td>&nbsp;</td>
      <?
   }
   echo "</tr>";
   $i++;
}
echo "</table>";
}
echo "</div>";


include('../battery_include/battery_footer.inc');
?>