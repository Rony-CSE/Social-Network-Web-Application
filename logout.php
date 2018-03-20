<?php
//
require_once('classes/Database.php');
include('./classes/Login.php');

//check if the user logged in or not
if (!login::isLoggedIn()) {
    //if not logged in kill the page
	die("Not logged in!");
}

//
if (isset($_POST['confirm'])) {
	//
	if (isset($_POST['alldevices'])) {
	    //delete the user id fro the database to logged out from all devices
        Database::query('DELETE FROM login_tokens WHERE user_id=:user_id', array(':user_id'=>Login::isLoggedIn()));
		}else{
	        //check if the user deleted the cookie from the page or not
	        if (isset($_COOKIE['SNID'])){
	            //remove cookie from database
                Database::query('DELETE FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])));
            }
            //remove cookie from the browser
			setcookie('SNID', '1', time()-3600);
		}	
}
?>
<h1>Logout of your Account?</h1>
<p>Are you sure you'd like to logout?</p>
<form action="logout.php" method="post">
	<input type="checkbox" name="alldevices" value="alldevices"> Logout of all devices?<br>
	<input type="submit" name="confirm" value="Confirm">
</form>