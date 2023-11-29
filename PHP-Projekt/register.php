<?php 
	print '
	<h1>Registration Form</h1>
	<div id="register">';
	
	if ($_POST['_action_'] == FALSE) 
		{
			print '
			<form action="" id="registration_form" name="registration_form" method="POST">
				<input type="hidden" id="_action_" name="_action_" value="TRUE">
				
				<label for="fname">First Name *</label>
				<input type="text" id="fname" name="firstname" placeholder="Your name.." required>

				<label for="lname">Last Name *</label>
				<input type="text" id="lname" name="lastname" placeholder="Your last natme.." required>
					
				<label for="email">Your E-mail *</label>
				<input type="email" id="email" name="email" placeholder="Your e-mail.." required>
				
				<label for="username">Username:* <small>(Username must have min 5 and max 10 char)</small></label>
				<input type="text" id="username" name="username" pattern=".{5,10}" placeholder="Username.." required><br>
				
										
				<label for="password">Password:*</label>
				<input type="password" id="password" name="password" placeholder="Password.." required>

				<label for="country">Country:</label>
				<select name="country" id="country">
					<option value="">molimo odaberite</option>';
					$query  = "SELECT * FROM countries";
					$result = mysqli_query($MySQL, $query);
					while($row = mysqli_fetch_array($result)) 
						{
							print '<option value="' . $row['country_code'] . '">' . $row['country_name'] . '</option>';
						}

				print '
				</select>
				
				<label for="role">Uloga: </label>
				<select name="role" id="role">
					<option value="1">admin</option>
					<option value="2">editor</option>
					<option value="3">user</option>
				</select>

				<br>	
				<input type="submit" value="Submit">
			</form>';
		}
	else if ($_POST['_action_'] == TRUE) 
		{
			
			$query  = "SELECT * FROM users";
			$query .= " WHERE email='" .  $_POST['email'] . "'";
			$query .= " OR username='" .  $_POST['username'] . "'";
			$result = mysqli_query($MySQL, $query);
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			
			if ($row === null ) 
				{
					$pass_hash = password_hash($_POST['password'], PASSWORD_DEFAULT, ['cost' => 12]);
					
					$query  = "INSERT INTO users (firstname, lastname, email, username, password, country, role)";
					$query .= " VALUES ('" . $_POST['firstname'] . "', '" . $_POST['lastname'] . "', '" . $_POST['email'] . "', '" . $_POST['username'] . "', '" . $pass_hash . "', '" . $_POST['country'] . "','" . $_POST['role'] . "')";
					$result = mysqli_query($MySQL, $query);
					print '<p>' . ucfirst(strtolower($_POST['firstname'])) . ' ' .  ucfirst(strtolower($_POST['lastname'])) . ', thank you for registration </p>
					<hr>';
				}
			else 
				{
					print '<p>User with this email or username already exist!</p>';
				}
		}
	print '
	</div>';
?>