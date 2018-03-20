<?php
//
require_once('classes/Database.php');
include('classes/Login.php');
$tokenIsValid = False;
// Check if the user is logged in
if (Login::isLoggedIn()) {
    echo 'Logged in ';
    echo 'User id=> ' . Login::isLoggedIn() . '<br>';
    //check if the form is submitted, then change the password
    if (isset($_POST['changepassword'])){
        $oldpassword = $_POST['oldpassword'];
        $newpassword = $_POST['newpassword'];
        $newpasswordrepeat = $_POST['newpasswordrepeat'];
        $userid = Login::isLoggedIn();

        if(password_verify($oldpassword, Database::query('SELECT password FROM tbl_users WHERE id=:userid', array(':userid'=>$userid))[0]['password'])){

            if ($newpassword == $newpasswordrepeat){

                if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60){
                    Database::query('UPDATE tbl_users SET password=:newpassword WHERE id=:userid', array(':newpassword'=>password_hash($newpassword, PASSWORD_BCRYPT), ':userid'=>$userid));
                    echo 'Password changed successfully';
                }
            }else{
                echo "Passwords don't match";
            }
        }else{
            echo 'Incorrect old password!';
        }
    }
} else {
    if (isset($_GET['token'])) {
        $token = $_GET['token'];
        // Check the token if valid and change passowrd
        if (Database::query('SELECT user_id FROM password_tokens WHERE token=:token', array(':token'=>sha1($token)))) {
            $user_id = Database::query('SELECT user_id FROM password_tokens WHERE token=:token', array(':token'=>sha1($token)))[0]['user_id'];
            $tokenIsValid = True;
            // Reset the password
            if (isset($_POST['changepassword'])){
                $newpassword = $_POST['newpassword'];
                $newpasswordrepeat = $_POST['newpasswordrepeat'];

                    if ($newpassword == $newpasswordrepeat){

                        if (strlen($newpassword) >= 6 && strlen($newpassword) <= 60){
                            Database::query('UPDATE tbl_users SET password=:newpassword WHERE id=:userid', array(':newpassword'=>password_hash($newpassword, PASSWORD_BCRYPT), ':userid'=>$userid));
                            echo 'Password changed successfully';
                            // After changing the password delete the token from database
                            Database::query('DELETE FROM password_tokens WHERE user_id=:userid', array(':userid'=>$userid));
                        }
                    }else{
                        echo "Passwords don't match";
                    }
            }
        } else{
            die('Token Invalid');
    }
}else {
    //
    die('Not logged in');
}
}
?>
<h1>Change your Password</h1>
<form action="<?php if (!$tokenIsValid) { echo 'change-password.php'; } else { echo 'change-password.php?token='.$token.''; }?>" method="post">
    <?php if ($tokenIsValid) { echo '<input type="password" name="oldpassword" value="" placeholder="Current Password ...">'; } ?><br><br>
    <input type="password" name="newpassword" value="" placeholder="New Password ..."><br><br>
    <input type="password" name="newpasswordrepeat" value="" placeholder="Repeat Password ..."><br><br>
    <input type="submit" name="changepassword" value="Change Password">
</form>
