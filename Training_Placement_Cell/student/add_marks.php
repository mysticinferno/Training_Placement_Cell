<?php
require_once 'db_config.php';

// Check connection
if ($db_conn->connect_error) {
  die("Connection failed: " . $db_conn->connect_error);
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
  // Prepare SQL statement
  $sql = "INSERT INTO student_marks (id, class, marks, script) VALUES (?, ?, ?, ?)";

  // Prepare statement object
  $stmt = $db_conn->prepare($sql);

  if ($stmt === false) {
    echo "Error preparing statement: " . $db_conn->error;
    exit(); 
  }

  // Bind parameters
  $stmt->bind_param("issb", $id, $class, $marks, $script);

  // Set parameters and execute statement for each submitted mark
  foreach ($_POST['class'] as $key => $value) {
    $id = $_SESSION['user_id'];
    $class = $_POST['class'][$key];
    $marks = $_POST['marks'][$key];
    $script = '';

    // Upload file if it exists
    // if ($_FILES['file']['error'][$key] === UPLOAD_ERR_OK) {
      $filename = $_FILES['file']['name'][$key];
      $tempname = $_FILES['file']['tmp_name'][$key];
      $folder = "uploads/" . $_SESSION['user_email'] . "/";
      $fileType = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
      
      // Check if file is of PDF type
      if ($fileType !== "pdf") {
        echo "Error: File is not of PDF type.";
        exit();
      }

      // Create directory if it doesn't exist
      if (!file_exists($folder)) {
        if (!mkdir($folder, 0777, true)) {
          echo "Error creating directory: " . $folder;
          exit();
        }
        chmod($folder, 0777);
      }

      if (!move_uploaded_file($tempname, $folder.$filename)) {
        echo "Error uploading file: " . $_FILES['file']['error'][$key];
        exit();
      }
      $script = $filename;
    // }

    // Execute statement
    if (!$stmt->execute()) {
      echo "Error executing statement: " . $stmt->error;
      exit();
    }
  }

  // Close statement object and database connection
  $stmt->close();
  $db_conn->close();
  echo "Marks submitted successfully!";
} else {
  echo "Error: Invalid request method.";
}
exit();
?>
