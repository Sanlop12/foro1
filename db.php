<?php
$conn = new mysqli("localhost", "root", "", "auth_system");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
