<?php
// Establish database connection
require_once 'db_config.php';

// Check connection
if (!$db_conn) {
  die("Connection failed: " . mysqli_connect_error());
}

// Get form data
$email = $_POST['email'];
$company_name = $_POST['company_name'];
$company_year = $_POST['placement-since'];
$first_password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Check if passwords match
if ($first_password != $confirm_password) {
  die("Error: Passwords do not match.");
}

// Check if email already exists in database
$email_check_query = "SELECT * FROM company_auth WHERE email='$email' LIMIT 1";
$result = mysqli_query($db_conn, $email_check_query);
$user = mysqli_fetch_assoc($result);

if ($user) { // if user exists
  if ($user['email'] == $email) {
    die("Error: Email already exists.");
  }
}

// Hash password
$hashed_password = password_hash($first_password, PASSWORD_DEFAULT);

// Insert values into company_authentication table
$insert_auth_query = "INSERT INTO company_auth (email, password) VALUES ('$email', '$hashed_password')";
mysqli_query($db_conn, $insert_auth_query);

// Get company_authentication id
$id_query = "SELECT id FROM company_auth WHERE email='$email'";
$result = mysqli_query($db_conn, $id_query);
$row = mysqli_fetch_assoc($result);
$auth_id = $row['id'];

// Insert values into company_database table
$insert_db_query = "INSERT INTO company_database (id, name,since_year) VALUES ('$auth_id', '$company_name', '$company_year')";
mysqli_query($db_conn, $insert_db_query);

// Close database connection
mysqli_close($db_conn);

echo "Registration successful!";
echo "<a class='login-button' href='company_login_ui.php'>go to Login</a>";

?>
