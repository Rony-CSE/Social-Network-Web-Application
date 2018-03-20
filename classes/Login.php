<?php
class Login{
	//
	public static function isLoggedIn(){
		//check if the cookie set in browser
		if (isset($_COOKIE['SNID'])) {
			//check if the cookie is saved into database
			if (Database::query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])))) {
                //retrieve user id from the database which user is logged in
				$user_id = Database::query('SELECT user_id FROM login_tokens WHERE token=:token', array(':token'=>sha1($_COOKIE['SNID'])))[0]['user_id'];
				return $user_id;
			}
		}
		//
		return false;
	}
}
?>