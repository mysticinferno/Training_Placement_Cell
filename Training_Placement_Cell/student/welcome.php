<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
  echo "<p>You are not logged in. Please <a href='login_main.php'>log in</a> to access this page.</p>";
  exit();
}


  // Connect to the database
require_once 'db_config.php';

// Retrieve user ID from session
$user_id = $_SESSION['user_id'];

// Retrieve student details
$sql = "SELECT * FROM student_database WHERE id='$user_id'";
$result = $db_conn->query($sql);

if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
  $name = $row['name'];
  $rollno = $row['rollno'];
  $cpi = $row['cpi'];
  $graduation_year = $row['graduation_year'];
  $qualification = $row['qualification'];
} else {
  echo "No data found";
}

// Retrieve student preferences
$sql = "SELECT role FROM student_pref WHERE id='$user_id'";
$result = $db_conn->query($sql);

if ($result->num_rows > 0) {
  $roles = array();
  while ($row = $result->fetch_assoc()) {
    $roles[] = $row['role'];
  }
  $preferences = implode(", ", $roles);
} else {
  $preferences = "No preferences found";
}

// Retrieve student marks
$sql = "SELECT * FROM student_marks WHERE id='$user_id'";
$result = $db_conn->query($sql);

if ($result->num_rows > 0) {
  $marks = array();
  while ($row = $result->fetch_assoc()) {
    $class = $row['class'];
    $mark = $row['marks'];
    $marks[$class] = $mark;
  }
} else {
  $marks = "No marks found";
}

// Close connection
$db_conn->close();

// Retrieve user data from session
$user_email = $_SESSION['user_email'];
?>

<!DOCTYPE html>
<html>
<head>
	<title>Welcome</title>
	<link rel="stylesheet" type="text/css" href="main_page.css">
</head>
<body>
<header>
		<div class="header-left">
			<button id="add-marks-btn">Add Marks</button>
			<button id="update-info-btn">Update Info</button>
			<button id="add-pref-btn">Add Preference</button>
		</div>
		<div class="header-right">
			<div class="dropdown">
				<button class="dropbtn">other options</button>
				<div class="dropdown-content">
					<button onclick="deleteAccount()" class="delete-btn">Delete Account</button>
					<button onclick="location.href='logout.php';" class="logout-btn">Logout</button>
				</div>
			</div>
		</div>
	</header>

  <form method="POST" style = "display:none" action="add_pref.php" id = "add-pref-form">
  <br>
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
  <br>
  <input type="submit" value="Submit">
</form>


	<form action="update.php" method="POST" style = "display:none" id= "update-info-form">
		<label for="email">Email:</label>
		<input type="email" id="email" name="email" value="<?php echo $user_email; ?>" readonly><br>
		<label for="fname">Name:</label>
		<input type="text" id="fname" name="fname" value="<?php echo $name; ?>" readonly><br>
		<label for="rollno">Roll No:</label>
		<input type="text" id="rollno" name="rollno" value="<?php echo $rollno; ?>" readonly required>
		<label for="cpi" >CPI:</label>
		<input type="number" id="cpi" name="cpi" min="0" step="0.01" value="<?php echo $cpi; ?>" required>
		<label for="qualification">Qualification:</label>
		<select id="qualification" name="qualification" value="<?php echo $qualification; ?>" readonly>
			<option value="btech">B.Tech</option>
			<option value="mtech">M.Tech</option>
			<option value="phd">PhD</option>
			<option value="others">Other</option>
		</select>
	
		</select>
		<label for="graduation_year">Graduation Year:</label>
		<input type="number" id="graduation_year" name="graduation_year" min="1900" readonly value="<?php echo $graduation_year; ?>" max="<?php echo date('Y'); ?>" required>
		
		<label for="old_password">Old Password:</label>
		<input type="password" id="old_password" name="old_password"><br>
		<label for="new_password">New Password:</label>
		<input type="password" id="new_password" name="new_password"><br>
		<label for="confirm_password">Confirm New Password:</label>
		<input type="password" id="confirm_password" name="confirm_password"><br>
		<input type="submit" value="Update">
	</form>

	<form id="marks-form" action="add_marks.php" method="POST" enctype="multipart/form-data" style="display:none">
	  <div id="marks-container">
	
	  </div>
	  <button type="button" id="add-mark-btn">Add Marks</button>
	  <button type="submit">Submit</button>
	</form>
	</div>

	<div id="profile">
  <h2><?php echo $name; ?></h2>
  <p><strong>Roll No:</strong> <?php echo $rollno; ?></p>
  <p><strong>CPI:</strong> <?php echo $cpi; ?></p>
  <p><strong>Graduation Year:</strong> <?php echo $graduation_year; ?></p>
  <p><strong>Qualification:</strong> <?php echo $qualification; ?></p>
  <p><strong>Preferences:</strong> <?php echo $preferences; ?></p>
  <p><strong>Marks:</strong></p>
  <table>
    <tr>
      <th>Class</th>
      <th>Marks</th>
    </tr>
    <?php foreach ($marks as $class => $mark) { ?>
      <tr>
        <td><?php echo $class; ?></td>
        <td><?php echo $mark; ?></td>
      </tr>
    <?php } ?>
  </table>
