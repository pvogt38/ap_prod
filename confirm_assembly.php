<?php
$servername = "localhost";
$username = "root"; // Replace with your username
$password = ""; // Replace with your password
$dbname = "prod"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$assemblyId = $_GET['assembly_id'];
$description = $_GET['description'];

// Fetch the parts of the newly created assembly
$sql = "SELECT p.part_name, ap.quantity FROM assembly_parts ap JOIN Parts p ON ap.part_id = p.part_id WHERE ap.assembly_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $assemblyId);
$stmt->execute();
$result = $stmt->get_result();

$parts = [];
while ($row = $result->fetch_assoc()) {
    $parts[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assembly Created</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            text-align: center;
        }
        .container {
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
        .container h2 {
            color: #333;
        }
        .container p {
            font-size: 18px;
        }
        .container button {
            margin-top: 20px;
            padding: 10px 20px;
            font-size: 18px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .container button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Assembly Created Successfully</h2>
        <p><strong>Assembly ID:</strong> <?php echo htmlspecialchars($assemblyId); ?></p>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($description); ?></p>
        <h3>Parts</h3>
        <ul>
            <?php foreach ($parts as $part): ?>
                <li><?php echo htmlspecialchars($part['part_name']) . " - Quantity: " . htmlspecialchars($part['quantity']); ?></li>
            <?php endforeach; ?>
        </ul>
        <button onclick="window.location.href='index.html'">Close</button>
    </div>
</body>
</html>
