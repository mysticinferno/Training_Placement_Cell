<?php
// establish connection to the database
require_once 'db_config.php';

// check if form has been submitted
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // check if roll no. has been entered
    if (!empty($_POST["rollno"])){
        
        // escape user input to prevent SQL injection
        $rollno = mysqli_real_escape_string($conn, $_POST["rollno"]);
        
        // query the database
        $sql = "SELECT * FROM student_database WHERE rollno = '$rollno'";
        $result = mysqli_query($conn, $sql);
        
        // check if row was returned
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            $row = mysqli_fetch_assoc($result);
            $name = $row["name"];
            $cpi = $row["cpi"];
            $graduation_year = $row["graduation_year"];
            $qualification = $row["qualification"];
            
            // display student information
            echo "Name: " . $name . "<br>";
            echo "Roll No: " . $rollno . "<br>";
            echo "CPI: " . $cpi . "<br>";
            echo "Graduation Year: " . $graduation_year . "<br>";
            echo "Qualification: " . $qualification . "<br>";
        } else {
            // handle error
            echo "No student found with Roll No: " . $rollno;
        }
    } else {
        // handle error
        echo "Roll No is required";
    }
    
    // close database connection
    mysqli_close($conn);    
// } else { 
//     // handle error
//     echo "Form not submitted";
// }
?> 