<?php
$servername = "localhost";
$username = "root"; // Replace with your username
$password = ""; // Replace with your password
$dbname = "prod"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$description = $_POST['description'];

// Insert the new assembly into the Assemblies table using prepared statements
$stmt = $conn->prepare("INSERT INTO Assemblies (description) VALUES (?)");
$stmt->bind_param("s", $description);
$stmt->execute();

// Get the last inserted assembly_id
$assemblyId = $conn->insert_id;

// Insert the components into the assembly_parts table using prepared statements
$stmt = $conn->prepare("INSERT INTO assembly_parts (assembly_id, part_id, quantity) VALUES (?, ?, ?)");
$partIndex = 1;
while (isset($_POST["partId$partIndex"])) {
    $partId = $_POST["partId$partIndex"];
    $quantity = $_POST["quantity$partIndex"];
    $stmt->bind_param("iii", $assemblyId, $partId, $quantity);
    $stmt->execute();
    $partIndex++;
}

$stmt->close();
$conn->close();

// Redirect to the confirmation page with assembly details
header("Location: confirm_assembly.php?assembly_id=$assemblyId&description=$description");
exit();
?>
