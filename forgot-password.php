<?php
//
require_once('classes/Database.php');

if (isset($_POST['resetpassword'])) {
	$cstrong = True;
            $token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
            // Catch email from the form
            $email = $_POST['email'];
            // Catch the id against the email
            $user_id = Database::query('SELECT id FROM tbl_users WHERE email=:email', array(':email'=>$email))[0]['id'];

            // Store password tokens into database
            Database::query('INSERT INTO password_tokens (token, user_id) VALUES (:token, :user_id)', array(':token'=>sha1($token), ':user_id'=>$user_id));

            echo 'Email sent.';
            echo '<br>';
            echo $token;
}
?>
<h1>Forgot Password</h1>
<form action="forgot-password.php" method="post">
	<input type="text" name="email" value="" placeholder="Email ..."><br><br>
	<input type="submit" name="resetpassword" value="Reset Password">
</form>