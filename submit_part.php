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

$partName = $_POST['partName'];
$partNumber = $_POST['partNumber'];

// Insert the new part into the Parts table
$sql = "INSERT INTO Parts (part_name, part_number) VALUES ('$partName', '$partNumber')";
if ($conn->query($sql) === TRUE) {
    // Get the last inserted part_id
    $partId = $conn->insert_id;

    // Redirect to the confirmation page with part details
    header("Location: confirm_part.php?part_id=$partId&part_name=$partName&part_number=$partNumber");
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
