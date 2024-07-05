<?php
$servername = "localhost";
$username = "root"; // Replace with your username
$password = ""; // Replace with your password
$dbname = "prod"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $poItemId = $_POST['po_item_id'];

    // Fetch PO Item details
    $sql = "SELECT po_id, assembly_id, quantity FROM PO_Items WHERE po_item_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $poItemId);
    $stmt->execute();
    $result = $stmt->get_result();
    $poItem = $result->fetch_assoc();

    if ($poItem) {
        $poId = $poItem['po_id'];
        $assemblyId = $poItem['assembly_id'];
        $quantity = $poItem['quantity'];

        // Insert into WorkOrders
        $sql = "INSERT INTO WorkOrders (po_item_id, assembly_id, quantity, status, po_id) VALUES (?, ?, ?, 'Pending', ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $poItemId, $assemblyId, $quantity, $poId);
        if ($stmt->execute()) {
            $workOrderId = $stmt->insert_id; // Get the ID of the newly created work order
            header("Location: review_work_order.php?work_order_id=" . $workOrderId);
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "PO item not found.";
    }

    $stmt->close();
}

$conn->close();
?>
