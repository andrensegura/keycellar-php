<?php

include_once('cookie.php');
$logged_in = check_cookie();


//This if block logs out a user if they asked to be,
//otherwise it tells the user whom they are logged in as.
if (!empty($logged_in)) {
    if ( $_GET["logout"] == 1 ) {
        remove_cookie($logged_in);
    } else { 
        readfile("html/header.html");
        echo "You are logged in as " . $logged_in . ".";
        echo "<br><a href = './login.php?logout=1'>Logout?</a>";
        die();
    }
}

$username = $_POST["username"];
$password = $_POST["password"];
require_once('functions.php');
$success = check_login($username, $password);

if ($success) {
    header('Location: http://keycellar.com/php/user.php');
} else {
    readfile("html/header.html");
    readfile("html/login.html");
}

?>
