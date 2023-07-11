<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login_main.php");
  exit();
}

// Retrieve user data from session
$user_email = $_SESSION['user_email'];
$user_id = $_SESSION['user_id'];

	// Display confirmation message
	echo "<p>Are you sure you want to delete your account?</p>";
	echo "<form action='delete.php' method='post'>";
	echo "<input type='submit' name='confirm_delete' value='Yes'>";
	echo "<input type='submit' name='cancel_delete' value='No'>";
	echo "</form>";
  if (isset($_POST['confirm_delete'])) {
	// Delete user data from "users" table
	require_once 'db_config.php';

	// Check connection
	if ($db_conn->connect_error) {
		die("Connection failed: " . $db_conn->connect_error);
	}

	$sql = "DELETE from student_database where id = '$user_id'";
 if ($db_conn->query($sql) == TRUE) {
		// Delete user session data and redirect to login page
		session_unset();
		session_destroy();
		echo "<div class='success-message'>Account deletion Successful!</div>";
        echo "<a class='login-button' href='login_main.php'>Login</a>";
		exit();
	} else {
		echo "Error deleting account: " . $db_conn->error;
	}

	$db_conn->close();
} elseif (isset($_POST['cancel_delete'])) {
	// Redirect to welcome page
	header("Location: welcome.php");
	exit();
}
?>
