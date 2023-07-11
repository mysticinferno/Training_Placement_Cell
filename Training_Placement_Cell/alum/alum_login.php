<?php
session_start();

// Check if user is logged in
if (isset($_SESSION['alum_id'])) {
  header("Location: welcome.php");
  exit();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>User Login</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			background-color: #f2f2f2;
		}
		
		form {
			display: flex;
			flex-direction: column;
			max-width: 500px;
			margin: auto;
			border: 2px solid #ccc;
			padding: 20px;
			background-color: #fff;
			box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
		}
		
		h2 {
			text-align: center;
			color: #333;
			margin-top: 50px;
			margin-bottom: 30px;
		}
		
		label {
			margin-bottom: 10px;
			font-weight: bold;
			color: #666;
		}
		
		input[type="email"],
		input[type="password"] {
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 5px;
			font-size: 16px;
			margin-bottom: 20px;
			background-color: #f2f2f2;
		}
		
		input[type="submit"] {
			background-color: #4CAF50;
			color: white;
			padding: 12px 20px;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			font-size: 16px;
			transition: background-color 0.3s ease;
		}
		
		input[type="submit"]:hover {
			background-color: #45a049;
		}
		
		.register-btn {
	background-color: #008CBA;
	color: white;
	padding: 12px 20px;
	border: none;
	border-radius: 4px;
	cursor: pointer;
	font-size: 16px;
	transition: background-color 0.3s ease;
	float: right;
	margin-left: 10px;
}

.register-btn:hover {
	background-color: #006f8a;
}

</style>
</head>
<body>
	<h2>Alumni Login</h2>
	<form action="alum_login_main.php" method="post">
  		<label for="email">Email:</label>
  		<input type="email" id="email" name="email" required>
  		<label for="password">Password:</label>
  		<input type="password" id="password" name="password" required><br><br>
		<input type="submit" value="Login">
	</form>
  <form action="alum_registration.html">
	<button class="register-btn" id="register-btn">Register</button>
  </form>
</body>
</html>
