<?php

function generate_QR($url,$widthHeight ='200',$EC_level='M',$margin='0') {
    $url = urlencode($url); 

    echo "<img src='http://chart.apis.google.com/chart?chs=".$widthHeight."x".$widthHeight."&cht=qr&chld=".$EC_level."|".$margin."&chl=".$url."' alt='QR code' height='".$widthHeight."' width='".$widthHeight."' />";
}

function valid_input($my_input, $good_input) {
   if (preg_match("/$good_input/i", $my_input)) {
      return true;
   }
   else {
      return false;
   }
}
function get_item_name($item_id, $db) {
   $command = "SELECT item_name from battery_item where item_id=".$item_id." AND date_deleted<=0";
   $result = mysql_query($command, $db);
   $data = mysql_fetch_object($result);
   $item_name = $data->item_name;
   
   return $item_name;
}
function get_repository_name($repository_id) {
   $member_db = member_db_connect();
   if (is_numeric($repository_id)) {
      $command = "SELECT repository_name FROM battery_repository WHERE repository_id=".$repository_id.";";
      $result = mysql_query($command, $member_db);
      $data = mysql_fetch_object($result);
      $repository_name = $data->repository_name;
      
      return $repository_name;  
   }
}
function get_login_name($login_id, $db) {
   if (is_numeric($login_id)) {
      $command = "SELECT login_name FROM battery_login where login_id=".$login_id;
      $result = mysql_query($command, $db);
      $data = mysql_fetch_object($result);
      $login_name = $data->login_name;
      
      return $login_name;
   }
}
function get_time_zone($repository_id) {
   $member_db = member_db_connect();
   $command = "SELECT repository_time_zone FROM battery_repository where repository_id=".$repository_id;
   $result = mysql_query($command, $member_db);
   $data = mysql_fetch_object($result);
   $repository_time_zone = $data->repository_time_zone;
   return $repository_time_zone;
}
function time_left($integer) // taken from forum at http://www.go4expert.com/forums/showthread.php?t=456
 { 
 
     $seconds=$integer; 
 
     if ($seconds/60 >=1) 
 
     { 
 
     $minutes=floor($seconds/60); 
 
     if ($minutes/60 >= 1) 
 
     { # Hours 
 
     $hours=floor($minutes/60); 
 
     if ($hours/24 >= 1) 
 
     { #days 
 
     $days=floor($hours/24); 
 
     if ($days/7 >=1) 
 
     { #weeks 
 
     $weeks=floor($days/7); 
 
     if ($weeks>=2) $return="$weeks Weeks"; 
 
     else $return="$weeks Week"; 
 
     } #end of weeks 
 
     $days=$days-(floor($days/7))*7; 
 
     if ($weeks>=1 && $days >=1) $return="$return, "; 
 
     if ($days >=2) $return="$return $days days";
 
     if ($days ==1) $return="$return $days day";
 
     } #end of days
 
     $hours=$hours-(floor($hours/24))*24; 
 
     if ($days>=1 && $hours >=1) $return="$return, "; 
 
     if ($hours >=2) $return="$return $hours hours";
 
     if ($hours ==1) $return="$return $hours hour";
 
     } #end of Hours
 
     $minutes=$minutes-(floor($minutes/60))*60; 
 
     if ($hours>=1 && $minutes >=1) $return="$return, "; 
 
     if ($minutes >=2) $return="$return $minutes minutes";
 
     if ($minutes ==1) $return="$return $minutes minute";
 
     } #end of minutes 
 
     $seconds=$integer-(floor($integer/60))*60; 
 
     if ($minutes>=1 && $seconds >=1) $return="$return, "; 
 
     if ($seconds >=2) $return="$return $seconds seconds";
 
     if ($seconds ==1) $return="$return $seconds second";
 
 
     return $return; 
 
 } 
