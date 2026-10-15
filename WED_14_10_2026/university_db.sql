-- ====================================
-- MySQL Comprehensive Exercise
-- University Database
-- ====================================

-- Step 1: Database Design

CREATE DATABASE university_db;
USE university_db;

-- هنا بنحط أكواد CREATE TABLE للجداول الخمسة


-- Step 2: Insert Data

-- هنا بنحط أكواد INSERT INTO


-- Step 3: Queries

-- Basic Queries

-- Q1
SELECT *
FROM Students;

-- Q2
SELECT COUNT(*) AS total_courses
FROM Courses;

-- Q3
SELECT
    Students.first_name,
    Students.last_name
FROM Students
INNER JOIN Enrollments
ON Students.student_id = Enrollments.student_id
WHERE Enrollments.course_id = 1;

-- Q4
SELECT email
FROM Instructors
WHERE department = 'Computer Science';

-- Intermediate Queries
-- Q5
SELECT
    c.course_name,
    COUNT(e.student_id) AS total_students
FROM Courses AS c
LEFT JOIN Enrollments AS e
ON c.course_id = e.course_id
GROUP BY c.course_id, c.course_name;

-- Q6
SELECT DISTINCT
    s.student_id,
    s.first_name,
    s.last_name
FROM Students AS s
INNER JOIN Enrollments AS e
ON s.student_id = e.student_id
WHERE e.grade = 'A';

-- Q7
SELECT
    c.course_name,
    i.first_name,
    i.last_name,
    ca.semester,
    ca.year
FROM Course_Assignments AS ca
INNER JOIN Courses AS c
    ON ca.course_id = c.course_id
INNER JOIN Instructors AS i
    ON ca.instructor_id = i.instructor_id
WHERE ca.semester = 'Fall'
AND ca.year = 2026;

-- Q8
SELECT
    c.course_name,
    i.first_name,
    i.last_name,
    ca.semester,
    ca.year
FROM Course_Assignments AS ca
INNER JOIN Courses AS c
    ON ca.course_id = c.course_id
INNER JOIN Instructors AS i
    ON ca.instructor_id = i.instructor_id
WHERE ca.semester = 'Fall'
AND ca.year = 2026;

-- Advanced Queries
-- Q9
SELECT
    s.student_id,
    s.first_name,
    s.last_name,
    COUNT(DISTINCT e.course_id) AS total_courses
FROM Students AS s
INNER JOIN Enrollments AS e
    ON s.student_id = e.student_id
WHERE e.semester = 'Fall'
    AND e.year = 2026
GROUP BY s.student_id, s.first_name, s.last_name
HAVING COUNT(DISTINCT e.course_id) > 3

-- Q10
SELECT
    s.student_id,
    s.first_name,
    s.last_name,
    c.course_name,
    e.grade
FROM Students AS s
INNER JOIN Enrollments AS e
    ON s.student_id = e.student_id
INNER JOIN Courses AS c
    ON e.course_id = c.course_id
WHERE e.grade IS NULL
   OR e.grade = 'I';

-- Q11
SELECT
    s.student_id,
    s.first_name,
    s.last_name,
    ROUND(AVG(
        CASE
            WHEN e.grade = 'A' THEN 4.0
            WHEN e.grade = 'B+' THEN 3.5
            WHEN e.grade = 'B' THEN 3.0
            WHEN e.grade = 'C+' THEN 2.5
            WHEN e.grade = 'C' THEN 2.0
            ELSE NULL
        END
    ), 2) AS average_grade
FROM Students AS s
INNER JOIN Enrollments AS e
    ON s.student_id = e.student_id
GROUP BY s.student_id, s.first_name, s.last_name
HAVING COUNT(
    CASE
        WHEN e.grade IN ('A', 'B+', 'B', 'C+', 'C')
        THEN 1
    END
) > 0
ORDER BY average_grade DESC
LIMIT 1;

-- Q12
SELECT
    c.department,
    COUNT(DISTINCT ca.course_id) AS total_courses
FROM Courses AS c
INNER JOIN Course_Assignments AS ca
    ON c.course_id = ca.course_id
WHERE ca.year = 2026
GROUP BY c.department
ORDER BY total_courses DESC
LIMIT 1;

-- Q13
SELECT
    c.course_id,
    c.course_name,
    c.course_code
FROM Courses AS c
LEFT JOIN Enrollments AS e
    ON c.course_id = e.course_id
WHERE e.enrollment_id IS NULL;

-- Step 4: Functions and Aggregates


-- Step 5: Constraints and Transactions


-- Step 6: Optimization and Indexes


-- Step 7: Joins


-- Step 8: Final Challenge