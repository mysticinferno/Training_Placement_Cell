<?php
session_start();
 
if (isset($_POST['email']) && isset($_POST['password'])) {
  // Retrieve the form data
  $email = $_POST['email'];
  $password = $_POST['password'];
 
  // Connect to the MySQL database
  require_once 'db_config.php';
 
  // Check if the email exists in the users table
  $sql = "SELECT * FROM alum_auth WHERE email=?";
  $stmt = mysqli_prepare($db_conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
 
  if (mysqli_num_rows($result) == 1) {
    // Retrieve the user's password hash
    $user = mysqli_fetch_assoc($result);
    $hash = $user['password'];
 
    // Verify the password
    if (password_verify($password, $hash)) {
      // Set session variables for the user
      $_SESSION['alum_id'] = $user['id'];
      $_SESSION['alum_email'] = $user['email'];
      // Generate a random token

      // Redirect to the welcome page
      header("Location: welcome.php");
      exit();
    } else {
      // Display error message for incorrect password
      echo "Incorrect password. Please try again.";
    }
  } else {
    // Display error message for invalid email
    echo "Invalid email. Please try again.";
  }
 
  // Close the database connection
  mysqli_close($db_conn);
}

?>