function menu_nav($page = false, $login_id = false, $user_id = false, $action = false, $listed_action = false) {
   $current_page = basename($_SERVER['PHP_SELF']);
   
   if ($current_page == 'admin.php') {
      if ($action == $listed_action) {
         $print = "id='current'";
         return $print;
      }
      else if (!$action) {
         if ($listed_action == 'portal') {
            $print = "id='current'";
            return $print;
         }
      }
   }
   
   if ($current_page == $page && $login_id == $user_id && $user_id != false) {
      $print = "id='current'";
      return $print;
   }
   else if ($login_id != $user_id) {
      return;
   }
   
   if ($page == $current_page) {
      $print = "id='current'";
      return $print;
   }

}
function mode_nav($current_mode, $this_mode) {
   if ($this_mode == $current_mode) {
      $print = "id='current_mode'";
      return $print;
   }
   else if (!$current_mode && $this_mode == "flag_count") {
      $print = "id='current_mode'";
      return $print;
   }
}
function index_page_nav_setup($mode, $total_pages, $page) {
   if ($page > $total_pages || $page <= 0) {
      echo "There was a problem with the page get variable that was requested";
      exit();
   }
  
   if (($page-3) <= 1) {
      $start = 1;
      $beginning = false;
   }
   else {
      $start = $page - 3;
      $beginning = 1;
   }
   if (($start + 6) >= $total_pages) {
      $stop = $total_pages;
      $end = false;
   }
   else {
      $stop = $start + 6;
      $end = $total_pages;
   }
   $page_nav = page_nav($mode, $page, $total_pages, $start, $stop, $beginning, $end);
   return $page_nav;
}
function page_nav ($mode, $page, $total_pages, $start, $stop, $beginning = false, $end = false) {
   $page_nav = '';
   if ($total_pages == 1) {
      return $page_nav;
   }
   if ($page == 1) {
      $page_nav .= "&#60; Prev ";
   }
   else {
      $page_nav .= "<a href='index.php?mode=".$mode."&page=".($page - 1)."'>&#60; Prev</a> ";
   }
   
   if ($start != 1) {
      $page_nav .= "<a href='index.php?mode=".$mode."&page=1'>1</a> ... ";
   }
   
   for ($i=$start; $i<=$stop; $i++) {
      if ($i == $page) {
         $page_nav .= "<span class='current_page'>".$i."</span> | ";
      }
      else if ($i == $stop) {
         $page_nav .= "<a href='index.php?mode=".$mode."&page=".$i."'>".$i."</a>";
      }      
      else {
         $page_nav .= "<a href='index.php?mode=".$mode."&page=".$i."'>".$i."</a> | ";
      }
   } 
   
   if ($end) {
      $page_nav .= " ... <a href='index.php?mode=".$mode."&page=".$end."'>".$end."</a> |";
   }
   
   if ($total_pages == $page) {
      $page_nav .= "Next &#62;";
   }
   else {
      $page_nav .= " <a href='index.php?mode=".$mode."&page=".($page + 1)."'>Next &#62;</a>";
   }
   
   return $page_nav;
}

function get_next_item_name($repository_id, $db) {
   $command = "select * from battery_item WHERE item_name REGEXP '^[0-9]+$' AND repository_id=".$repository_id." ORDER BY ABS(item_name) DESC LIMIT 0,1";
   $result = mysql_query($command, $db);
   if (mysql_num_rows($result) <= 0) {
      $last_number = 0;
   }
   else {
      $data = mysql_fetch_object($result);
      $last_number = $data->item_name;
   }
   if ($last_number == 999999) {
   
   } 
   else {
      $next_number = $last_number + 1;
      return $next_number;
   }
}
function get_total_flags($item_id, $repository_id, $db) {
   
   $three_months = (60 * 60 * 24) * 90;
   $three_months_ago = time() - $three_months;
   
   $command = "SELECT bi.item_id,bi.item_name,count(bs.status_flagged) AS flag_count FROM battery_item AS bi LEFT OUTER JOIN battery_status AS bs ON 
bs.item_id=bi.item_id AND bs.status_flagged > ".$three_months_ago." AND bs.date_deleted<=0 WHERE bi.item_id=".$item_id." AND bi.date_deleted<=0 AND bi.repository_id=".$repository_id." GROUP BY bi.item_id";
   $result = mysql_query($command, $db);
   $data = mysql_fetch_object($result);
   $flag_count = $data->flag_count;
   return $flag_count;
}
?>