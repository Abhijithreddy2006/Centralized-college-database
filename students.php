<?php
include '../includes/db_connect.php';

// Auto-create table if missing
$conn->query("CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    dob DATE
)");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $dob = $_POST['dob'];

    $stmt = $conn->prepare("INSERT INTO students (name, email, dob) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $dob);
    $stmt->execute();
    $message = "✅ Student added successfully.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Students</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f6f9;
        }
        .container {
            max-width: 650px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 12px rgba(0,0,0,0.08);
        }
        h2, h3 {
            color: #2c3e50;
        }
        form label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }
        input[type="text"], input[type="email"], input[type="date"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #2980b9;
            color: white;
            border: none;
            margin-top: 20px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #1c5980;
        }
        .message {
            padding: 10px;
            background: #eaf4ff;
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
        <h2>🎓 Add Student</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="dob">Date of Birth</label>
            <input type="date" name="dob" id="dob" required>

            <input type="submit" value="Add Student">
        </form>

        <hr>
        <h3>📋 Current Students</h3>
        <ul>
        <?php
        $result = $conn->query("SELECT * FROM students ORDER BY name");
        while ($row = $result->fetch_assoc()) {
            echo "<li><strong>{$row['name']}</strong> ({$row['email']})</li>";
        }
        ?>
        </ul>
    </div>
</body>
</html>
