<?php

include 'functions.php';

$tableExistsQuery = "SHOW TABLES LIKE 'drivers'";
$tableExistsResult = mysqli_query($MySQL, $tableExistsQuery);

if ($tableExistsResult->num_rows > 0) 
    {
        if (isset($_POST['edit']) && $_POST['_action_'] == 'TRUE') 
            {
                updateDriver($MySQL);
            }
        else if (isset($_GET['edit']) && $_GET['edit'] != '') 
            {
                if ($_SESSION['user']['role'] == 1 || $_SESSION['user']['role'] == 2)
                {
                    editDriver($MySQL);
                }
                else print '<p>Zabranjeno</p>';
            }
        else if (isset($_GET['delete']) && $_GET['delete'] != '') 
            {
                deleteDriver($MySQL);
            }   
        else 
            {
                displayTable($MySQL);
            }
    } 
else
    {
        createTable($MySQL);
        fillTable($MySQL);
        displayTable($MySQL);
    }
?>

