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

$sql = "SELECT assembly_id, description FROM Assemblies";
$result = $conn->query($sql);

$assemblies = [];
while ($row = $result->fetch_assoc()) {
    $assemblies[] = $row;
}

$conn->close();
echo json_encode($assemblies);
?>
