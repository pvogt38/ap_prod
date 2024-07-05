<?php
$servername = "localhost";
$username = "root"; // Replace with your username
$password = ""; // Replace with your password
$dbname = "prod"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$work_order_id = $_GET['work_order_id']; // Or however you get the work_order_id

$sql = "SELECT
            wo.work_order_id,
            po.po_number,
            c.customer_name,
            wo.status,
            a.assembly_id,
            a.description,
            poi.quantity AS assembly_order_quantity,
            ap.part_id,
            ap.quantity AS parts_per_assembly,
            p.part_name
        FROM
            WorkOrders wo
        LEFT JOIN
            PurchaseOrders po ON wo.po_id = po.po_id
        LEFT JOIN
            Customers c ON po.customer_id = c.customer_id
        LEFT JOIN
            Assemblies a ON wo.assembly_id = a.assembly_id
        LEFT JOIN
            po_items poi ON poi.assembly_id = a.assembly_id AND poi.po_id = po.po_id
        LEFT JOIN
            assembly_parts ap ON ap.assembly_id = a.assembly_id
        LEFT JOIN
            Parts p ON ap.part_id = p.part_id
        WHERE
            wo.work_order_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $work_order_id);
$stmt->execute();
$result = $stmt->get_result();

$work_order_details = [];
while ($row = $result->fetch_assoc()) {
    $work_order_details[] = $row;
}

$stmt->close();
$conn->close();

$total_quantities = [];
foreach ($work_order_details as $detail) {
    $total_quantity = $detail['assembly_order_quantity'] * $detail['parts_per_assembly'];
    $total_quantities[] = [
        'part_name' => $detail['part_name'],
        'total_quantity' => $total_quantity,
        'parts_per_assembly' => $detail['parts_per_assembly']
    ];
}

// Extracting the other data to display
if (!empty($work_order_details)) {
    $po_number = $work_order_details[0]['po_number'];
    $customer_name = $work_order_details[0]['customer_name'];
    $status = $work_order_details[0]['status'];
    $assembly_id = $work_order_details[0]['assembly_id'];
    $description = $work_order_details[0]['description'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Work Order Review</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div w3-include-html="banner.html"></div>
    <div class="container">
        <h2>Work Order Review</h2>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Work Order ID:</strong> <?php echo htmlspecialchars($work_order_id); ?></p>
                <p><strong>PO Number:</strong> <?php echo htmlspecialchars($po_number); ?></p>
                <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($customer_name); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($status); ?></p>
                <p><strong>Assembly ID:</strong> <?php echo htmlspecialchars($assembly_id); ?></p>
                <p><strong>Description:</strong> <?php echo htmlspecialchars($description); ?></p>
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Part Name</th>
                    <th>Parts per Assembly</th>
                    <th>Total Quantity Needed</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($total_quantities as $item): ?>
                <tr>
                    <td><?php echo htmlspecialchars($item['part_name']); ?></td>
                    <td><?php echo htmlspecialchars($item['parts_per_assembly']); ?></td>
                    <td><?php echo htmlspecialchars($item['total_quantity']); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="button" class="btn btn-secondary" onclick="window.location.href='view_work_order.php'">Close</button>
    </div>
    <script>
        function includeHTML() {
            var z, i, elmnt, file, xhttp;
            z = document.getElementsByTagName("*");
            for (i = 0; i < z.length; i++) {
                elmnt = z[i];
                file = elmnt.getAttribute("w3-include-html");
                if (file) {
                    xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            elmnt.innerHTML = this.responseText;
                            elmnt.removeAttribute("w3-include-html");
                            includeHTML();
                        }
                    }
                    xhttp.open("GET", file, true);
                    xhttp.send();
                    return;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            includeHTML();
        });

        function updateDateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const dateString = now.toLocaleDateString();

            const timeElement = document.getElementById('time');
            const dateElement = document.getElementById('date');

            if (timeElement && dateElement) {
                timeElement.textContent = timeString;
                dateElement.textContent = dateString;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateDateTime();
            setInterval(updateDateTime, 60000); // Update every minute
        });
    </script>
</body>
</html>
