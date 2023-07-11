<?php
session_start();
if (!isset($_SESSION['company_id'])) {
    header("Location: company_login_ui.php");
    exit();
}

// database connection parameters
require_once 'db_config.php';

// check connection
if (!$db_conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// retrieve form data using POST method
$min_graduation = $_POST["min-graduation"];
$min_cpi = $_POST["min-cpi"];
$interview_process = $_POST["interview-mode"];
$max_salary = $_POST["salary"];
$contact_person_name = $_POST["contact-name"];
$contact_person_email = $_POST["contact-email"];
$role = $_POST["role"];

$company_id = $_SESSION["company_id"];

// check if any field is empty
if ( empty($min_graduation) || empty($min_cpi) || empty($interview_process) || empty($max_salary)|| empty($contact_person_name) || empty($contact_person_email) || empty($role)) {
    die("Error: All fields are required.");
}

// check if company exists in the table
$sql = "SELECT * FROM company_database WHERE id = '$company_id'";
$result = mysqli_query($db_conn, $sql);

if (mysqli_num_rows($result) > 0) {

    // update company data
    $sql = "INSERT INTO company_roles(id, contact_phone, contact_email, interview_mode, min_cpi, qualification, salary, role) values ('$company_id', '$contact_person_name', '$contact_person_email', '$interview_process', '$min_cpi', '$min_graduation', '$max_salary', '$role')";
    
    if (mysqli_query($db_conn, $sql)) {      
        header('Location: company_webpage.php');
    } else {
        echo "Error updating record: " . mysqli_error($db_conn);
    }
} else {
    echo "Company not found.";
}

mysqli_close($db_conn);
?>