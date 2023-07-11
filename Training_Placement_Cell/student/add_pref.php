<?php
session_start();
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Get the form data
  $id = $_SESSION['user_id'];
  $role = $_POST["role"];

require_once 'db_config.php';

  // Check connection
  if ($db_conn->connect_error) {
    die("Connection failed: " . $db_conn->connect_error);
  }

  // Prepare and execute the SQL statement
  $stmt = $db_conn->prepare("INSERT INTO student_pref (id, role) VALUES (?, ?)");
  $stmt->bind_param("is", $id, $role);
  $stmt->execute();

  // Close the database connection
  $stmt->close();
  $db_conn->close();

  // Redirect the user back to the form
  header("Location: welcome.php");
  exit();
}else{
    echo "incorrect request";
}
?>