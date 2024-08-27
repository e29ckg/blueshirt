<?php
$servername = "localhost";
$username = "root"; // Change if you have a different username
$password = ""; // Change if you have a password
$dbname = "blueshirt";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();
}
?>