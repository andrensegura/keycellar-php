<?php

function send_verification($email, $key){
    $message = "
Hello,

    Thanks for signing up with Keycellar.com!
Here is your verification link:
https://keycellar.com/php/register.php?key=" . $key . "

If this isn't you, please ignore this message.

Andre";
    $message = wordwrap($message, 80);

    $headers = "From: andre@keycellar.com" . "\r\n" ;

    mail($email, "KeyCellar Registration Verification", $message, $headers);
}

function send_recovery_email($email, $recovery_link){
    $message = "
Hello,


This email was sent to you because someone requested a password
reset for the account using address " . $email . ". If this was sent to you
in error, you can disregard this message and no changes will be
made.

If, however, you *did* request a password reset, please click
on the link below to begin.\n"
. $recovery_link
. "

Thank you, and let me know if you have any trouble!


Andre";

    $message = wordwrap($message, 80);

    $headers = "From: Andre <andre@keycellar.com>" . "\r\n" ;

    mail($email, "KeyCellar Password Recovery", $message, $headers);
}


?>
