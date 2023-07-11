<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['alum_id'])) {
    header("Location: alum_login.php");
    exit();
}

// Check if the user has clicked the logout button
	// Display confirmation message
	echo "<p>Are you sure you want to logout your account?</p>";
	echo "<form action='logout.php' method='post'>";
	echo "<input type='submit' name='confirm_logout' value='Yes'>";
	echo "<input type='submit' name='cancel_logout' value='No'>";
	echo "</form>";
if (isset($_POST['confirm_logout'])) {
		// Delete user session data and redirect to login page
		session_unset();
		session_destroy();
		echo "<div class='success-message'>logout success!</div>";
        echo "<a class='login-button' href='alum_login.php'>Login</a>";
		exit();

	$db_conn->close();
} elseif (isset($_POST['cancel_logout'])) {
	// Redirect to welcome page
	header("Location: welcome.php");
	exit();
}
?>
