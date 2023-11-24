<?php

include 'functions.php';

// Check if the 'drivers' table exists
$tableExistsQuery = "SHOW TABLES LIKE 'drivers'";
$tableExistsResult = mysqli_query($MySQL, $tableExistsQuery);



if ($tableExistsResult->num_rows > 0) 
{
    if (isset($_GET['edit']) && $_GET['edit'] != '') 
    {
    if ($_SESSION['user']['role'] == 1 || $_SESSION['user']['role'] == 2)
    {
    editDriver($MySQL);
    }
    else echo '<p>Zabranjeno</p>';
    }
    // Table exists, display data
    else 
    {
        displayTable($MySQL);
    }
} 
else
{
    // Table doesn't exist, create and fill it
    createTable($MySQL);
    fillTable($MySQL);
    // Display the newly created table
    displayTable($MySQL);
}

if (isset($_GET['delete']) && $_GET['delete'] != '') 
{
    deleteDriver($MySQL);
}



?>

