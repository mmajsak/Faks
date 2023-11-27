<?php 
	print '
	<h1>Sign In form</h1>
	<div id="signin">';

	if ($_POST['_action_'] == FALSE) 
	{
		print '
		<form action="" name="myForm" id="myForm" method="POST">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">

			<label for="username">Username:*</label>
			<input type="text" id="username" name="username" value="" pattern=".{5,10}" required>
									
			<label for="password">Password:*</label>
			<input type="password" id="password" name="password" value="" required>
									
			<input type="submit" value="Submit">
		</form>';
	}
	else if ($_POST['_action_'] == TRUE) 
		{
			$query  = "SELECT * FROM users";
			$query .= " WHERE username='" .  $_POST['username'] . "'";
			$result = mysqli_query($MySQL, $query);
			$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
			
			if (password_verify($_POST['password'], $row['password'])) 
				{
					$_SESSION['user']['valid'] = 'true';
					$_SESSION['user']['id'] = $row['id'];
					$_SESSION['user']['firstname'] = $row['firstname'];
					$_SESSION['user']['lastname'] = $row['lastname'];
					$_SESSION['message'] = '<p>Dobrodošli, ' . $_SESSION['user']['firstname'] . ' ' . $_SESSION['user']['lastname'] . '</p>';
					$_SESSION['user']['role']= $row['role'];

					header("Location: index.php?menu=4");
				}
			else 
				{
					unset($_SESSION['user']);
					$_SESSION['message'] = '<p>You entered wrong email or password!</p>';
					header("Location: index.php?menu=5");
				}
		}
	print '
	</div>';
?>