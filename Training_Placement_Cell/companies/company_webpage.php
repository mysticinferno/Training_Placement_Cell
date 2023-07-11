<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Company Details</title>
    <link rel="stylesheet" href="main_page.css">
  </head>
  <body>
  
<!-- header section -->
<header>
  <?php
session_start();

  if (!isset($_SESSION['company_id'])) {
    header("Location: company_login_ui.php");
    exit();
  }

    // Connect to database
    require_once 'db_config.php';

    if (!$db_conn) {
      die("Connection failed: " . mysqli_connect_error());
    }

    // Get the company name from the company_database table using the company_email stored in session
    $company_email = $_SESSION['company_email'];
    $company_id = $_SESSION['company_id'];
    $result = mysqli_query($db_conn, "SELECT name FROM company_database WHERE id='$company_id'");
    $row = mysqli_fetch_assoc($result);
    $company_name = $row['name'];
    
    // Close database connection
    
    // Show the company name in the header
    echo "<span>".$company_name."</span>";
  ?>
  <div>

  <div class="header-right">
			<div class="dropdown">
				<button class="dropbtn">other options</button>
				<div class="dropdown-content">
        <button onclick="window.location.href='company_proposal.php'">New proposal</button>
        <button onclick="window.location.href='all_students.php'">All students</button>
        <button onclick="window.location.href='all_proposals.php'">All Proposals</button>
        <button onclick="window.location.href='logout.php'">Logout</button>
				</div>
			</div>
		</div>


    <form action="logout.php" method="post">
		<input type="submit" name="logout" value="Logout">
	  </form>
  </div>
</header>

<?php
  session_start();

  require_once 'db_config.php';

  if (!$db_conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
  
  echo "<h2>Eligible students</h2>";
  $roles = mysqli_query($db_conn, "SELECT DISTINCT role FROM company_roles");

  while ($role_row = mysqli_fetch_assoc($roles)) {
    $role = $role_row['role'];
    echo "<h3>$role</h3>";

    $result = mysqli_query($db_conn, "SELECT s.id, s.name, s.rollno, s.cpi, s.graduation_year 
    FROM student_database s, student_pref sp, company_roles cr
    WHERE s.cpi >= (SELECT MIN(min_cpi) FROM company_roles WHERE role = '$role')
    AND s.qualification IN (SELECT qualification FROM company_roles WHERE role = '$role')
    AND sp.id = s.id AND sp.role = '$role'
    GROUP BY s.id");

    echo "<table id='studentsTable'>";
    echo "<tr><th>S.No.</th><th>Name</th><th>Roll No.</th><th>CPI</th><th>Graduation Year</th></tr>";
    $serial_num = 1;
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr onclick='showStudentDetails(".$row['id'].")'>";
      echo "<td>".$serial_num."</td>";
      echo "<td>".$row['name']."</td>";
      echo "<td>".$row['rollno']."</td>";
      echo "<td>".$row['cpi']."</td>";
      echo "<td>".$row['graduation_year']."</td>";
      echo "</tr>";
      $serial_num++;
    }
    echo "</table>";

    echo "<div id='studentDetails' style='display:none'></div>";
  }

  mysqli_close($db_conn);
?>

<script>
  function showStudentDetails(id) {
    // Hide the table
    document.getElementById('studentsTable').style.display = 'none';

    // Show the student details
    var studentDetailsDiv = document.getElementById('studentDetails');
    studentDetailsDiv.style.display = 'block';
    studentDetailsDiv.innerHTML = 'Loading...';

    // Fetch the student's details using AJAX
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        studentDetailsDiv.innerHTML = this.responseText;
      }
    };
    xmlhttp.open('GET', 'get_student_details.php?id=' + id, true);
    xmlhttp.send();
  }
</script>
</body>
</html>
