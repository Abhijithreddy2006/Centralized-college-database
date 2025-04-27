<?php
include '../includes/db_connect.php';

// Create attendance table if it doesn't exist
$conn->query("CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    date DATE,
    status VARCHAR(10),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
)");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $date = $_POST['date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO attendance (student_id, course_id, date, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $student_id, $course_id, $date, $status);
    $stmt->execute();
    $message = "✅ Attendance recorded successfully.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Attendance</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f4f4f4;
        }
        .container {
            max-width: 700px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 14px;
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
        select, input[type="date"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #27ae60;
            color: white;
            border: none;
            margin-top: 20px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #1e8449;
        }
        .message {
            padding: 10px;
            background: #eaffea;
            border-left: 5px solid #2ecc71;
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
        <h2>📅 Mark Attendance</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post">
            <label for="student_id">Student</label>
            <select name="student_id" id="student_id" required>
                <option value="">-- Select Student --</option>
                <?php
                $students = $conn->query("SELECT id, name FROM students");
                while ($s = $students->fetch_assoc()) {
                    echo "<option value='{$s['id']}'>{$s['name']}</option>";
                }
                ?>
            </select>

            <label for="course_id">Course</label>
            <select name="course_id" id="course_id" required>
                <option value="">-- Select Course --</option>
                <?php
                $courses = $conn->query("SELECT id, name FROM courses");
                while ($c = $courses->fetch_assoc()) {
                    echo "<option value='{$c['id']}'>{$c['name']}</option>";
                }
                ?>
            </select>

            <label for="date">Date</label>
            <input type="date" name="date" id="date" required>

            <label for="status">Status</label>
            <select name="status" id="status" required>
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
            </select>

            <input type="submit" value="Mark Attendance">
        </form>

        <hr>
        <h3>📝 Attendance Records</h3>
        <ul>
        <?php
        $query = "SELECT s.name, c.name AS course, a.date, a.status
                  FROM attendance a
                  JOIN students s ON a.student_id = s.id
                  JOIN courses c ON a.course_id = c.id
                  ORDER BY a.date DESC";

        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            echo "<li><strong>{$row['name']}</strong> - {$row['course']} on {$row['date']}: <em>{$row['status']}</em></li>";
        }
        ?>
        </ul>
    </div>
</body>
</html>
