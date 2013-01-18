<?php
require('../battery_static/database.inc');
require('../battery_include/battery_utilities.inc');
require('../battery_include/members_only.inc');
$member_db = member_db_connect();
include('../battery_include/battery_header.inc');
$repository_id = $_SESSION['repository_id'];
$login_id = $_SESSION['login_id'];
$confirmation = $_GET['confirmation'];

$item_id = $_GET['item_id'];
if (!(is_numeric($item_id))) {
   header("Location: index.php");
   exit();
}
else {
   $command = "SELECT item_name from battery_item where item_id=".$item_id." AND repository_id=".$repository_id." AND date_deleted<=0";
   $result = mysql_query($command, $member_db);
   if (mysql_num_rows($result) != 1) {
      $error_message = "There is no battery in this repository with that item_id";
   }
   else {
      $data = mysql_fetch_object($result);
      $item_name = $data->item_name;
      $command = "SELECT status_flagged,status_id,login_id from battery_status where item_id=".$item_id." AND date_deleted<=0 ORDER BY status_flagged DESC";
      $result = mysql_query($command, $member_db);
      
      echo "<div id='repository_box'>
      <h2>History for battery \" ".$item_name." \"</h2>
      <h4><a href='update_status.php?item_id=".$item_id."&reroute=battery_history.php'>Click here to manually flag this battery</a></h4>";
      echo "<span style='color:orange;'>";
      switch($confirmation) {
         case "flag_removed":
            echo "flag successfully removed<br>";
         break;
         case "flagged":
            echo "battery successfully flagged<br>";
         break;
      }
      echo "</span>";
      
      if (mysql_num_rows($result) <=0 ) {
         $error_message = "<h3>There are no status updates for battery '".$item_name."'</h3>";
      }
      else {
         $flag = array();
                 
      ?>
      <table>
      <tr>
      	<th class="first">flagged on</th><th>flagged by</th><th>&nbsp;</th>
      </tr>
      <?
         $i=0;
         while ($flag = mysql_fetch_assoc($result)) {

            $command = "SELECT login_name from battery_login where login_id=".$flag['login_id'];
            $result2 = mysql_query($command, $member_db);
            $data=mysql_fetch_object($result2);
            $name = $data->login_name;
            
            if ($i%2 ==0) {
               echo "<tr class='row-a'>";
            }
            else {
               echo "<tr class='row-b'>";
            }
            echo "<td class='first'>".date('n-j-y g:i A T',$flag['status_flagged'])."</td><td><a href='user_history.php?user_id=".$flag['login_id']."'>".$name."</a></td>";
            if ($flag['login_id'] == $login_id) {
               echo "<td><a href='delete_history.php?status_id=".$flag['status_id']."&reroute=history'>Unflag</a></td>";
            }
            else {
               echo "<td>&nbsp;</td>";
            }
            echo "</tr>";
         }
      ?>
      </table>
      <?
      }
   }
}
echo $error_message;
echo "</div>";

include('../battery_include/battery_footer.inc');
?>