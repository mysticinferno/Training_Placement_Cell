<?php
require_once 'db_config.php';

// Check connection
if ($db_conn->connect_error) {
	die("Connection failed: " . $db_conn->connect_error);
}

$email = test_input($_POST["email"]);
$id = $_SESSION['user_id'];
$cpi = test_input($_POST["cpi"]);
$old_password = $_POST["old_password"];
$new_password = $_POST["new_password"];
$confirm_password = $_POST["confirm_password"];

// Check if email exists in "users" table
$sql = "SELECT * FROM student_auth WHERE email='$email'";
$result = $db_conn->query($sql);

if ($result->num_rows > 0) {
	// Email exists, retrieve user info
	$row = $result->fetch_assoc();
	$password_hash = $row["password"];

	// Verify old password
	if (password_verify($old_password, $password_hash)) {
		// Check if new password matches confirm password
		if (!empty($new_password)) {
			if ($new_password == $confirm_password) {
				$new_password_hash = password_hash($new_password, PASSWORD_DEFAULT);
				$sql = "UPDATE student_database SET cpi='$cpi', password='$new_password_hash' WHERE id='$id'";
			} else {
				echo "New password does not match confirm password";
				exit();
			}
		} else {
			$sql = "UPDATE student_database SET cpi='$cpi' WHERE id='$id'";
		}

		if ($db_conn->query($sql) === TRUE) {
			echo "Information updated successfully";
		} else {
			echo "Error updating information: " . $db_conn->error;
		}
	} else {
		echo "Incorrect old password";
	}
} else {
	// Email does not exist, show error message
	echo "Email does not exist";
}

$db_conn->close();

// Function to validate user input data
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>
