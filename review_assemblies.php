<?php
$servername = "localhost";
$username = "root"; // Replace with your username
$password = ""; // Replace with your password
$dbname = "prod"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT a.assembly_id, a.description, p.part_name, ap.quantity
        FROM Assemblies a
        LEFT JOIN assembly_parts ap ON a.assembly_id = ap.assembly_id
        LEFT JOIN Parts p ON ap.part_id = p.part_id
        ORDER BY a.assembly_id, p.part_name";
$result = $conn->query($sql);

$assemblies = [];
while ($row = $result->fetch_assoc()) {
    $assemblies[$row['assembly_id']]['description'] = $row['description'];
    $assemblies[$row['assembly_id']]['parts'][] = [
        'part_name' => $row['part_name'],
        'quantity' => $row['quantity']
    ];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Assemblies</title>
    <link rel="stylesheet" href="assets/css/styles.css">
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

        function updateDateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const dateString = now.toLocaleDateString();
            document.getElementById('time').textContent = timeString;
            document.getElementById('date').textContent = dateString;
        }

        document.addEventListener('DOMContentLoaded', () => {
            includeHTML();
            setInterval(updateDateTime, 1000);
            updateDateTime(); // Initial call to set the date and time immediately
        });
    </script>
</head>
<body>
    <div w3-include-html="banner.html"></div>
    <div class="container">
        <h2>Assemblies List</h2>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Assembly ID</th>
                        <th>Description</th>
                        <th>Part Name</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($assemblies as $assemblyId => $assembly): ?>
                        <tr>
                            <td rowspan="<?php echo count($assembly['parts']) + 1; ?>"><?php echo htmlspecialchars($assemblyId); ?></td>
                            <td rowspan="<?php echo count($assembly['parts']) + 1; ?>"><?php echo htmlspecialchars($assembly['description']); ?></td>
                        </tr>
                        <?php foreach ($assembly['parts'] as $part): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($part['part_name']); ?></td>
                                <td><?php echo htmlspecialchars($part['quantity']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="button-container">
            <button onclick="window.location.href='index.html'">Close</button>
        </div>
    </div>
</body>
</html>
