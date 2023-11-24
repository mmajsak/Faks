<?php



// Create the 'drivers' table
function createTable($MySQL) {
    $createTableQuery = "CREATE TABLE drivers (
        driverId VARCHAR(50) PRIMARY KEY,
        code VARCHAR(10),
        url VARCHAR(255),
        permanentNumber INT,
        givenName VARCHAR(50),
        familyName VARCHAR(50),
        dateOfBirth DATE,
        nationality VARCHAR(50)
    )";

    // Execute the query
    if (mysqli_query($MySQL, $createTableQuery)) {
        echo "Table 'drivers' created successfully.<br>";
    } else {
        echo "Error creating table: " . mysqli_error($MySQL) . "<br>";
    }
}


// Fill the 'drivers' table with data from the API
function fillTable($MySQL) {
    // Load XML data from the API
    $api_url = 'http://ergast.com/api/f1/2023/drivers/';
    $xml_data = file_get_contents($api_url);

    // Check if data retrieval was successful
    if ($xml_data === FALSE) {
        die('Error fetching data from the API');
    }

    // Load XML data
    $xml = simplexml_load_string($xml_data);

    // Check if XML parsing was successful
    if ($xml === FALSE) {
        die('Error parsing XML data');
    }

    $driverid = 1;
    // Insert data into the 'drivers' table
    foreach ($xml->DriverTable->Driver as $driver) {
        $insertQuery = "INSERT INTO drivers (driverId, code, url, permanentNumber, givenName, familyName, dateOfBirth, nationality)
                        VALUES (
                            $driverid,
                            '{$driver->attributes()->code}',
                            '{$driver->attributes()->url}',
                            '{$driver->PermanentNumber}',
                            '{$driver->GivenName}',
                            '{$driver->FamilyName}',
                            '{$driver->DateOfBirth}',
                            '{$driver->Nationality}'
                        )";

        // Execute the query
        mysqli_query($MySQL, $insertQuery);

        $driverid++; 
    }
}

// Display data from the 'drivers' table in an HTML table
function displayTable($MySQL) {
    $selectQuery = "SELECT * FROM drivers";
    $result = mysqli_query($MySQL, $selectQuery);

    if ($result->num_rows > 0) {
        // Display data in HTML table
        echo "<table border='1'>
                <tr>  
                    <th width='16'></th>                  
                    <th>Code</th>                                     
                    <th>Given Name</th>
                    <th>Family Name</th>
                    <th>Permanent Number</th>
                    <th>Date of Birth</th>
                    <th>Nationality</th>
                    <th>URL</th>
                </tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>";
                        if ($_SESSION["user"]["role"] == 1 || $_SESSION["user"]["role"] == 2) 
                        {
                            echo '<a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;delete=' . $row["driverId"] . '"><img src="slike/delete.png" alt="obriši"></a>';
                        }
                        echo 
                    "</td>
                    <td>{$row['code']}</td>                    
                    <td>{$row['givenName']}</td>
                    <td>{$row['familyName']}</td>
                    <td>{$row['dateOfBirth']}</td>
                    <td>{$row['nationality']}</td>
                    <td><a href='{$row['url']}' target='_blank'>{$row['url']}</a></td>
                    <td>{$row['permanentNumber']}</td>
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "Table 'drivers' is empty.";
    }
}

?>
