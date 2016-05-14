<?php

readfile("html/header.html");

//Check if the user is attempting to validate the account
//before doing anything.

$key = $_GET['key'];
if ( !empty($key) ) {
    if ( verify_account($key) ) {
        echo "Verification success! Go log in now :)";
        die();
    }
}

//Not validating, so check if they are trying to register.
//If not, then give them the registration form.

$username    = $_POST['username'];
$email       = $_POST['email'];
$password    = $_POST['password'];
$password2   = $_POST['password2'];
$registering = $_POST['registering'];

if (!empty($registering)){
    $register_success = test_input($username, $email, $password, $password2);
}



//If registering, then store the key in the database and
//send the user an email letting them know how to verify.
if ( !empty($registering) && empty($register_success) ) {
    $key = register_user($username, $email, $password);
    include_once("mail.php");
    send_verification($email, $key);
    readfile("html/success.html");
} elseif ( $registering ) {
    readfile("html/register.html");
    echo "<br>" . $register_success . "<br>";
} else {
    readfile("html/register.html");
}

//This function tests all of the input the user provides to make sure it is all valid.
//Returns an error message describing what went wrong, or nothing if everything passed.
function test_input($username, $email, $password, $password2){
    include_once("mysql.php");

    //USERNAME TESTS
    $result = query_database("SELECT * FROM users WHERE username = '" . $username . "';");
    $result = $result->fetch_assoc()['username'];

    if (empty($username)
        || strlen($username) < 3
        || !ctype_alnum($username)
        || $result ){
        return "Username invalid or username taken.";
    }

    //EMAIL TESTS
    $result = query_database("SELECT * FROM users WHERE email = '" . $email . "';");
    $result = $result->fetch_assoc()['email'];

    if ( !filter_var($email, FILTER_VALIDATE_EMAIL) 
        || $result ){
        return "Email invalid or email taken.";
    } 

    //PASSWORD TESTS
    if ( strlen($password) < 8 ) {
        return "Password is less than 8 characters.";
    }
    if ( $password != $password2 ) {
        return "Password mismatch.";
    }

    //PASSWORD CHARACTER TEST
    //the preg_match looks for anything that is not alphanumeric, '.', '#', 
    if ( preg_match('/[^A-Za-z0-9.#\-$]/', $password) ){
        return "Password contains invalid characters.<br>Valid characters are alphanumeric and: . # - $";
    }

    return;
}

//This function creates a table for the user based on the information provided it.
//It also turns the password into a hash.
//This returns a verification key that the user will need to be able to verify their
//account.
function register_user($username, $email, $password){
    include_once("mysql.php");
    $key = hash('sha256', $email . time() . $username);
    $password = password_hash( $password, PASSWORD_BCRYPT);
    query_database("INSERT INTO users (username, password, email, verified)
                          VALUES ('" . $username . "', '"
                                    . $password . "', '"
                                    . $email . "', '"
                                    . $key . "');" );
    return $key;
}


function verify_account($key){
    include_once("mysql.php");
    $result = query_database("SELECT verified FROM users WHERE verified = '" . $key . "';");
    $verification_key = $result->fetch_assoc()['verified'];

    if ( !empty($verification_key) ) {
        query_database("UPDATE users SET verified = '' WHERE verified = '" . $verification_key . "';");
        return True;
    } else {
        return False;
    }
}
?>
