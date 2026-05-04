<?php
$host = "localhost";
$user = "root";
$password = ""; // XAMPP default is empty
$database = "ClothingStore";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>