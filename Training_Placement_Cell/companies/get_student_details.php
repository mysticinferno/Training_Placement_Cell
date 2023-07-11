<?php
session_start();

require_once 'db_config.php';

if (!$db_conn) {
  die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
  $id = mysqli_real_escape_string($db_conn, $_GET['id']);
  $result = mysqli_query($db_conn, "SELECT * FROM student_database WHERE id='$id'");

  if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $rollno = $row['rollno'];
    $cpi = $row['cpi'];
    $graduation_year = $row['graduation_year'];
    $email_id = $_SESSION['email_id'];

    echo "<h2>Student Details</h2>";
    echo "<p><strong>Name:</strong> $name</p>";
    echo "<p><strong>Roll No.:</strong> $rollno</p>";
    echo "<p><strong>CPI:</strong> $cpi</p>";
    echo "<p><strong>Graduation Year:</strong> $graduation_year</p>";

    // Show marks
    echo "<h2>Marks</h2>";
    echo "<table>";
    echo "<tr><th>Class</th><th>Marks</th><th>PDF</th></tr>";

    $marks_query = "SELECT * FROM student_marks WHERE id='$id'";
    $marks_result = mysqli_query($db_conn, $marks_query);

    if (mysqli_num_rows($marks_result) > 0) {
      while ($marks_row = mysqli_fetch_assoc($marks_result)) {
        $class = $marks_row['class'];
        $marks = $marks_row['marks'];
        $script = $marks_row['script'];

        echo "<tr>";
        echo "<td>$class</td>";
        echo "<td>$marks</td>";

        if (!empty($script)) {
          $file_path = "/uploads/$email_id/$script";
          echo "<td><a href='$file_path'>Download</a></td>";
        } else {
          echo "<td>N/A</td>";
        }

        echo "</tr>";
      }
    } else {
      echo "<tr><td colspan='3'>No marks found.</td></tr>";
    }

    echo "</table>";
  } else {
    echo "<p>Invalid ID</p>";
  }
} else {
  echo "<p>No ID provided</p>";
}
?>
