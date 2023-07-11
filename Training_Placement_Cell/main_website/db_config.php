<?php

session_start();

$db_host = "localhost";
  $db_name = "new_database";
  $db_user = "newuser";
  $db_pass = "niibtarana";
  $db_conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

  if ($db_conn->connect_error) {
    die("Connection failed: " . $db_conn->connect_error);
}

?>