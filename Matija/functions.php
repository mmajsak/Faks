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
                            echo '<a href="index.php?menu=5&amp;edit=' . $row["driverId"] . '"><img src="slike/edit.png" alt="uredi"></a>';
                        }
                        echo 
                    "</td>
                    <td>";
                        if ($_SESSION["user"]["role"] == 1) 
                        {
                            echo '<a href="index.php?menu=5&amp;delete=' . $row["driverId"] . '"><img src="slike/delete.png" alt="obriši"></a>';
                        }
                        echo 
                    "</td>
                    <td>{$row['code']}</td>
                    <td>{$row['permanentNumber']}</td>                    
                    <td>{$row['givenName']}</td>
                    <td>{$row['familyName']}</td>
                    <td>{$row['dateOfBirth']}</td>
                    <td>{$row['nationality']}</td>
                    <td><a href='{$row['url']}' target='_blank'>{$row['url']}</a></td>
                    
                  </tr>";
        }

        echo "</table>";
    } else {
        echo "Table 'drivers' is empty.";
    }
}

function deleteDriver($MySQL)
{
    $query  = "DELETE FROM drivers";
    $query .= " WHERE driverId=".(int)$_GET['delete'];
    $query .= " LIMIT 1";
    $result = mysqli_query($MySQL, $query);

    $_SESSION['message'] = '<p>You successfully deleted user profile!</p>';
    
    # Redirect
    header("Location: index.php?menu=5");
}

function editDriver($MySQL)
{
    $query  = "SELECT * FROM drivers";
    $query .= " WHERE driverId=".$_GET['edit'];
    $result = mysqli_query($MySQL, $query);
    $row = mysqli_fetch_array($result);
    $checked_archive = false;
    
    print '
    <h2>Edit user profile</h2>
    <form action="" id="registration_form" name="registration_form" method="POST">
        <input type="hidden" id="_action_" name="_action_" value="TRUE">
        <input type="hidden" id="DriverID" name="DriverID" value="' . $_GET['edit'] . '">
        
        <label for="fname">First Name *</label>
        <input type="text" id="fname" name="givenName" value="' . $row['givenName'] . '" placeholder="Driver name.." required>

        <label for="lname">Last Name *</label>
        <input type="text" id="lname" name="familyName" value="' . $row['familyName'] . '" placeholder="Driver last natme.." required>
            
        <label for="number">Driver Number</label>
        <input type="number" id="number" name="number"  value="' . $row['permanentNumber'] . '" placeholder="Driver number..." required>
        
        <label for="date">Driver birthday</label>
        <input type="date" id="date" name="date"  value="' . $row['dateOfBirth'] . '" placeholder="Driver birthday..." required>

        
        <label for="country">Country</label>
        <select name="country" id="country">
            <option value="">molimo odaberite</option>';
            #Select all countries from database webprog, table countries
            $_query  = "SELECT * FROM countries";
            $_result = mysqli_query($MySQL, $_query);
            while($_row = mysqli_fetch_array($_result)) {
                print '<option value="' . $_row['country_code'] . '"';
                if ($row['country'] == $_row['country_code']) { print ' selected'; }
                print '>' . $_row['country_name'] . '</option>';
            }
        print '
        </select>
        
        <label for="archive">Archive:</label><br />
        <input type="radio" name="archive" value="Y"'; if($row['archive'] == 'Y') { echo ' checked="checked"'; $checked_archive = true; } echo ' /> YES &nbsp;&nbsp;
        <input type="radio" name="archive" value="N"'; if($checked_archive == false) { echo ' checked="checked"'; } echo ' /> NO
        
        <hr>
        
        <input type="submit" value="Submit">
    </form>
    <p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
}
?>
