<?php

readfile("html/header.html");

$recovery_key = $_GET['key'];
if ( !empty($recovery_key) ) {
    include_once("mysql.php");
    $result = query_database("SELECT username FROM users WHERE verified = '"
                                . $recovery_key . "';");
    $username = $result->fetch_assoc()['username'];
    if ( !empty($username) ) {
        $input_password = $_POST['password'];
        $input_password2 = $_POST['password2'];

        if ( empty($input_password) || empty($input_password2) ) {
            echo "<p>Please enter a new password for the user '" . $username . "'.</p>";
            readfile("html/new_pass.html");
            die();
        }

        $error = test_passwords($input_password, $input_password2);
        
        if (empty($error) ) {
            $new_pass = password_hash($input_password, PASSWORD_BCRYPT);
            query_database("UPDATE users SET password = '" . $new_pass . "' WHERE username = '"
                            . $username . "';");
            query_database("UPDATE users SET verified = '' WHERE username = '" . $username . "';");
            echo "Password updated! Go log in :)";            
        }else{
            echo "<p>Please enter a new password for the user '" . $username . "'.</p>";
            readfile("html/new_pass.html");
            echo "<br>" . $error ;
        }


    }else {
        readfile("html/recovery.html");
    }

} else {
    $email = $_POST['email'];
    if ( !empty($email) ) {
        include_once("mysql.php");
        $result = query_database("SELECT email FROM users WHERE email = '" . $email . "';");
        $database_email = $result->fetch_assoc()['email'];

        if (!empty($database_email)) {
            $recovery_hash = hash('sha256', $database_email . "recover" . time());
            $recovery_link = "https://keycellar.com/php/recover.php?key=" . $recovery_hash;
            query_database("UPDATE users SET verified = '"
                            . $recovery_hash . "' WHERE email = '" . $database_email . "';");
            include("mail.php");
            send_recovery_email($database_email, $recovery_link);
            echo "Password recovery instructions have been sent to: " . $database_email . " :)";
        }else{
           readfile("html/recovery.html");
           echo "Sorry, the email address '" . $email . "' hasn't been registered with us :(";
        }
    }else{
        readfile("html/recovery.html");
    }
}


function test_passwords($password, $password2){
    //PASSWORD TESTS
    if ( strlen($password) < 8 ) {
        return "Password is less than 8 characters.";
    }   
    if ( $password != $password2 ) {
        return "Password mismatch.";
    }   
    
    //PASSWORD CHARACTER TEST
    //the preg_match looks for anything that is not alphanumeric, '.', '#', '-'
    if ( preg_match('/[^A-Za-z0-9.#\-$]/', $password) ){
        return "Password contains invalid characters.<br>Valid characters are alphanumeric and: . # - ";
    }
}

?>
