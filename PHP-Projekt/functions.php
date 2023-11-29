<?php

function createTable($MySQL) 
    {
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

        if (mysqli_query($MySQL, $createTableQuery)) 
            {
                print "Table 'drivers' created successfully.<br>";
            } 
        else 
            {
                print "Error creating table: " . mysqli_error($MySQL) . "<br>";
            }
    }


// Tablica drivers se puni sa podataka dohvacenih sa api-a
function fillTable($MySQL) 
    {
        $api_url = 'http://ergast.com/api/f1/2023/drivers/';
        $xml_data = file_get_contents($api_url);

        if ($xml_data === FALSE) 
            {
                die('Error fetching data from the API');
            }

        $xml = simplexml_load_string($xml_data);

        if ($xml === FALSE) 
            {
                die('Error parsing XML data');
            }

        //u API-u je driverId neka kratica pa sam im dodjeljivao svoj ID
        $driverid = 1; 
        
        // Insertanje podataka u drivers tablicu
        foreach ($xml->DriverTable->Driver as $driver) 
            {
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

                mysqli_query($MySQL, $insertQuery);
                $driverid++; 
            }
    }


function displayTable($MySQL) 
    {
        $selectQuery = "SELECT * FROM drivers";
        $result = mysqli_query($MySQL, $selectQuery);

            if ($result->num_rows > 0) 
                {
                    print "<table border='1'>
                            <tr>  
                                <th width='16'></th>  
                                <th width='16'></th>                  
                                <th>Code</th>   
                                <th>Permanent Number</th>                                  
                                <th>Given Name</th>
                                <th>Family Name</th>
                                <th>Date of Birth</th>
                                <th>Nationality</th>
                                <th>URL</th>
                            </tr>";

                            while ($row = $result->fetch_assoc()) 
                                {
                                    print "
                                        <tr>
                                            <td>";
                                            if (isset($_SESSION["user"]["role"]) && ($_SESSION["user"]["role"] == 1 || $_SESSION["user"]["role"] == 2)) 
                                                {
                                                    print '<a href="index.php?menu=4&amp;edit=' . $row["driverId"] . '"><img src="slike/edit.png" alt="obriši"></a>';
                                                }
                                            print "
                                            </td>

                                            <td>";
                                            if (isset($_SESSION["user"]["role"]) && $_SESSION["user"]["role"] == 1) 
                                                {
                                                    print '<a href="index.php?menu=4&amp;delete=' . $row["driverId"] . '"><img src="slike/delete.png" alt="obriši"></a>';
                                                }
                                            print 
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
                    print "</table>";
                }

            else 
                {
                    print "Table 'drivers' is empty.";
                }
    }


function deleteDriver($MySQL)
    {
        $query  = "DELETE FROM drivers";
        $query .= " WHERE driverId=".(int)$_GET['delete'];
        $query .= " LIMIT 1";
        $result = mysqli_query($MySQL, $query);

        $_SESSION['message'] = '<p>You successfully deleted user profile!</p>';
        header("Location: index.php?menu=4");
    }

function editDriver($MySQL)
    {
        $query  = "SELECT * FROM drivers";
        $query .= " WHERE driverId=".$_GET['edit'];
        $result = mysqli_query($MySQL, $query);
        $row = mysqli_fetch_array($result);
        
        print '
        <h2>Edit driver profile</h2>
        <form action="" id="drivers_form" name="drivers_form" method="POST">

            <input type="hidden" id="_action_" name="_action_" value="TRUE">

            <input type="hidden" id="edit" name="edit" value="' . $_GET['edit'] . '">
            
            <label for="fname">First Name *</label>
            <input type="text" id="fname" name="givenName" value="' . $row['givenName'] . '" placeholder="Driver name.." required>

            <label for="lname">Last Name *</label>
            <input type="text" id="lname" name="familyName" value="' . $row['familyName'] . '" placeholder="Driver last natme.." required>
                
            <label for="code">Code *</label>
            <input type="text" id="code" name="code" value="' . $row['code'] . '" placeholder="VER" required>

            <label for="number">Driver Number</label>
            <input type="number" id="number" name="permanentNumber"  value="' . $row['permanentNumber'] . '" placeholder="Driver number..." required>
            
            <label for="date">Driver birthday</label>
            <input type="date" id="date" name="dateOfBirth"  value="' . $row['dateOfBirth'] . '" placeholder="Driver birthday..." required>

            
            <label for="nationality">nationality</label>
            <select name="nationality" id="nationality">
                <option value="">'. $row['nationality']. '</option>';
                #Select all countries from database webprog, table countries
                $_query  = "SELECT * FROM countries";
                $_result = mysqli_query($MySQL, $_query);
                while($_row = mysqli_fetch_array($_result)) 
                    {
                        print '<option value="' . $_row['country_nationality'] . '"';
                        if ($row['nationality'] == $_row['country_nationality'])
                            { 
                                print ' selected'; 
                            }
                        print '>' . $_row['country_nationality'] . '</option>';
                    }
            print '
            </select>
            
            <label for="url">url *</label>
            <input type="text" id="url" name="url" value="' . $row['url'] . '" placeholder="http://" required>        
            <hr>
            
            <input type="submit" value="Submit">
        </form>

        <p><a href="index.php?menu=4">Back</a></p>';
    }

function updateDriver($MySQL)
    {
        $query  = "UPDATE drivers SET givenName='" . $_POST['givenName'] . "', familyName='" . $_POST['familyName'] . "', dateOfBirth='" . $_POST['dateOfBirth'] . "', nationality='" . $_POST['nationality'] . "', permanentNumber='" . $_POST['permanentNumber'] . "', url='" . $_POST['url'] . "', code='" . $_POST['code']. "'";
        $query .= " WHERE driverId=" . (int)$_POST['edit'];
        $query .= " LIMIT 1";
        $result = mysqli_query($MySQL, $query);

        mysqli_close($MySQL);
        $_SESSION['message'] = '<p>You successfully changed driver profile!</p>';
        header("Location: index.php?menu=4");
    }
?>
