<?php
require_once('classes/Database.php');

if (isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (Database::query('SELECT username from tbl_users WHERE username=:username', array(':username'=>$username))){

        if(password_verify($password, Database::query('SELECT password FROM tbl_users WHERE username=:username', array(':username'=>$username))[0]['password'])){
            echo 'Logged in';

            $cstrong = True;
            $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
            $user_id = Database::query('SELECT id FROM tbl_users WHERE username=:username', array(':username'=>$username))[0]['id'];

            // Store token into database
            Database::query('INSERT INTO login_tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));

            setcookie("SNID", $token, time() + 60 * 60 * 24 * 7, '/', NULL, NULL, TRUE);

        }else{
            echo 'Incorrect password!';
        }
    }else{
        echo 'User not registered!';
    }
}
?>
<h1>Login to your account</h1>
<form action="login.php" method="post">
    <input type="text" name="username" value="" placeholder="Username ..."><p/>
    <input type="password" name="password" value="" placeholder="Password ..."><p/>
    <input type="submit" name="login" value="Login">
</form>