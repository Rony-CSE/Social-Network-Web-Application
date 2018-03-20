<?php
//connect with the database 
require_once('classes/Database.php');

    //get data from the form
	if (isset($_POST['createaccount'])) {
		$username   = $_POST['username'];
		$password   = $_POST['password'];
		$email      = $_POST['email'];

        //check if the username is already exists
        if (!Database::query('SELECT username from tbl_users WHERE username=:username', array(':username'=>$username))){

            //check if the length of username greater than 3 and less than 32
            if (strlen($username) >= 3 && strlen($username) <= 32){

                //
                if (preg_match('/[a-zA-Z0-9_]+/',$username)){

                    //
                    if (strlen($password) >= 6 && strlen($password) <= 60){

                        //
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)){

                            //
                            if (!Database::query('SELECT email FROM tbl_users WHERE email=:email', array(':email'=>$email))) {
                                Database::query('INSERT INTO tbl_users (username, password, email) VALUES (:username, :password, :email)', array(':username'=>$username, ':password'=>password_hash($password, PASSWORD_BCRYPT), ':email'=>$email));
                                //
                                echo 'Successful!!!';
                            }else{
                                //
                                echo 'Email is already used!';
                            }
                        }else{
                            //
                            echo 'Invalid email!';
                        }
                    }else{
                        //
                        echo 'Invalid password!';
                    }
                }else{
                    //
                    echo 'Invalid username';
                }
            }else{
                //
                echo 'Invalid username!';
            }
	    }else{
            //
            echo 'User already exists!';
        }
    }
?>


<!-- registration form -->
<h1>Register</h1>
<form action="create-account.php" method="post">
	<input type="text" name="username" value="" placeholder="Username ..."><br><br>
	<input type="password" name="password" value="" placeholder="Password ..."><br><br>
	<input type="email" name="email" value="" placeholder="example@example.com"><br><br>
	<input type="submit" name="createaccount" value="Create Account">
</form>
