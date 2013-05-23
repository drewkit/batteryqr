<?php
// decided against the ability to change a battery name once it has been created, since printed qr labels would then require a 
// change of the qr label's printed name, which gets confusing
/*
require('../battery_static/database.inc');
require('../battery_include/members_only.inc');
require('../battery_include/battery_utilities.inc');
$member_db = member_db_connect();


if (count($_POST) > 0) {

   $repository_id = $_SESSION['repository_id'];
   $item_id = $_POST['item_id'];
   $item_name = $_POST['item_name'];
   $good_name = "^[a-zA-Z0-9_]{1,25}$";   

   if (!($item_name && $item_id)) {
      $error_message = "Please enter a name/id into the field";
   }
   else if (!(valid_input($item_name, $good_name))) {
      $error_message = "Please make sure that you are only using alphanumeric characters";
   }
   else if (!(is_numeric($item_id))) {
      $error_message = "There was an issue with the item_id";
   }
   else {
      $command = "SELECT * FROM battery_item where item_id=".$item_id." AND date_deleted <=0 AND repository_id=".$repository_id;
      $result = mysql_query($command, $member_db);
      
      //if using this page, requires a check on active battery names in repository, to prevent duplicate names
      
      if (mysql_num_rows($result) == 1) {
         $command = "UPDATE battery_item SET item_name='".addslashes($item_name)."' where item_id=".$item_id." AND date_deleted <=0 AND repository_id=".$repository_id;
         $result = mysql_query($command, $member_db);
      
         if (mysql_error()) {
            echo mysql_error();
            exit();
         }
         header("Location: index.php?confirmation=battery_edited");
         exit();
      }
   }
}

if ($_GET['item_id']) {
   $repository_id = $_SESSION['repository_id'];
   $item_id = $_GET['item_id'];   

   if (!($item_id)) {
      $error_message = "There is no item_id to be edited";
      echo $error_message;
      exit();
   }
   else if (!(is_numeric($item_id))) {
      $error_message = "There was an issue with the item_id";
         echo $error_message;
         exit();
   }
   else {
      $command = "SELECT item_name FROM battery_item where item_id=".$item_id." AND date_deleted <=0 AND repository_id=".$repository_id;
      $result = mysql_query($command, $member_db);
            if (mysql_error()) {
               echo mysql_error();
               exit();
            }
      if (mysql_num_rows($result) == 1) {
         $data = mysql_fetch_object($result);
         $item_name = $data->item_name;
      }
      else {
         $error_message = "We could not find this battery item_id in your repository";
         echo $error_message;
         exit();
      }
   }
}
else {
   echo "No battery item_id selected";
   exit();
}

   require_once "HTML/Form.php";
   include('../battery_include/battery_header.inc');

   $form = new HTML_FORM('edit_battery.php', 'post');

   $form->addText("item_name", "ID/Name: ", $item_name);
   $form->addHidden("item_id", $item_id);
   $form->addSubmit("submit", "Submit");
   if ($error_message) {
      echo "<span style='color:red;'>".$error_message."</span>";
   }
   echo "<h3>Edit this battery</h3>";
   $form->display();


   include('../battery_include/battery_footer.inc');
*/
?>