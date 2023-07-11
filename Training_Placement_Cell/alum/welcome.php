<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['alum_id'])) {
  echo "<p>You are not logged in. Please <a href='alum_login.php'>log in</a> to access this page.</p>";
  exit();
}


  // Connect to the database
require_once 'db_config.php';

// Retrieve user ID from session
$alum_id = $_SESSION['alum_id'];

// Retrieve student details
$sql = "SELECT * FROM alum_database WHERE id='$alum_id'";
$result = $db_conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $name = $row['name'];
  $rollno = $row['rollno'];
  $cpi = $row['cpi'];
  $graduation_year = $row['graduation_year'];
  $qualification = $row['qualification'];
  $prev_email = $row['previous_email'];

} else {
  echo "No data found";
}

// // Retrieve student marks
// $sql = "SELECT * FROM student_marks WHERE id='$user_id'";
// $result = $db_conn->query($sql);

// if ($result->num_rows > 0) {
//   $marks = array();
//   while ($row = $result->fetch_assoc()) {
//     $class = $row['class'];
//     $mark = $row['marks'];
//     $marks[$class] = $mark;
//   }
// } else {
//   $marks = "No marks found";
// }

// Close connection
// $db_conn->close();

// Retrieve user data from session
$user_email = $_SESSION['alum_email'];
?>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome Alumni</title>
	<link rel="stylesheet" type="text/css" href="main_page.css">
</head>
<body>
<header>
		<div class="header-left">   
			<button id="add-placed-btn">Add carrer</button>
		</div>
		<div class="header-right">
			<div class="dropdown">
				<button class="dropbtn">other options</button>
				<div class="dropdown-content">
					<button onclick="location.href='logout.php';" class="logout-btn">Logout</button>
				</div>
			</div>
		</div>
	</header>

<div id = "add_carrer" style = "display:none">
	<form method="post" action="placement_adder.php">
  <label for="company_name">Company Name:</label>
  <select id="company_name" name="company_name">
    <?php
    require_once 'db_config.php';

      $sql = "SELECT cr.rid, cd.name, cr.role
      FROM company_database cd
      INNER JOIN company_roles cr ON cd.id = cr.id;
      ";
      $result = $db_conn->query($sql);

      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo '<option value="' . $row["rid"] . '">' . $row["name"] . " (" . $row["role"] . " )" . '</option>';
        }
      }
    ?>
    <option value="other">Other</option>
  </select>

  <div id="other_options" style="display:none;">
  <label for="company_name">Company Name:</label>
  <input type="test" id="company" name="company">

    <label for="salary">Salary:(LPA)</label>
    <input type="text" id="salary" name="salary">

    <label for="start_date">Start Date:</label>
    <input type="date" id="start_date" name="start_date">

    <label for="end_date">End Date:</label>
    <input type="date" id="end_date" name="end_date">

    <label for="role">Select a Role:</label>
  <select name="role" id="role">
    <option value="SDE">SDE</option>
    <option value="ML ENGINEER">ML ENGINEER</option>
    <option value="RESEARCH">RESEARCH</option>
    <option value="DATA SCIENTIST">DATA SCIENTIST</option>
    <option value="ANALYST">ANALYST</option>
    <option value="CONSULTANT">CONSULTANT</option>
    <option value="HR">HR</option>
    <option value="CORE">CORE</option>
    <option value="OTHERS">OTHERS</option>
  </select>
    
  </div>
  <button type="submit">Submit</button>
</form>
</div>

<div id="profile">
  <h2><?php echo $name; ?></h2>
  <p><strong>Roll No:</strong> <?php echo $rollno; ?></p>
  <p><strong>CPI:</strong> <?php echo $cpi; ?></p>
  <p><strong>Graduation Year:</strong> <?php echo $graduation_year; ?></p>
  <p><strong>Qualification:</strong> <?php echo $qualification; ?></p>

  <h2>Placements</h2>
  <?php

  require_once 'db_config.php';
$alum_id = $_SESSION['alum_id'];

// Retrieve student details
$sql = "SELECT * FROM alum_database WHERE id='$alum_id'";
$result = $db_conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $name = $row['name'];
  $rollno = $row['rollno'];
  $cpi = $row['cpi'];
  $graduation_year = $row['graduation_year'];
  $qualification = $row['qualification'];
  $prev_email = $row['previous_email'];

  // Retrieve current companies enrolled
  $sql = "SELECT * FROM alum_placed WHERE id='$alum_id'";
  $result = $db_conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Company Name</th><th>Start Date</th><th>End Date</th><th>Role</th><th>Salary</th></tr>";
    while ($row = $result->fetch_assoc()) {
      echo "<tr>";
      echo "<td>" . $row["company_name"] . "</td>";
      echo "<td>" . $row["start_date"] . "</td>";
      echo "<td>" . $row["end_date"] . "</td>";
      echo "<td>" . $row["role"] . "</td>";
      echo "<td>" . $row["salary"] . "</td>";
      echo "</tr>";
    }
    echo "</table>";
  } else {
    echo "<p>No companies enrolled.</p>";
  }

} else {
  echo "No data found";
}

?>

</div>
    <script>
  // show the "Other" options when the user selects "Other"
  var companyDropdown = document.getElementById("company_name");
  var otherOptionsDiv = document.getElementById("other_options");
  var addCarrerBtn = document.getElementById("add-placed-btn");
  var addCarrerForm = document.getElementById("add_carrer");
  var profile = document.getElementById("profile");

  addCarrerBtn.addEventListener('click', () => {
		addCarrerForm.style.display = "block";
		profile.style.display = "none";
	})

  companyDropdown.addEventListener("change", function() {
    if (companyDropdown.value === "other") {
      otherOptionsDiv.style.display = "block";
    } else {
      otherOptionsDiv.style.display = "none";
    }
  });
</script>
</body>
</html>
