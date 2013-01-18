<?php //All db_connect methods need to be updated with your own credentials
function member_db_connect() {
   $host = "localhost:/Applications/MAMP/tmp/mysql/mysql.sock"; //my localhost MAMP setting
   $user = 'root'; //your own mysql username
   $pw = 'password'; //your own password
   $database = "batteryqr"; //mysql database created, named batteryqr
   
   $db = mysql_connect($host,$user,$pw)
      or die("Cannot connect to mySQL.");
   
   mysql_select_db($database,$db)
      or die("Cannot connect to database.");
      
   return $db;
}
function login_db_connect() {
   $host = "localhost:/Applications/MAMP/tmp/mysql/mysql.sock";
   $user = "root";
   $pw = 'password';
   $database = "batteryqr";
   
   $db = mysql_connect($host,$user,$pw)
      or die("Cannot connect to mySQL.");
   
   mysql_select_db($database,$db)
      or die("Cannot connect to database.");
      
   return $db;
}

function admin_db_connect() {
   $host = "localhost:/Applications/MAMP/tmp/mysql/mysql.sock";
   $user = 'root';
   $pw = 'password';
   $database = "batteryqr";
   
   $db = mysql_connect($host,$user,$pw)
      or die("Cannot connect to mySQL.");
   
   mysql_select_db($database,$db)
      or die("Cannot connect to database.");
      
   return $db;
}
?>