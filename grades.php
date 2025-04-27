<?php
include '../includes/db_connect.php';

// Create table if it doesn't exist
$conn->query("CREATE TABLE IF NOT EXISTS grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    grade VARCHAR(2),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
)");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST['student_id'];
    $course_id = $_POST['course_id'];
    $grade = $_POST['grade'];

    $stmt = $conn->prepare("INSERT INTO grades (student_id, course_id, grade) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $student_id, $course_id, $grade);
    $stmt->execute();
    $message = "✅ Grade added successfully.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Grades</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
        }
        .container {
            max-width: 650px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 0 15px rgba(0,0,0,0.08);
        }
        h2, h3 {
            color: #2c3e50;
        }
        form label {
            display: block;
            margin-top: 15px;
            font-weight: 600;
        }
        select, input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            font-size: 16px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #8e44ad;
            color: white;
            border: none;
            margin-top: 20px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #732d91;
        }
        .message {
            padding: 10px;
            background: #f3e8ff;
            border-left: 5px solid #8e44ad;
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
        <h2>🏅 Add Grade</h2>

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

            <label for="grade">Grade</label>
            <input type="text" name="grade" id="grade" maxlength="2" required>

            <input type="submit" value="Add Grade">
        </form>

        <hr>
        <h3>📚 Grade Records</h3>
        <ul>
        <?php
        $query = "SELECT s.name AS student, c.name AS course, g.grade
                  FROM grades g
                  JOIN students s ON g.student_id = s.id
                  JOIN courses c ON g.course_id = c.id
                  ORDER BY s.name";

        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            echo "<li><strong>{$row['student']}</strong> - {$row['course']} : Grade <strong>{$row['grade']}</strong></li>";
        }
        ?>
        </ul>
    </div>
</body>
</html>
