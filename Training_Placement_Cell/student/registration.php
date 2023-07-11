<?php
require_once 'db_config.php';

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
$cpi = test_input($_POST["cpi"]);
$qualification = test_input($_POST["qualification"]);
$role = test_input($_POST["role"]);
$graduation_year = test_input($_POST["graduation_year"]);
$worked_in_company = isset($_POST["worked_in_company"]) ? 1 : 0;
$company_name = isset($_POST["company_name"]) ? test_input($_POST["company_name"]) : "";
$salary_in_company = isset($_POST["salary_in_company"]) ? test_input($_POST["salary_in_company"]) : '0';

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("Invalid email format");
}

// Validate password strength
if (strlen($password) < 8) {
    die("Password must be at least 8 characters long");
}

// Validate password and confirm_password match
if ($password !== $confirm_password) {
    die("Passwords do not match");
}

// Hash password
$password = password_hash($password, PASSWORD_DEFAULT);

// Check if email already exists in "student_database" table
$sql = "SELECT email FROM student_auth WHERE email='$email'";
$result = $db_conn->query($sql);

if ($result->num_rows > 0) {
    die("Email already in use");
}

// Insert data into "student_authentication" table
$sql = "INSERT INTO student_auth (email, password) VALUES ('$email', '$password')";

if ($db_conn->query($sql) !== TRUE) {
    die("Error: " . $sql . "<br>" . $db_conn->error);
}

// Get the ID of the newly inserted row
$student_id = $db_conn-> insert_id;

// Insert data into "student_database" table
$sql = "INSERT INTO student_database (id, name, rollno, cpi, qualification, graduation_year) 
        VALUES ('$student_id', '$fname', '$rollno', '$cpi', '$qualification', '$graduation_year')";

if ($db_conn->query($sql) === TRUE) {
    // Show success message and login button
    echo "<div class='success-message'>Account created successfully!</div>";
    echo "<a class='login-button' href='login_main.php'>Go to Login</a>";
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
