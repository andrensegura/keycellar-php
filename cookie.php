<?php

function set_cookie($user_string){
    $random_num = str_pad(mt_rand(1,99999999),8,'0',STR_PAD_LEFT); //8 digit number
    $cookie_value = hash('sha256', $random_num . $user_string );
    $cookie_name = "kc_user";
    $time = time() + (86400 * 3);

    setcookie($cookie_name, $cookie_value, $time, "/");


    include_once('mysql.php');
    query_database("UPDATE users SET logged_in = '"
                    . $cookie_value . "' WHERE username = '"
                    . $user_string . "';");
}

function check_cookie() {
    if (isset($_COOKIE['kc_user'])) {
        include_once('mysql.php');
        $result = query_database("SELECT username FROM users WHERE logged_in = '"
                    . $_COOKIE['kc_user'] . "';");
        return $result->fetch_assoc()['username'];
    }
}

//To remove a cookie, ALL parameters must be the same.
//Since this cookie is set with a name, value, time, and path,
//all must be present to remove it.
function remove_cookie() {
    setcookie("kc_user", "", time() - 3600, "/");
}

?>
