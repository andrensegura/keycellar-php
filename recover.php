<?php

readfile("html/header.html");

////////////////////
//When the page is loaded, we need to check if there is a recovery
//key already in the URL. If there is one, we need to handle the
//recovery.
////////////////////
$recovery_key = $_GET['key'];
if ( !empty($recovery_key) ) {
    //match the username to the key assigned to it
    include_once("mysql.php");
    $result = query_database("SELECT username FROM users WHERE verified = '"
                                . $recovery_key . "';");
    $username = $result->fetch_assoc()['username'];

    if ( !empty($username) ) {
        //if the key matches a user, then check if the user has provided a
        //new password and test the new password's quality
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
        //if no user matches the key, ask them for an email to send a new key to.
        readfile("html/recovery.html");
    }

////////////////////
//If there is no recovery key, then we need to check if the user
//has already provided us an email to send the recovery_key to.
////////////////////
} else {
    $email = $_POST['email'];
    if ( !empty($email) ) {
        //if the user has provided an email, we need to make sure that a user
        //has actually registerd with it.
        include_once("mysql.php");
        $result = query_database("SELECT email FROM users WHERE email = '" . $email . "';");
        $database_email = $result->fetch_assoc()['email'];

        //check if anything matched the given email. if it matched something,
        //then create a recovery key and email it to the user.
        if (!empty($database_email)) {
            $recovery_hash = hash('sha256', $database_email . "recover" . time());
            $recovery_link = "https://keycellar.com/php/recover.php?key=" . $recovery_hash;
            query_database("UPDATE users SET verified = '"
                            . $recovery_hash . "' WHERE email = '" . $database_email . "';");
            include("mail.php");
            send_recovery_email($database_email, $recovery_link);
            echo "Password recovery instructions have been sent to: " . $database_email . " :)";
        //if it didn't match anything, then say so and give them another chance
        //to input a proper email address.
        }else{
           readfile("html/recovery.html");
           echo "Sorry, the email address '" . $email . "' hasn't been registered with us :(";
        }
    ////////////////////
    //If they haven't provided an email already, then we need to ask for one.
    ////////////////////
    }else{
        readfile("html/recovery.html");
    }
}


////////////////////
//test_passwords checks the set of passwords provided and makes
//sure that they match and are of acceptable quality.
//The function returns a string indicating any issues with the
//password, or nothing if the password is OK.
////////////////////

//I can see moving this to functions.php, but I don't know how many
//files would actually use this. Like, two?

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
    //I've got 99 problems, so I used regular expressions. Now I have 100 problems.
    if ( preg_match('/[^A-Za-z0-9.#\-$]/', $password) ){
        return "Password contains invalid characters.<br>Valid characters are alphanumeric and: . # - ";
    }
}

?>
