<!DOCTYPE html>
<html>
<head>
	<title>Placements</title>
	<link rel="stylesheet" href="main_page.css">
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
	<header>
		<img src="logo.png" alt="Logo">
		<nav>
			<a href="/student/login_main.php">Student Login</a>
			<a href="/alum/alum_login.php">Alumni Login</a>
			<a href="/companies/company_login_ui.php">Company Login</a>
		</nav>
	</header>
	
	<main>
    <h1>Placement Graph</h1>
	<form>
		<label for="year">Select a year:</label>
		<select id="year" name="year">
			<?php
			// Create options for the last 3 years
			for ($i = 0; $i < 3; $i++) {
				$year = date("Y") - $i;
				echo "<option value='$year'>$year</option>";
			}
			?>
		</select>
		<input type="submit" value="Update">
	</form>
	<?php
	// Connect to the database
	require_once 'db_config.php';

	// Check connection
	if ($db_conn->connect_error) {
		die("Connection failed: " . $db_conn->connect_error);
	}

	// Get the selected year from the form
	$selected_year = $_GET["year"];

	// Query to fetch data for the selected year
	$sql = "SELECT alum_placed.company_name, COUNT(*) as count FROM alum_database JOIN alum_placed ON alum_database.id = alum_placed.id WHERE YEAR(alum_placed.start_date) = $selected_year GROUP BY alum_placed.company_name";

	$result = $db_conn->query($sql);

	// Initialize arrays to store data
	$company_names = array();
	$counts = array();

	if ($result->num_rows > 0) {
		// Fetch data and store in arrays
		while($row = $result->fetch_assoc()) {
			array_push($company_names, $row["company_name"]);
			array_push($counts, $row["count"]);
		}
	} else {
		echo "No results";
	}
	?>

	<!-- Display the graph using JavaScript -->
	<div id="chart-container">
		<canvas id="myChart"></canvas>
		<canvas id="topChart"></canvas>
	</div>

	<script>
		var ctx = document.getElementById('myChart').getContext('2d');

		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: <?php echo json_encode($company_names); ?>,
				datasets: [{
					label: '# of Placements',
					data: <?php echo json_encode($counts); ?>,
					backgroundColor: 'rgba(255, 99, 132, 0.2)',
					borderColor: 'rgba(255, 99, 132, 1)',
					borderWidth: 1
				}]
			},
			options: {
				scales: {
					y: {
						beginAtZero: true
					}
				},
				plugins: {
					title: {
						display: true,
						text: 'Placement Graph for <?php echo $selected_year; ?>'
					}
				}
			}
		});

		var topChart = document.getElementById('topChart').getContext('2d');

var topCompanies = <?php 
session_start();
require_once 'db_config.php';

    $sql_top = "SELECT alum_placed.company_name, COUNT(*) as count FROM alum_database JOIN alum_placed ON alum_database.id = alum_placed.id WHERE YEAR(alum_placed.start_date) = $selected_year GROUP BY alum_placed.company_name ORDER BY count DESC LIMIT 3";
    $result_top = $db_conn->query($sql_top);
    $top_names = array();
    $top_counts = array();
    while($row_top = $result_top->fetch_assoc()) {
        array_push($top_names, $row_top["company_name"]);
        array_push($top_counts, $row_top["count"]);
    }
    echo json_encode($top_names);
?>;

var topCounts = <?php echo json_encode($top_counts); ?>;

var topChart = new Chart(topChart, {
    type: 'pie',
    data: {
        labels: topCompanies,
        datasets: [{
            label: '# of Placements',
            data: topCounts,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Top 3 Companies with the Most Placements'
            }
        }
    }
});

	</script>
	</main>

	<footer>
		<p>© 2023 TCP</p>
	</footer>
</body>
</html>