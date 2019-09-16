<?php 
	session_start();

	// server variable declaration 
	$username = "";
	$email    = "";
	$errors = array(); 
	$_SESSION['success'] = "";

	// connect to database
	$db = mysqli_connect('localhost', 'root', '', 'silicon');

	// REGISTER ADMIN
	if (isset($_POST['reg_admin'])) {
		// receive all input values from the form
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
		$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

		// form validation: ensure that the form is correctly filled
		if (empty($username)) { array_push($errors, "Username is required"); }
		if (empty($email)) { array_push($errors, "Email is required"); }
		if (empty($password_1)) { array_push($errors, "Password is required"); }

		if ($password_1 != $password_2) {
			array_push($errors, "The two passwords do not match");
		}
		// register user if there are no errors in the form
		if (count($errors) == 0) {
			$password = md5($password_1);//encrypt the password before saving in the database
			$query = "INSERT INTO admins (username, email, password) 
					  VALUES('$username', '$email', '$password')";
			mysqli_query($db, $query);

			$_SESSION['username'] = $username;
			$_SESSION['success'] = "You are now logged in";
			header('location: admin_index.php');
		}

	}

	// LOGIN ADMIN
	if (isset($_POST['login_admin'])) {
		$username = mysqli_real_escape_string($db, $_POST['username']);
		$password = mysqli_real_escape_string($db, $_POST['password']);

		if (empty($username)) {
			array_push($errors, "Username is required");
		}
		if (empty($password)) {
			array_push($errors, "Password is required");
		}

		if (count($errors) == 0) {
			$password = md5($password);
			$query = "SELECT * FROM admins WHERE username='$username' AND password='$password'";
			$results = mysqli_query($db, $query);

			if (mysqli_num_rows($results) == 1) {
				$_SESSION['username'] = $username;
				$_SESSION['success'] = "You are now logged in";
				header('location: admin_index.php');
			}else {
				array_push($errors, "Wrong username/password combination");
			}
		}
	}


	// ADD A NEW AGENT ON THE SYSTEM
	if (isset($_POST['add_agent'])) {
		$uname = mysqli_real_escape_string($db, $_POST['uname']);
		$fname = mysqli_real_escape_string($db, $_POST['fname']);
		$lname = mysqli_real_escape_string($db, $_POST['lname']);
		$sname = mysqli_real_escape_string($db, $_POST['sname']);
		$email = mysqli_real_escape_string($db, $_POST['email']);
		$phone = mysqli_real_escape_string($db, $_POST['tel']);
		$password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
		$password_2 = mysqli_real_escape_string($db, $_POST['password_2']);
		$cdate = date("Y-m-d");
		$ctime = date("h:i:s");
		$status = 'ACTIVE';
		
		// form validation: ensure that the form is correctly filled
		function validate_phone_number($phone)
		{
			// Allow +, - and . in phone number
			$filtered_phone_number = filter_var($phone, FILTER_SANITIZE_NUMBER_INT);
			// Remove "-" from number
			$phone_to_check = str_replace("-", "", $filtered_phone_number);
			// Check the lenght of number
			// This can be customized if you want phone number from a specific country
			if (strlen($phone_to_check) < 9 || strlen($phone_to_check) > 14) {
			return false;
			} else {
			return true;
			}
		}
		//VALIDATE PHONE NUMBER 
		if (validate_phone_number($phone) !=true) {
			array_push($errors, "Invalid phone number");
		}

		if (empty($uname)) { array_push($errors, "Username is required"); }
		if (empty($fname)) { array_push($errors, "First name required"); }
		if (empty($lname)) { array_push($errors, "Last Name required"); }
		if (empty($sname)) { array_push($errors, "Sir name is required"); }
		if (empty($phone)) { array_push($errors, "input the tel no"); }
		if (empty($email)) { array_push($errors, "Email is required"); }
		if (empty($password_1)) { array_push($errors, "Password is required"); }

		if ($password_1 != $password_2) {
			array_push($errors, "The two passwords do not match");
		}
	
		// register user if there are no errors in the form
		if (count($errors) == 0) {
			$password = md5($password_1);//encrypt the password before saving in the database
			$query = "INSERT INTO agents (username, firstname, lastname, sirname, email, tel, password, dateCreated, timeCreated,status)
							VALUES('$uname','$fname','$lname','$sname','$email','$phone','$password','$cdate','$ctime','$status')";
			$result = mysqli_query($db, $query);
			if($result)
				echo "<script type='text/javascript'>alert('Agent Added successfully!')</script>";
			else
				echo "<script type'text/javascript'>alert('Something Went Wrong!!')</script>";
			
			header('location:admin_agents.php');
		}

	}

?>