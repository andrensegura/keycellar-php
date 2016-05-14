<?php
    require ('steam/SteamAuthentication/steamauth/steamauth.php');  
    readfile('html/header.html');
    
	# You would uncomment the line beneath to make it refresh the data every time the page is loaded
	// $_SESSION['steam_uptodate'] = false;
?>
<?php
if(!isset($_SESSION['steamid'])) {

    echo "Login to Steam to auto-add tradeable games to your collection:";
    echo steamlogin(); //login button
    echo "<i><a href='http://store.steampowered.com/'>Proudly powered by Steam!</a></i>";
    
}  elseif (isset($_POST['up_steam'])) {
    require_once('functions.php');
    require_once('cookie.php');

    if ( check_login( check_cookie(), $_POST['password'] ) ) {
        echo "Your tradeable game library has been updated :)<br>You may log out of Steam now.";
        logoutbutton();
        echo "<i><a href='http://store.steampowered.com/'>Proudly powered by Steam!</a></i>";
    } else {
        echo "Sorry, your password was incorrect. :( Please click <a href='steam.php'>here</a> and try again."; 
    }

} else {
    include ('steamauth/userInfo.php');

    //Protected content
    echo "<form method='post' action='steam.php'>"; 
    echo "<input type='hidden' name='up_steam' value='steamprofile['steamid'].";
    echo "Successfully logged into Steam! Please click below to update your list.<br>";
    echo "Enter current password: <input type='password' name='password'><br>";
    echo "<input type='submit' value='Update'>";
    echo "</form>";
 
    logoutbutton();
    echo "<i><a href=\"http://store.steampowered.com/\">Proudly powered by Steam!</a></i>";
}
?>  
