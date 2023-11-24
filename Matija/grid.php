<?php

include 'functions.php';

// Check if the 'drivers' table exists
$tableExistsQuery = "SHOW TABLES LIKE 'drivers'";
$tableExistsResult = mysqli_query($MySQL, $tableExistsQuery);

if ($tableExistsResult->num_rows > 0) {
    // Table exists, display data
    displayTable($MySQL);
} 
else {
    // Table doesn't exist, create and fill it
    createTable($MySQL);
    fillTable($MySQL);
    // Display the newly created table
    displayTable($MySQL);
}

?>

