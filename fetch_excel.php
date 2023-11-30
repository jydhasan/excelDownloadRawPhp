<?php

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "excel";
$tableName = "students";

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the MySQL table
$sql = "SELECT * FROM $tableName";
$result = $conn->query($sql);

// Create a file pointer connected to the output stream
$fp = fopen('php://output', 'w');

// Output CSV headers
$header = [];
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $header = array_keys($row);
    fputcsv($fp, $header);
    // Reset result pointer to the beginning
    $result->data_seek(0);
}

// Output data to CSV
while ($row = $result->fetch_assoc()) {
    fputcsv($fp, $row);
}

// Close the file pointer
fclose($fp);

// Close MySQL connection
$conn->close();

// Set headers for download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="output.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// Output the generated CSV to the browser
readfile('php://output');
?>