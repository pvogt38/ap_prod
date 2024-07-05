<?php
$servername = "localhost";
$username = "root"; // Replace with your username
$password = ""; // Replace with your password
$dbname = "prod"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT work_order_id,
        COALESCE(c.customer_name, 'N/A') AS customer_name,
        wo.status,
        po.po_number,
        a.assembly_id,
        a.description
        FROM WorkOrders wo
        LEFT JOIN PurchaseOrders po ON wo.po_id = po.po_id
        LEFT JOIN Customers c ON po.customer_id = c.customer_id
        LEFT JOIN Assemblies a ON wo.assembly_id = a.assembly_id
        ORDER BY wo.work_order_id";
$result = $conn->query($sql);

$work_orders = [];
if ($result !== false) {
    while ($row = $result->fetch_assoc()) {
        $work_orders[] = $row;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Work Order</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <style>
        .narrow-select {
            width: 200px;
        }
        .button-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .button-container button {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div w3-include-html="banner.html"></div>
    <div class="container">
        <h2>View Work Order</h2>
        <form>
            <div class="form-group">
                <label for="work_order_id">Select Work Order:</label>
                <select id="work_order_id" name="work_order_id" class="form-control narrow-select" onchange="displayWorkOrderDetails()">
                    <option value="">Select a work order</option>
                    <?php foreach ($work_orders as $order): ?>
                        <option value="<?php echo htmlspecialchars($order['work_order_id']); ?>"
                                data-customer-name="<?php echo htmlspecialchars($order['customer_name']); ?>"
                                data-status="<?php echo htmlspecialchars($order['status']); ?>"
                                data-po-number="<?php echo htmlspecialchars($order['po_number']); ?>"
                                data-assembly-id="<?php echo htmlspecialchars($order['assembly_id']); ?>"
                                data-description="<?php echo htmlspecialchars($order['description']); ?>">
                            <?php echo htmlspecialchars($order['work_order_id']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
        <div id="work_order_details" class="mt-4"></div>
        <div class="button-container mt-4">
            <button type="button" class="btn btn-primary" onclick="openWorkOrder()">View Work Order</button>
            <button type="button" class="btn btn-secondary" onclick="goBack()">Back</button>
        </div>
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

        function displayWorkOrderDetails() {
            const select = document.getElementById('work_order_id');
            const selectedOption = select.options[select.selectedIndex];
            const details = document.getElementById('work_order_details');

            const customerName = selectedOption.getAttribute('data-customer-name');
            const status = selectedOption.getAttribute('data-status');
            const poNumber = selectedOption.getAttribute('data-po-number');
            const assemblyId = selectedOption.getAttribute('data-assembly-id');
            const description = selectedOption.getAttribute('data-description');

            details.innerHTML = `
                <strong>Customer Name:</strong> ${customerName ? customerName : 'Not available'} <br>
                <strong>Status:</strong> ${status ? status : 'Not available'} <br>
                <strong>PO Number:</strong> ${poNumber ? poNumber : 'Not available'} <br>
                <strong>Assembly ID:</strong> ${assemblyId ? assemblyId : 'Not available'} <br>
                <strong>Description:</strong> ${description ? description : 'Not available'} <br>
            `;
        }

        function openWorkOrder() {
            const select = document.getElementById('work_order_id');
            const workOrderId = select.value;
            if (workOrderId) {
                window.location.href = `review_work_order.php?work_order_id=${workOrderId}`;
            }
        }

        function goBack() {
            window.location.href = 'index.html'; // Adjust this if your main form is located elsewhere
        }

        document.addEventListener('DOMContentLoaded', function() {
            includeHTML();
            setTimeout(function() {
                updateDateTime();
                setInterval(updateDateTime, 60000); // Update every minute
            }, 500); // Small delay to ensure the banner is loaded
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
    </script>
</body>
</html>
