<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Part Created Successfully</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; text-align: center; }
        .container {
            background-color: #f0f0f0;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: inline-block;
        }
        .container h2 { color: #333; }
        .container p { font-size: 18px; }
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
        <h2>Part Created Successfully</h2>
        <p><strong>Part ID:</strong> <?php echo htmlspecialchars($_GET['part_id']); ?></p>
        <p><strong>Part Name:</strong> <?php echo htmlspecialchars($_GET['part_name']); ?></p>
        <p><strong>Part Number:</strong> <?php echo htmlspecialchars($_GET['part_number']); ?></p>
        <button onclick="window.location.href='index.html'">Close</button>
    </div>
</body>
</html>
