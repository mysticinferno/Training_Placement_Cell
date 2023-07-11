<?php
session_start();
require_once 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Validate form data
  $company_name = $_POST['company_name'];
  if (empty($company_name)) {
    die("Error: company_name is required.");
  }

  // If not "other" company, get data from database
  if ($company_name !== 'other') {
    $rid = (int) $company_name;
    $sql = "SELECT cr.id, cr.year, cr.salary, cr.role, cd.name FROM company_roles cr
            INNER JOIN company_database cd ON cd.id = cr.id
            WHERE cr.rid = $rid;";
    $result = $db_conn->query($sql);
    if (!$result) {
      // Query failed
      die("Error: " . $sql . "<br>" . $db_conn->error);
    } else if ($result->num_rows > 0) {
      // Data found for the selected company
      $row = $result->fetch_assoc();
      $role = $row['role'];
      $salary = $row['salary'];
      $company_name = $row['name'];
      $id = $_SESSION['alum_id'];
      $year = $_SESSION['year'];


      $sql = "INSERT INTO alum_placed (id, company_name,year, salary, role) VALUES ($id, '$company_name', '$salary','$year', '$role');";
      if ($db_conn->query($sql) === true) {
        echo "Data inserted successfully.";
      } else {
        // Query failed
        echo "Error: " . $sql . "<br>" . $db_conn->error;
      }
    } else {
      // No data found for the selected company
      echo "No data found for the selected company.";
    }
  } else {
    // If "other" company, insert data into database
    $id = $_SESSION['alum_id'];
    $company_name = $_POST['company'];
    $salary = (float) $_POST['salary'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $role = $_POST['role'];

    // Validate form data
    if (empty($company_name) || empty($salary) || empty($start_date)|| empty($role)) {
      die("Error: all fields are required.");
    }

    $sql = "INSERT INTO alum_placed (id, company_name, salary, start_date, role) VALUES ($id, '$company_name', $salary, '$start_date', '$role');";
    if ($db_conn->query($sql) === true) {
      echo "Data inserted successfully.";
    } else {
      // Query failed
      echo "Error: " . $sql . "<br>" . $db_conn->error;
    }
  }
} else {
  // If script is executed directly, redirect user
//   header('Location: index.html');
  exit();
}

$db_conn->close();
?>
