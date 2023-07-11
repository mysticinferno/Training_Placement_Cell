<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login_main.php");
  exit();
}

// Retrieve user data from session
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['user_email'];

require_once 'db_config.php';

// Retrieve student data based on user ID
$sql = "SELECT name, rollno, cpi, qualification, graduation_year FROM student_database WHERE id = $user_id";
$result = $db_conn->query($sql);

if ($result->num_rows > 0) {
  // Store student data in variables
  while($row = $result->fetch_assoc()) {
    $name = $row["name"];
    $rollno = $row["rollno"];
    $cpi = $row["cpi"];
    $qualification = $row["qualification"];
    $graduation_year = $row["graduation_year"];
  }
} else {
  echo "No student data found for user ID: $user_id";
}

// Close the database connection
$db_conn->close();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Update Information</title>
</head>
<body>
<!-- <div class="forms-container"> -->
	
</body>
</html>