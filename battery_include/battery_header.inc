<?php
echo "<?xml version='1.0' encoding='UTF-8'?>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>BatteryQR - Track and Eliminate bad batteries with QR codes</title>
<meta name="description" content="Track your two-way radio batteries with QR codes on BatteryQR and eliminate bad batteries once and for all." />
<meta name="keywords" content="battery,qr" />
<link rel="icon" type="image/x-icon" href="favicon.ico" />
<link rel="stylesheet" rev="stylesheet" type="text/css" href="css/styles.css" media="screen" />
<link rel="stylesheet" href="css/mobile.css" type="text/css" media="handheld" />
<link rel="stylesheet" media="screen and (max-device-width: 480px)" href="css/mobile.css" />
<!-- and (-webkit-device-pixel-ratio:0.75) -->
<meta name="viewport" content="initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Style-Type" content="text/css" />

<!--[if IE]>
<link rel="stylesheet" href="css/style.css" media="screen" type="text/css" />
<![endif]-->

<!-- <meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" /> -->

<script type="text/javascript">
function selectToggle(toggle, form) {
     var myForm = document.forms[form];
     for( var i=0; i < myForm.length; i++ ) { 
          if(toggle) {
               myForm.elements[i].checked = "checked";
          } 
          else {
               myForm.elements[i].checked = "";
          }
     }
}
</script>

</head>
<body>
<div id="wrap">
	<div id="header">
		<h1 id="logo-text">Battery QR</h1>
		<h2 id="slogan">eliminate "bad batteries" once and for all</h2>
		<img id="logo-img" src="images/battery.png" alt="battery_2.png" />
		<div id="header-links">
			<p><strong><? echo $login_name; ?></strong> | <a <? echo menu_nav('edit_account.php'); ?> href="edit_account.php">account settings</a> | <a href="logout.php">log out</a></p>
		</div>
	</div><!-- closes header -->
	<div id="menu">
		<ul>  
	  		<li <? echo menu_nav('index.php'); ?>><a href="index.php">Home</a></li>
	  		<li <? echo menu_nav('recent_activity.php'); ?>><a href="recent_activity.php">Recent Activity</a></li>
	  		<li <? echo menu_nav('user_history.php', $login_id, $_GET['user_id']); ?>><a href="user_history.php?user_id=<? echo $login_id; ?>">Your Activity</a></li>
      			<li <? echo menu_nav('add_battery.php'); ?>><a href="add_battery.php">Add Battery</a></li>
	      		<li <? echo menu_nav('print_qr.php'); ?>><a href="print_qr.php">Print QR Codes</a></li>
      			<li <? echo menu_nav('how_to_use.php'); ?> class="last"><a href="how_to_use.php">How to Use</a></li>
	    	</ul>
	</div><!-- closes menu -->
	<div id="content-wrap">	
    		<div id="sidebar">
    			 <h1>Search Repository</h1>
      			<form action="index.php" class="searchform">
        		<p>
        		<input name="search_query" class="textbox" type="text" />
        		<input type='hidden' name='mode' value='search' />
          		<input class="button" value="Search" type="submit" />
        		</p>
      			</form>
      			<h1>Users</h1>
      			<?
      			   $repository_id = $_SESSION['repository_id'];
      			   $login_db = login_db_connect();
      			   $command = "SELECT login_name, login_id FROM battery_login WHERE date_deleted<=0 AND repository_id=".$repository_id." AND login_admin=0";
      			   $result = mysql_query($command, $login_db);
      			?>
      			<ul class="sidemenu">
      			        <?
      			        while ($array = mysql_fetch_assoc($result)) {
      			           echo "<li><a href='user_history.php?user_id=".$array['login_id']."'>".$array['login_name']."</a></li>";
      			        }
				?>
    			</ul>
		</div><!-- closes sidebar -->
		<div id="main">