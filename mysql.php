<?php

/////////////////////
//query_database simply runs a mysql query and returns
//a result object to the caller.
/////////////////////
function query_database($query){
    //config.php contains all of the database configuration info.
    include 'config.php';

    // Create connection
    $conn = new mysqli($DB_SERV, $DB_USER, $DB_PASS, $DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } 

    if ( !$result = $conn->query($query)) {
        die('There was an error running the query [' . $conn->error . ']');
    }

    return $result;
}


?>
