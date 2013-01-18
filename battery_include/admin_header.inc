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
<link rel="stylesheet" type="text/css" rev="stylesheet" href="css/styles.css">
<meta http-equiv="Content-Style-Type" content="text/css" />
</head>
<body>
<div id="wrap">
	<div id="header">
		<h1 id="logo-text">Battery QR</h1>
		<h2 id="slogan">eliminate "bad batteries" once and for all</h2>
		<img id="logo-img" src="images/battery.png" alt="battery_2.png" />
		<div id="header-links">
			<p><strong><? echo $login_name; ?></strong> | <a href="logout.php">log out</a></p>
		</div>
	</div><!--- closes header --->
	<div id="menu">
		<ul>  
	  		<li <? echo menu_nav(null, null, null, $_GET['action'], 'portal'); ?>><a href="admin.php">Admin Portal</a></li>
	  		<li <? echo menu_nav(null, null, null, $_GET['action'], 'add_user'); ?>><a href="admin.php?action=add_user">Add User</a></li>
	  		<li <? echo menu_nav(null, null, null, $_GET['action'], 'set_time_zone'); ?>><a href="admin.php?action=set_time_zone">Change Time Zone</a></li>
	  		<li <? echo menu_nav(null, null, null, $_GET['action'], 'delete_account'); ?>><a href="admin.php?action=delete_account">Delete Account</a></li>
	  		<li class="last">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	  		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
	    	</ul>	
	</div><!--- closes menu --->
	<div id="content-wrap">	
    		<div id="sidebar">
      			
		</div><!--- closes sidebar --->
		<div id="main">