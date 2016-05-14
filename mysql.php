<?php


function query_database($query){
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
    
    //while ($row = $result->fetch_assoc()){
    //    echo $row['username'] . '<br>';
    //}
}


?>
