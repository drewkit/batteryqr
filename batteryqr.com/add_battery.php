<?php
require('../battery_static/database.inc');
require('../battery_include/battery_utilities.inc');
require('../battery_include/members_only.inc');
$member_db = member_db_connect();

$action = '';
$action = $_GET['action'];

$command = "SELECT count(item_id) as total_items from battery_item where date_deleted<=0 AND repository_id=".$repository_id;
$result = mysql_query($command, $member_db);
$data = mysql_fetch_object($result);
$total_items = $data->total_items;
if (mysql_num_rows($result) <=0) {
   $total_items = 0;
}

switch ($action) {
   case "add_one":

if (count($_POST) > 0) {
   $repository_id = $_SESSION['repository_id'];
   $item_name = $_POST['item_name'];
   $good_name = "^[a-zA-Z0-9_]{1,25}$";

   if (!($item_name)) {
      if (!$error_message) {
	      $error_message = "Please enter a name/id into the field";
      }
   }
   else if (!(valid_input($item_name, $good_name))) {
      $error_message = "Please make sure that you are only using alphanumeric characters (underscores permitted).";
   }
   else {
   
      $command = "SELECT item_name from battery_item where item_name='".addslashes($item_name)."' AND repository_id=".$repository_id;
      $result = mysql_query($command, $member_db);
      
      if (mysql_num_rows($result) == 1) {
         $error_message = "There is already a battery on record in your repository with this ID/Name.  
         This includes batteries previously marked as removed.  Please choose another name.";
      }
      else {
   
         $command = "INSERT into battery_item VALUES('','".addslashes($item_name)."',".$repository_id.",0)";
         $result = mysql_query($command, $member_db);
      
         header("Location: index.php?confirmation=battery_added&mode=recent");
         exit();
      }
   }
}   
      
   break;
   case "add_multiple":
   
if (count($_POST) > 0) {
   $total = $_POST['total'];
   if (is_numeric($total) && $total > 0 && $total <= 20) {
   		$success = true;
		$command = "SET AUTOCOMMIT=0";
		$result = mysql_query($command, $member_db);
		$command = "BEGIN";
		$result = mysql_query($command, $member_db);		
		for($i=0; $i < $total; $i++) {
			$item_name = get_next_item_name($repository_id, $member_db);
			
			$command = "SELECT item_name from battery_item where item_name='".addslashes($item_name)."' AND repository_id=".$repository_id;
			$result = mysql_query($command, $member_db);
			      
			if (mysql_num_rows($result) == 1) {
			      $success = false;
			}
			
			$command = "INSERT into battery_item VALUES('','".addslashes($item_name)."',".$repository_id.",0)";
			$result = mysql_query($command, $member_db);
			if ($result == false) {
				$success = false;
			}
		}
		if ($success) {
			$command = "COMMIT";
			$result = mysql_query($command, $member_db);
			
			$command = "SET AUTOCOMMIT=1";
			$result = mysql_query($command, $member_db);
			
			header("Location: index.php?confirmation=battery_added&mode=recent");
			exit();
		}
		else {
			$command = "ROLLBACK";
			$result = mysql_query($command, $member_db);
			
			$command = "SET AUTOCOMMIT=1";
			$result = mysql_query($command, $member_db);
			
			$error_message = "There was an error with processing your batch request";
		}
   }
}   
   break;
}
include('../battery_include/battery_header.inc');
echo "<div id='repository_box' class='form'>";
if ($error_message) {
   echo "<span style='color:red;'>".$error_message."</span>";
}
echo "<h2>".$total_items." total batteries in repository \"".$repository_name."\"</h2>";
echo "<hr />";
echo "<h2>Setup a single battery with custom name</h2>";
?>
<form action="add_battery.php?action=add_one" method="post">
<p>
<label>Enter ID/Name</label>
<input type="text" name="item_name" size="25" value="<? echo $item_name; ?>" />
<br />
<br />
<input class="button" type="submit" value="Add Battery" />
</p>
</form>
<hr />
<br />
<h2>I don't care about custom name/id, just set up a bunch.</h2>
<form action="add_battery.php?action=add_multiple" method="post">
<p>
<label>Select Number of batteries to add</label>
<select name="total">
<?
for ($i=1;$i<=20;$i++) {
	echo "<option>$i</option>\n";
}
?>
</select>
<br /><br />
<input class="button" type="submit" value="Add Batteries" />
</p>
</form>
<?
echo "</div>";
include('../battery_include/battery_footer.inc');
?>