</div>

	<script>
	  const addMarksBtn = document.getElementById('add-marks-btn');
	  const marksForm = document.getElementById('marks-form');
	  const marksContainer = document.getElementById('marks-container');
	  const addMarkBtn = document.getElementById('add-mark-btn');
	  const updateInfoBtn = document.getElementById('update-info-btn');
	  const updateInfoForm = document.getElementById('update-info-form');
	  const profile = document.getElementById('profile');
	  const addPrefBtn = document.getElementById('add-pref-btn');	
	  const addPrefForm = document.getElementById('add-pref-form');

	  addPrefBtn.addEventListener('click', () => {
		addPrefForm.style.display = "block";
		profile.style.display = "none";
	    marksForm.style.display = "none";
	  })

	  updateInfoBtn.addEventListener('click', () => {
		addPrefForm.style.display = "none";
		updateInfoForm.style.display = "block";
		profile.style.display = "none";
	    marksForm.style.display = "none";
	  })

	  addMarksBtn.addEventListener('click', () => {
		addPrefForm.style.display = "none";
		updateInfoForm.style.display = "none";
	    marksForm.style.display = "block";
		profile.style.display = "none";
	  });

	  addMarkBtn.addEventListener('click', () => {
	    const newMark = document.createElement('div');
	    newMark.classList.add('mark');
	    newMark.innerHTML = `
	      <label for="class-input">Class</label>
	      <select class="class-input" name="class[]">
	        <option value="Class 10">Class 10</option>
	        <option value="Class 11">Class 11</option>
	        <option value="Class 12">Class 12</option>
	        <option value="1st Year">1st Year</option>
	        <option value="2nd Year">2nd Year</option>
	        <option value="3rd Year">3rd Year</option>
	      </select>
	      <label for="marks-input">Marks</label>
	      <input type="text" class="marks-input" name="marks[]">
	      <input type="file" class="file-upload" name="file[]">
	      <button type="button" class="delete-btn">Delete</button>
	    `;
	    marksContainer.appendChild(newMark);

	    const deleteBtn = newMark.querySelector('.delete-btn');
	    deleteBtn.addEventListener('click', () => {
	      newMark.remove();
	    });
	  });

	  const deleteBtns = document.querySelectorAll('.delete-btn');
	  deleteBtns.forEach((btn) => {
	    btn.addEventListener('click', (e) => {
	      e.target.closest('.mark').remove();
	    });
	  });

	  // Submit marks form when user clicks the main submit button
	  marksForm.addEventListener('submit', (event) => {
	    event.preventDefault(); // Prevent the form from submitting normally
	    marksForm.submit(); // Submit the form programmatically
	  });

	  function deleteAccount() {
     window.location.href = "delete.php";
      }
	  </script>
</body>
</html>
