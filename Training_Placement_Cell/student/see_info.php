<!DOCTYPE html>
<html>
<head>
	<title>User Information</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f2f2f2;
			text-align: center;
		}
		h1 {
			color: #333;
			margin-top: 50px;
			margin-bottom: 30px;
		}
		table {
			border-collapse: collapse;
			width: 50%;
			margin: auto;
			background-color: #fff;
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
		}
		th, td {
			padding: 15px;
			text-align: left;
			border-bottom: 1px solid #ddd;
		}
		th {
			background-color: #4CAF50;
			color: white;
		}
		tr:hover {
			background-color: #f5f5f5;
		}
	</style>
</head>
<body>
	<h1>User Information</h1>
	<table>
		<tr>
			<th>Field</th>
			<th>Value</th>
		</tr>
		<?php
		session_start();

		// Check if user is logged in
		if (!isset($_SESSION['user_id'])) {
		  header("Location: login_main.php");
		  exit();
		}

		// Retrieve user data from session
		$user_email = $_SESSION['user_email'];

		require_once 'db_config.php';

		// Check connection
		if ($db_conn->connect_error) {
		  die("Connection failed: " . $db_conn->connect_error);
		}

		// Retrieve user information from the database
		$sql = "SELECT * FROM users WHERE email='$user_email'";
		$result = $db_conn->query($sql);

		if ($result->num_rows > 0) {
		  // Output data of each row
		  while($row = $result->fetch_assoc()) {
		    echo "<tr><td>First Name:</td><td>" . $row["first_name"]. "</td></tr>";
		    echo "<tr><td>Last Name:</td><td>" . $row["last_name"]. "</td></tr>";
		    echo "<tr><td>Email:</td><td>" . $row["email"]. "</td></tr>";
		  }
		} else {
		  echo "<tr><td colspan='2'>User information not found</td></tr>";
		}

		$db_conn->close();
		?>
	</table>
</body>
</html>
