-- Database: college

CREATE DATABASE IF NOT EXISTS college;
USE college;

-- Students Table
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    email VARCHAR(100),
    dob DATE
);

-- Faculty Table
CREATE TABLE faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    department VARCHAR(100)
);

-- Courses Table
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    code VARCHAR(10),
    faculty_id INT,
    FOREIGN KEY (faculty_id) REFERENCES faculty(id)
);

-- Grades Table
CREATE TABLE grades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    grade CHAR(2),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Attendance Table
CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    course_id INT,
    date DATE,
    status ENUM('Present', 'Absent'),
    FOREIGN KEY (student_id) REFERENCES students(id),
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Schedule Table
CREATE TABLE schedule (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT,
    day_of_week VARCHAR(10),
    start_time TIME,
    end_time TIME,
    FOREIGN KEY (course_id) REFERENCES courses(id)
);

-- Sample Data
INSERT INTO students (name, email, dob) VALUES
('Alice Smith', 'alice@example.com', '2002-05-15'),
('Bob Johnson', 'bob@example.com', '2001-08-22');

INSERT INTO faculty (name, department) VALUES
('Dr. Adams', 'Computer Science'),
('Prof. Blake', 'Mathematics');

INSERT INTO courses (name, code, faculty_id) VALUES
('Data Structures', 'CS201', 1),
('Calculus I', 'MATH101', 2);

INSERT INTO grades (student_id, course_id, grade) VALUES
(1, 1, 'A'),
(2, 2, 'B');

INSERT INTO attendance (student_id, course_id, date, status) VALUES
(1, 1, '2025-04-01', 'Present'),
(2, 2, '2025-04-01', 'Absent');

INSERT INTO schedule (course_id, day_of_week, start_time, end_time) VALUES
(1, 'Monday', '09:00:00', '10:30:00'),
(2, 'Tuesday', '11:00:00', '12:30:00');
