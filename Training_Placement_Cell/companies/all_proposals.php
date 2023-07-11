<?php
// Establish database connection
require_once 'db_config.php';

// Handle delete button
if (isset($_POST['delete'])) {
    $rid = $_POST['rid'];
    $sql = "DELETE FROM company_roles WHERE rid='$rid'";
    if ($db_conn->query($sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $db_conn->error;
    }
}

// Retrieve data from table
$sql = "SELECT * FROM company_roles";
$result = $db_conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Proposals</title>
</head>
<body>
    <h1>My Proposals</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Contact Phone</th>
                <th>Contact Email</th>
                <th>Interview Mode</th>
                <th>Minimum CPI</th>
                <th>Qualification</th>
                <th>Salary</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["contact_phone"] . "</td>";
                    echo "<td>" . $row["contact_email"] . "</td>";
                    echo "<td>" . $row["interview_mode"] . "</td>";
                    echo "<td>" . $row["min_cpi"] . "</td>";
                    echo "<td>" . $row["qualification"] . "</td>";
                    echo "<td>" . $row["salary"] . "</td>";
                    echo "<td>" . $row["role"] . "</td>";
                    // Add delete button
                    echo "<td>";
                    echo "<form method='post'>";
                    echo "<input type='hidden' name='rid' value='" . $row["rid"] . "'>";
                    echo "<input type='submit' name='delete' value='Delete'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>0 results</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
// Close database connection
$db_conn->close();
?>
