CREATE DATABASE IF NOT EXISTS student_registration_portal;
USE student_registration_portal;

DROP TABLE IF EXISTS students;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash CHAR(64) NOT NULL,
    full_name VARCHAR(120) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id VARCHAR(20) NOT NULL UNIQUE,
    full_name VARCHAR(120) NOT NULL,
    gender ENUM('Male', 'Female') NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    phone VARCHAR(30) NOT NULL,
    dob DATE NOT NULL,
    age INT NOT NULL,
    address TEXT NOT NULL,
    course VARCHAR(80) NOT NULL,
    semester INT NOT NULL,
    enrollment_year INT NOT NULL,
    skills VARCHAR(150),
    hostel TINYINT(1) NOT NULL DEFAULT 0,
    total_fee DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, password_hash, full_name) VALUES
('admin', SHA2('admin123', 256), 'System Administrator');

DELIMITER //
CREATE PROCEDURE seed_students()
BEGIN
    DECLARE i INT DEFAULT 1;
    DECLARE v_course VARCHAR(80);
    DECLARE v_gender VARCHAR(10);
    DECLARE v_semester INT;
    DECLARE v_hostel TINYINT;
    DECLARE v_fee DECIMAL(10,2);

    WHILE i <= 100 DO
        SET v_course = CASE i MOD 5
            WHEN 0 THEN 'Software Engineering'
            WHEN 1 THEN 'Network Security'
            WHEN 2 THEN 'Mechatronics'
            WHEN 3 THEN 'Automotive Technology'
            ELSE 'Electrical Engineering'
        END;
        SET v_gender = IF(i MOD 2 = 0, 'Male', 'Female');
        SET v_semester = (i MOD 6) + 1;
        SET v_hostel = IF(i MOD 3 = 0, 1, 0);
        SET v_fee = CASE v_course
            WHEN 'Software Engineering' THEN 1250
            WHEN 'Network Security' THEN 1180
            WHEN 'Mechatronics' THEN 1320
            WHEN 'Automotive Technology' THEN 1150
            ELSE 1220
        END;
        SET v_fee = (v_fee * v_semester) + 100 + IF(v_hostel = 1, 450, 0);

        INSERT INTO students (
            student_id, full_name, gender, email, phone, dob, age, address,
            course, semester, enrollment_year, skills, hostel, total_fee
        ) VALUES (
            CONCAT('SWE2026', LPAD(i, 3, '0')),
            CONCAT('Student ', LPAD(i, 3, '0')),
            v_gender,
            CONCAT('student', LPAD(i, 3, '0'), '@gmi.edu.my'),
            CONCAT('012-555', LPAD(i, 4, '0')),
            DATE_SUB('2006-01-01', INTERVAL (i MOD 900) DAY),
            TIMESTAMPDIFF(YEAR, DATE_SUB('2006-01-01', INTERVAL (i MOD 900) DAY), CURDATE()),
            CONCAT(i, ', Jalan Ilmu, Kajang, Selangor'),
            v_course,
            v_semester,
            2026,
            IF(i MOD 2 = 0, 'HTML, PHP, MySQL', 'HTML, CSS'),
            v_hostel,
            v_fee
        );

        SET i = i + 1;
    END WHILE;
END//
DELIMITER ;

CALL seed_students();
DROP PROCEDURE seed_students;

SELECT 'Database created successfully. Login username: admin, password: admin123' AS message;
