ALTER TABLE students
    ADD COLUMN student_id VARCHAR(30) NULL AFTER id,
    ADD COLUMN password_hash VARCHAR(255) NULL AFTER student_id;

ALTER TABLE students
    ADD UNIQUE KEY unique_student_id (student_id);
