<?php
////////////////
//this page is under heavy construction
//and is basically just a testing page.
////////////////


////////////////
//Check if a the visitor is logged in.
////////////////
include_once("cookie.php");
$username = check_cookie();
readfile("html/header.html");

include_once("mysql.php");
$result = query_database("SELECT * FROM users WHERE username = '" . $username . "';");
$result = $result->fetch_assoc();

////////////////
//print out some info.
////////////////
echo "Username: " . $result['username'] . "<br>";
echo "Email: " . $result['email'] . "<br>";
if (!empty($result['avatar'])){
    echo "Avatar: <img src='" . $result['avatar'] . "'><br>";
} else {
    echo "Avatar: <img src='pics/default-avatar.png'><br>";
}
echo "Added Games: " . $result['added_games'] . "<br>";
echo "Steam Games: " . $result['steam_games'] . "<br>";

echo "<br><hr><a href='./steam.php'>Add tradeable games from Steam</a><br>";



?>
