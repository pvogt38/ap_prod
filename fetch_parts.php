<?php
header('Content-Type: application/json');
$servername = "localhost";
$username = "root"; // Replace with your username
$password = ""; // Replace with your password
$dbname = "prod"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT part_id, part_name FROM Parts";
$result = $conn->query($sql);
$parts = [];
while ($row = $result->fetch_assoc()) {
    $parts[] = $row;
}
$conn->close();
echo json_encode($parts);
?>
