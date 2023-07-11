<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Company Details</title>
    <style>
      table {
        border-collapse: collapse;
        width: 100%;
      }

      th, td {
        text-align: left;
        padding: 8px;
      }

      tr:nth-child(even) {
        background-color: #f2f2f2;
      }

      th {
        background-color: #4CAF50;
        color: white;
      }

      .pagination {
        display: inline-block;
      }

      .pagination a {
        color: black;
        float: left;
        padding: 8px 16px;
        text-decoration: none;
      }

      .pagination a.active {
        background-color: #4CAF50;
        color: white;
      }

      .pagination a:hover:not(.active) {
        background-color: #ddd;
      }
    </style>
  </head>
  <body>

    <?php
    session_start();

    // if (!isset($_SESSION['company_id'])) {
    //   header("Location: company_login_ui.php");
    //   exit();
    // }

    require_once 'db_config.php';

    if (!$db_conn) {
      die("Connection failed: " . mysqli_connect_error());
    }

    $rows_per_page = 20;

    if (isset($_GET['page'])) {
      $page = $_GET['page'];
    } else {
      $page = 1;
    }

    $start_from = ($page-1) * $rows_per_page;

    $result = mysqli_query($db_conn, "SELECT s.id, s.name, s.rollno, s.cpi, s.graduation_year 
    FROM student_database s LIMIT $start_from, $rows_per_page"); 

    $sn = 1;

    echo "<h2>All students</h2>";
    echo "<table>";
    echo "<tr><th>ID</th><th>Name</th><th>Roll No.</th><th>CPI</th><th>Graduation Year</th></tr>";
    while ($row = mysqli_fetch_assoc($result)) {
      echo "<tr>";
      echo "<td>".$sn."</td>";
      echo "<td>".$row['name']."</td>";
      echo "<td>".$row['rollno']."</td>";
      echo "<td>".$row['cpi']."</td>";
      echo "<td>".$row['graduation_year']."</td>";
      echo "</tr>";
      $sn++;
    }
    echo "</table>";

    $result_count = mysqli_query($db_conn, "SELECT COUNT(*) AS total FROM student_database s, company_database c");
    $row_count = mysqli_fetch_assoc($result_count)['total'];

    $total_pages = ceil($row_count / $rows_per_page);

    echo "<div class='pagination'>";
    for ($i = 1; $i <= $total_pages; $i++) {
      echo "<a href='all_students.php?page=".$i."'";
      if ($i == $page) {
        echo " class='active'";
      }
      echo ">".$i."</a>";
    }
    echo "</div>";

    mysqli_close($db_conn);
    ?>

  </body