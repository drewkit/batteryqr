<?php
require('../battery_static/database.inc');
require('../battery_include/battery_utilities.inc');
require('../battery_include/members_only.inc');
$member_db = member_db_connect();
$login_db = login_db_connect();
include('../battery_include/battery_header.inc');

$user_id = $_GET['user_id'];
$repository_id = $_SESSION['repository_id'];
$three_months = (60 * 60 * 24) * 90;
$three_months_ago = time() - $three_months;

echo "<div id='repository_box'>";
if (is_numeric($user_id)) {
   $command = "SELECT bl.login_name FROM battery_login as bl where login_id=".$user_id." AND repository_id=".$repository_id;
   $result = mysql_query($command, $login_db);
   
   if (mysql_num_rows($result) == 1) {
      $command = "SELECT bi.item_name,bi.item_id,bs.status_id,bs.status_flagged from battery_item as bi, battery_status as bs where bi.item_id=bs.item_id 
      AND bs.date_deleted<=0 AND bs.status_flagged > ".$three_months_ago." AND bs.login_id=".$user_id." ORDER BY bs.status_flagged DESC LIMIT 0,30";
      $result = mysql_query($command, $member_db);
      
      if (mysql_num_rows($result) <=0) {
         echo "<h2>This user has not flagged any batteries in the last 90 days.</h2>";
      }
      else {
         $login_name = get_login_name($user_id, $member_db);
         
         echo "
         <h2>Recent Activity for ".$login_name."</h2>
         <table>
         <tr><th class='first'>ID/Name</th><th>Flagged On</th><th>&nbsp;</th></tr>";
         $i=0;
         while ($array = mysql_fetch_assoc($result)) {
            if ($i%2 ==0) {
               echo "<tr class='row-a'>";
            }
            else {
               echo "<tr class='row-b'>";
            }
   ?>
   <td class='first'><a href='battery_history.php?item_id=<? echo $array['item_id']; ?>'><? echo $array['item_name']; ?></a></td>
   <td><? echo date('n-j-y g:i A T',$array['status_flagged']); ?></td>
   <?
   if ($user_id == $_SESSION['login_id']) {
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
   }
}
echo "</div>";


include('../battery_include/battery_footer.inc');

?>