<?php
require('../battery_static/database.inc');
require('../battery_include/battery_utilities.inc');
$admin_db = admin_db_connect();
session_start();
$login_id = $_SESSION['login_id'];
$login_admin = $_SESSION['login_admin'];
$login_name = $_SESSION['login_name'];

$repository_id = $_SESSION['repository_id'];
$repository_name = get_repository_name($repository_id);
$tz = get_time_zone($repository_id);
date_default_timezone_set($tz);

if (!($login_id && $login_admin && $repository_id)) {
   header("Location: login.php");
   exit();
}

$action = $_GET['action'];
switch ($action) {
   case "add_user":
      if (count($_POST) > 0) {
      $user_name = $_POST['user_name'];
      $user_password = $_POST['user_password'];
      $good_name = "^[a-zA-Z0-9_]{3,25}$";
      $good_password = "^[a-zA-Z0-9_]{3,12}$";
   
      if (!($user_name && $user_password)) {
         $error_message = "Please make sure you've filled out both fields";
      }
      else if (!(valid_input($user_name, $good_name))) {
         $error_message = "Login must be 3 to 25 characters in length and alphanumeric";
      }
      else if (!(valid_input($user_password, $good_password))) {
         $error_message = "Password must be no greater than 12 characters in length";
      }
      else {
         $command = "SELECT login_name from battery_login where login_name='".$user_name."'";
         $result = mysql_query($command, $admin_db);         
         if (mysql_num_rows($result) > 0) {
            $error_message = "This user name is already taken on the database, please choose another.";
         }
         else {
            $command = "INSERT into battery_login(login_id, login_name, login_password, repository_id, date_deleted) values('','".addslashes($user_name)."',password('".addslashes($user_password)."'),".$repository_id.",0)";
            $result = mysql_query($command, $admin_db);
      
            header("Location: admin.php?confirmation=added");
            exit();
         }
      }
      }
      include('../battery_include/admin_header.inc');
      if ($error_message) {
         echo "<span style='color:red;'>".$error_message."</span>";
      }
      else {
         echo "<h2>Create a new user to access the battery repository</h2>";
      }
      ?>
      <form action='admin.php?action=add_user' method='post'>
      <p>
      <label>Create a user name: </label>
      <input type='text' name='user_name' value='<? echo $user_name; ?>' size='12' max='25' />
      <label>Choose a password: </label>
      <input type='password' name='user_password' value='<? echo $user_password; ?>' size='12' max='12' />
      <br /><br />
      <input type='submit' class='button' value='Create User' />
      </p>
      </form>
      
      <?php
      
   break;
   case "reset_password":
      $user_id = $_GET['user_id'];
      
      if (count($_POST) > 0) {
         $user_id = $_POST['user_id'];
         $user_password_1 = $_POST['user_password_1'];
         $user_password_2 = $_POST['user_password_2'];
         $good_password = "^[a-zA-Z0-9_]{3,12}$";
                  
         if (!(is_numeric($user_id))) {
            $error_message = "Error assigning user to password reset";
         }
         else if (!($user_password_1 && $user_password_2)) {
            $error_message = "Please enter the same password twice";
         }
         else if ($user_password_1 != $user_password_2) {
            $error_message = "You have entered the password incorrectly in one field";
         }
         else if (!(valid_input($user_password_1, $good_password))) {
            $error_message = "Password must range from 3 to 12 characters in length, and must be alphanumeric";
         }
         else {
            $command = "UPDATE battery_login set login_password=password('".addslashes($user_password_1)."') where login_id=".addslashes($user_id)." 
            AND date_deleted <=0 AND repository_id=".$repository_id."";
            $result = mysql_query($command, $admin_db);
            
            header("Location: admin.php?confirmation=reset");
            exit();
         }
      
      }
      include('../battery_include/admin_header.inc');
      if ($error_message) {
         echo "<span style='color:red;'><h2>".$error_message."</h2></span>";
      }
      else {
         echo "<h2>Set a new password for this user</h2>";
      }
?>
      <form action='admin.php?action=reset_password' method='POST'>
      <p>
      <label>Choose a Password: </label>
      <input type='password' name='user_password_1' size='12' max='12' />
      <label>Re-enter Password: </label>
      <input type='password' name='user_password_2' size='12' max='12' />
      <input type='hidden' name='user_id' value='<? echo $user_id; ?>' />
      <br />
      <br />
      <input class='button' type='submit' value='Reset Password' />
      </p>
      </form>
      
      <?php     
     
   break;
   case "set_time_zone":
      if (count($_POST) > 0) {
	 $admin_db = admin_db_connect();
         $tz = $_POST['tz'];
         $command = "UPDATE battery_repository SET repository_time_zone='".$tz."' WHERE repository_id='".$repository_id."'";
         $result = mysql_query($command, $admin_db);
            
         if ($result == false) {

         }
         else {
         
            header("Location: admin.php?confirmation=time_zone_changed");
            exit(); 
         }
      }
      include('../battery_include/admin_header.inc');
      ?>
      <h2>Repository Time Zone is currently set to: <?php echo $tz; ?></h2>
      <form method="POST" action="admin.php?action=set_time_zone">
	<p>
	<select name="tz">
	<option value="Pacific/Honolulu">(GMT-10:00) Hawaii</option>
	<option value="America/Anchorage">(GMT-09:00) Alaska</option>
	<option selected="yes" value="America/Los_Angeles">(GMT-08:00) Pacific Time (US &amp; Canada)</option>
	<option value="America/Phoenix">(GMT-07:00) Arizona</option>
	<option value="America/Denver">(GMT-07:00) Mountain Time (US &amp; Canada)</option>
	<option value="America/Chicago">(GMT-06:00) Central Time (US &amp; Canada)</option>
	<option value="America/New_York">(GMT-05:00) Eastern Time (US &amp; Canada)</option>
	<option value="America/Indiana/Indianapolis">(GMT-05:00) Indiana (East)</option>
	</select>
	<input type="submit" class="button" value="Change Time Zone" />
	</p>
      </form>

      <?php
   break;
   case "delete_user":

         if (count($_POST) > 0) {
            #do password check, then delete user
            $user_id = $_POST['user_id'];
            $password = $_POST['password'];      
            $good_password = "^[a-zA-Z0-9_]{3,12}$";      
            if (!($password)) {
               $error_message = "Please enter your password to confirm";
            }
            else if (!(valid_input($password, $good_password))) {
               $error_message = "You have entered an incorrect password";
            } 
            else {     
                  if (!is_numeric($user_id)) {
                     $error_message = "Error with GET variable";
                  }
                  else {
                     $command = "UPDATE battery_login set date_deleted=UNIX_TIMESTAMP() where login_id=".addslashes($user_id)." 
                     AND repository_id=".$repository_id."";
                     $result = mysql_query($command, $admin_db);
            
                     header("Location: admin.php?confirmation=removed");
                     exit();
                  }
             }
         }
         include('../battery_include/admin_header.inc');
         if ($error_message) {
            echo "<span style='color:red;'><h2>".$error_message."</h2></span>";
         }
         else {
            echo "<h2>Are you sure you wish to delete this user?</h2>";
         }

         #pull up request to enter password before deleting user
         $user_id = $_GET['user_id'];
         echo "<h3>Please enter your password to confirm.</h3>
         <form action='admin.php?action=delete_user' method='POST'>
         <p>
         <label>Password: </label>
         <input type='password' size='12' max='12' name='password' />
         <input type='hidden' name='user_id' value='".$user_id."' />
         <br />
         <br />
         <input class='button' type='submit' value='Remove User' />
         </p>
         </form>";
      
   break;
   case "delete_account" :
      if (count($_POST) > 0) {
		$password = $_POST['password'];
		$repository_id = $_SESSION['repository_id'];
		$good_password = "^[a-zA-Z0-9_]{3,12}$";      
		if (!($password)) {
			$error_message = "Please enter your password to confirm";
		}
		else if (!(valid_input($password, $good_password))) {
			$error_message = "You have entered an incorrect password";
		}
      	else {     
            if (!is_numeric($repository_id)) {
               $error_message = "Error with GET variable";
            }
            else {
               $command = "UPDATE battery_repository set date_deleted=UNIX_TIMESTAMP() where repository_id=".$repository_id;
               $result = mysql_query($command, $admin_db);
      
               header("Location: logout.php");
               exit();
            }
        }
     }
     include('../battery_include/admin_header.inc');
     if ($error_message) {
        echo "<span style='color:red;'><h2>".$error_message."</h2></span>";
     }
     else {
        echo "<h2>Are you sure you wish to delete your account?</h2><h2>All your information will be lost.</h2>";
     }
     echo "<h3>Please enter your password to confirm.</h3>
     <form action='admin.php?action=delete_account' method='POST'>
     <p>
     <label>Password: </label>
     <input type='password' size='12' max='12' name='password' />
     <br />
     <br />
     <input class='button' type='submit' value='Remove Account' />
     </p>
     </form>";
   break;
   default:
      $command = "SELECT * FROM battery_login WHERE date_deleted <=0 AND repository_id=".$repository_id." AND login_admin=0";
      $result = mysql_query($command, $admin_db);
      include('../battery_include/admin_header.inc');      
      ?>
      <span style="color:orange;">
      <?php
         $confirmation = $_GET['confirmation'];
         switch ($confirmation) {
         case "added":
            echo "User successfully added.";
         break;
         case "reset":
            echo "Password has been reset.";
         break;
         case "removed":
            echo "User has been removed.";
         break;
         case "time_zone_changed":
            echo "Your time zone is now set to ".$tz;
         break;
         }
?>
      </span> 
      <h1>Admin Access For Battery Repository '<?php echo $repository_name; ?>'</h1>
      <p>Welcome to the admin portal,<br><br>
      Admin access is used for creating/deleting users and resetting passwords.<br>
      Our main site will follow the flagging history of each user, so make sure each user has a uniquely created account.  Log out of your admin account and enter in your own user credentials to access your repository.</p>
      <p>For admin access, the login is '<b><? echo $login_name; ?></b>'.  Please save that info for your records.</p>
      <?php
      if (mysql_num_rows($result) > 0) {
      ?>
      <table>
      <tr><th class="first">Login</th><th>Reset</th><th>Delete</th></tr>
      <?php
      }
      $i=0;
      while ($data = mysql_fetch_object($result)) {
         if ($i%2 ==0) {
            echo "<tr class='row-a'>";
         }
         else {
            echo "<tr class='row-b'>";
         }
         ?>
         	<td class="first"><?php echo $data->login_name; ?></td>
         	<td><a href="admin.php?action=reset_password&user_id=<? echo $data->login_id; ?>">Reset Password</td>
         	<td><a href="admin.php?action=delete_user&user_id=<? echo $data->login_id; ?>">Delete User</td>
         </tr>
         <?php
         $i++;
      }
      ?>
      </table>
      <?php
}
include('../battery_include/battery_footer.inc');
?>