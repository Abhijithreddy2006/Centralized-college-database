<?php
include '../includes/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];

    $conn->query("CREATE TABLE IF NOT EXISTS registration (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_id INT,
        course_id INT,
        FOREIGN KEY (student_id) REFERENCES students(id),
        FOREIGN KEY (course_id) REFERENCES courses(id)
    )");

    $stmt = $conn->prepare("INSERT INTO registration (student_id, course_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $student_id, $course_id);

    if ($stmt->execute()) {
        $message = "✅ Student registered successfully.";
    } else {
        $message = "❌ Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Registration</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .container {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 30px auto;
        }
        h2, h3 {
            color: #2c3e50;
        }
        form label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }
        form select, form input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"] {
            background-color: #2980b9;
            color: white;
            border: none;
            cursor: pointer;
            transition: 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #1c5980;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            border-left: 5px solid green;
            background: #e6ffe6;
        }
        ul {
            padding-left: 20px;
        }
        li {
            padding: 4px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📋 Register Student to Course</h2>

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

            <input type="submit" value="Register Student">
        </form>

        <hr>
        <h3>📚 Registered Students</h3>
        <ul>
        <?php
        $result = $conn->query("SELECT s.name AS student, c.name AS course
                                FROM registration r
                                JOIN students s ON r.student_id = s.id
                                JOIN courses c ON r.course_id = c.id");

        while ($row = $result->fetch_assoc()) {
            echo "<li>👤 {$row['student']} ➡️ 📘 {$row['course']}</li>";
        }
        ?>
        </ul>
    </div>
</body>
</html>
