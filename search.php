<?php

readfile("html/header.html");

echo "<p><form method='post'>
<input type='text' name='search_query'>
<input type='submit' value='Search'></p>";

$search_query = $_POST['search_query'];

////////////////////
//If a search query was given, search the games table for
//any games containing the string and show them to the user.
//If no query, then we don't need to display anything.
////////////////////
if (!empty($search_query)) {
    echo "<h3>Search results for: " . $search_query . "</h3>";

    include_once("mysql.php");
    $result = query_database("SELECT * FROM games WHERE title LIKE '%" . $search_query . "%';");

    //if anything was found, we need to format the results for display.
    if ($result->num_rows > 0 ){
        echo "<table>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td><img src='"
                    . $row['icon'] . "' height='87' width='231'></td><td>"
                    . $row['title'] . "</td><td>"

                    . "<input type='hidden' value='" . $row['id'] . "'>";
            //if the user is logged in, we can give them the option to add the
            //game to their wishlist or trade list.
            //---------Need to update, placeholder "True" for now.
            if (True) {
               echo "<input type='submit' value='Add'></td></tr>";
            } else {
               echo "</td></tr>";
            }
        }
        echo "</table>";
    //if nothing was found...
    } else {
        echo "No results found! :(";
    }
}

?>
