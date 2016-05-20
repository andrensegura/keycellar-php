<?php

function print_nav(){
    echo "<div class='navbar'>";

    echo "</div>"; //navbar
}

//////////////////////
//CHECK_LOGIN
//TAKES username and password strings and checks it against the user table.
//RETURNS True upon successful login and sets a cookie, otherwise False
//////////////////////
function check_login($username, $password){
    //empty can be 0, empty, or not set at all.
    if ( empty($username) && empty($password) ) {
        return False;
    } else{
        include_once('mysql.php');
        $result = query_database("SELECT password FROM users WHERE username = '". $username . "';");
        $pass_hash = $result->fetch_assoc()['password'];

        //password_verify is a native PHP function:
        //Verifies that the given hash matches the given password. Neat.
        if ( password_verify( $password, $pass_hash) ) {
            include_once('cookie.php');
            set_cookie($username);
            return True;
        } else {
            return False;
        }
    }
}


?>
