<?php
require_once 'db_config.php';

$errors = array();

// Check connection
if ($db_conn->connect_error) {
    die("Connection failed: " . $db_conn->connect_error);
}

// Get form data and validate
$fname = test_input($_POST["fname"]);
$email = test_input($_POST["email"]);
$password = test_input($_POST["password"]);
$confirm_password = test_input($_POST["confirm_password"]);
$rollno = test_input($_POST["rollno"]);
$qualification = test_input($_POST["qualification"]);
$role = test_input($_POST["role"]);
$graduation_year = test_input($_POST["graduation_year"]);

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format";
}

// Validate password strength
if (strlen($password) < 8) {
    $errors[] = "Password must be at least 8 characters long";
}

// Validate password and confirm_password match
if ($password !== $confirm_password) {
    $errors[] = "Passwords do not match";
}

// Check if email already exists in "alum_auth" table
$sql = "SELECT email FROM alum_auth WHERE email='$email'";
$result = $db_conn->query($sql);

if ($result->num_rows > 0) {
    $errors[] = "Email already in use";
}

// If there are any errors, display them and do not proceed with registration
if (!empty($errors)) {
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>" . $error . "</li>";
    }
    echo "</ul>";
    die();
}

// Hash password
$password = password_hash($password, PASSWORD_DEFAULT);

// Insert data into "alum_auth" table
$sql = "INSERT INTO alum_auth (email, password) VALUES ('$email', '$password')";

if ($db_conn->query($sql) !== TRUE) {
    die("Error: " . $sql . "<br>" . $db_conn->error);
}

// Get the ID of the newly inserted row
$alum_id = $db_conn-> insert_id;

// Insert data into "alum_database" table
$sql = "INSERT INTO alum_database (id, name, rollno, qualification, graduation_year) 
        VALUES ('$alum_id', '$fname', '$rollno', '$qualification', '$graduation_year')";

if ($db_conn->query($sql) === TRUE) {
    // Show success message and login button
    echo "<div class='success-message'>Account created successfully!</div>";
    echo "<a class='login-button' href='alum_login_main.php'>Go to Login</a>";
} else {
    echo "Error: " . $sql . "<br>" . $db_conn->error;
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
