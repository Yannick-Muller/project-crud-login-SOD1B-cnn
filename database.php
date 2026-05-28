<?php 

$host = "localhost";
$dbname = "cnn";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connectie mislukt: " . $conn->connect_error);
}

echo "Verbonden met database!";
?>