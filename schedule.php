<?php
include '../includes/db_connect.php';

// Create table if not exists
$conn->query("CREATE TABLE IF NOT EXISTS schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    day_of_week VARCHAR(20),
    start_time TIME,
    end_time TIME,
    FOREIGN KEY (course_id) REFERENCES courses(id)
)");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_id = $_POST['course_id'];
    $day = $_POST['day'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    $stmt = $conn->prepare("INSERT INTO schedule (course_id, day_of_week, start_time, end_time) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $course_id, $day, $start, $end);
    $stmt->execute();
    $message = "✅ Schedule added successfully.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Schedule</title>
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
        select, input[type="text"], input[type="time"], input[type="submit"] {
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
            background: #e9f7ef;
            border-left: 5px solid #27ae60;
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
        <h2>📅 Add Schedule</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post">
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

            <label for="day">Day</label>
            <input type="text" name="day" id="day" placeholder="e.g., Monday" required>

            <label for="start">Start Time</label>
            <input type="time" name="start" id="start" required>

            <label for="end">End Time</label>
            <input type="time" name="end" id="end" required>

            <input type="submit" value="Add Schedule">
        </form>

        <hr>
        <h3>🗓️ Current Schedule</h3>
        <ul>
        <?php
        $query = "SELECT c.name AS course, s.day_of_week, s.start_time, s.end_time
                  FROM schedule s
                  JOIN courses c ON s.course_id = c.id
                  ORDER BY FIELD(s.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'), s.start_time";

        $result = $conn->query($query);
        while ($row = $result->fetch_assoc()) {
            echo "<li><strong>{$row['course']}</strong> - {$row['day_of_week']} ({$row['start_time']} to {$row['end_time']})</li>";
        }
        ?>
        </ul>
    </div>
</body>
</html>
