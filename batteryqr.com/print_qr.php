<?php
require('../battery_static/database.inc');
require('../battery_include/battery_utilities.inc');
require('../battery_include/members_only.inc');

include('../battery_include/battery_header.inc');
$member_db = member_db_connect();

$command = "SELECT bi.item_id,bi.item_name FROM battery_item AS bi WHERE bi.date_deleted<=0 AND bi.repository_id=".$repository_id." ORDER BY bi.item_id DESC";
$result = mysql_query($command, $member_db);
if (mysql_num_rows($result)<=0) {
   echo "<h2>There are no batteries currently in this repository. Click the link above to begin adding batteries to your repository.</h2>";
}

?>
<form name='theForm' action='label.php' method='post'>
<div id="repository_box">
<h2>Instructions:</h2>
<p>Select the battery qr codes that you wish to print out.</p>
<br />
<p>PDF will be generated and formatted for avery labels '6576'.  YOU MUST USE A LASER PRINTER.  Normal inkjet printers will smear ink on the labels. In print settings, ensure that scaling is off or set to 100%.</p>
<br /><p>Select <a href="javascript:selectToggle(true, 'theForm');">All</a> | <a href="javascript:selectToggle(false, 'theForm');">None</a></p>
<table id="print_qr">
<tr>
<th class="first"></th><th>ID/Name</th><th></th><th>ID/Name</th><th></th><th>ID/Name</th>
</tr>
<tr class="row-b">
<?

$i=0;
$row=0;
while ($array = mysql_fetch_assoc($result)) {
   
   if ($i==3) {
      $i=0;

      if ($row%2==0) {
         echo "<tr class='row-a'>";
      }
      else {
         echo "<tr class='row-b'>";
      }
      $row++;
   }
   ?>
   <td><input type='checkbox' name='answers[]' value='<? echo $array['item_id']; ?>' /></td><td><? echo $array['item_name']; ?></td>
   <?
   $i++;
   if ($i==3) {
      echo "</tr>";
   }
}
if ($i==1) {
?>
<td></td><td></td><td></td><td></td>
</tr>
<?
}
else if ($i==2) {
?>
<td></td><td></td>
</tr>
<?	
}
echo "</table>";

?>
<input type="submit" class="button" value="Get PDF" />
</div>
</form>
<?
include('../battery_include/battery_footer.inc');
?>
