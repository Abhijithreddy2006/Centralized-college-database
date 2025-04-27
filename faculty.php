<?php
include '../includes/db_connect.php';

// Create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    department VARCHAR(100)
)");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $dept = $_POST['department'];

    $stmt = $conn->prepare("INSERT INTO faculty (name, department) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $dept);
    $stmt->execute();
    $message = "✅ Faculty added successfully.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Faculty</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f9f9f9;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }
        h2, h3 {
            color: #2c3e50;
        }
        form label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }
        form input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"] {
            background-color: #3498db;
            color: white;
            border: none;
            margin-top: 20px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #2980b9;
        }
        .message {
            padding: 10px;
            background: #e1f5fe;
            border-left: 5px solid #3498db;
            margin-bottom: 20px;
        }
        ul {
            padding-left: 20px;
        }
        li {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>👨‍🏫 Add Faculty</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required>

            <label for="department">Department</label>
            <input type="text" name="department" id="department" required>

            <input type="submit" value="Add Faculty">
        </form>

        <hr>
        <h3>📋 Current Faculty</h3>
        <ul>
        <?php
        $result = $conn->query("SELECT * FROM faculty");
        while ($row = $result->fetch_assoc()) {
            echo "<li><strong>{$row['name']}</strong> — {$row['department']}</li>";
        }
        ?>
        </ul>
    </div>
</body>
</html>
