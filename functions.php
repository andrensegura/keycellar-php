<?php

function print_nav(){
    echo "<div class='navbar'>";

    echo "</div>"; //navbar
}

//CHECK_LOGIN
//TAKES username and password strings and checks it against the user table.
//RETURNS True upon successful login, otherwise False
function check_login($username, $password){

    if ( empty($username) && empty($password) ) { //empty can be 0, empty, or not set at all.
        return False;
    } else{
        include_once('mysql.php');
        $result = query_database("SELECT password FROM users WHERE username = '". $username . "';");
        $pass_hash = $result->fetch_assoc()['password'];

        if ( password_verify( $password, $pass_hash) ) {
            set_cookie($username);
            return True;
        } else {
            return False;
        }
    }
}


?>
