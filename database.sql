CREATE TABLE students (
    id INT NOT NULL AUTO_INCREMENT,
    firstname VARCHAR(100) NOT NULL,
    middlename VARCHAR(100) NULL,
    lastname VARCHAR(100) NOT NULL,
    birthdate DATE NOT NULL,
    age INT NULL,
    gender VARCHAR(20) NOT NULL,
    nationality VARCHAR(100) NOT NULL,
    address VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE student_contacts (
    id INT NOT NULL AUTO_INCREMENT,
    student_id INT NOT NULL,
    phone VARCHAR(30) NOT NULL,
    email VARCHAR(150) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY unique_student_contact (student_id),
    UNIQUE KEY unique_phone (phone),
    KEY idx_contact_email (email),
    CONSTRAINT fk_student_contacts_student
        FOREIGN KEY (student_id) REFERENCES students(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE guardians (
    id INT NOT NULL AUTO_INCREMENT,
    student_id INT NOT NULL,
    guardian_phone VARCHAR(30) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY unique_student_guardian (student_id),
    CONSTRAINT fk_guardians_student
        FOREIGN KEY (student_id) REFERENCES students(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE student_accounts (
    id INT NOT NULL AUTO_INCREMENT,
    student_id INT NOT NULL,
    student_number VARCHAR(30) NULL,
    password_hash VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY unique_account_student (student_id),
    UNIQUE KEY unique_student_number (student_number),
    CONSTRAINT fk_student_accounts_student
        FOREIGN KEY (student_id) REFERENCES students(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE grade_levels (
    id INT NOT NULL AUTO_INCREMENT,
    level VARCHAR(20) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY unique_grade_level (level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE strands (
    id INT NOT NULL AUTO_INCREMENT,
    code VARCHAR(30) NOT NULL,
    name VARCHAR(100) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY unique_strand_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE school_years (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(20) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY unique_school_year (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE enrollments (
    id INT NOT NULL AUTO_INCREMENT,
    student_id INT NOT NULL,
    grade_level_id INT NOT NULL,
    strand_id INT NOT NULL,
    school_year_id INT NOT NULL,
    previous_school VARCHAR(150) NOT NULL,
    status ENUM('Pending', 'Verified') NOT NULL DEFAULT 'Pending',
    enrolled_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY unique_student_school_year (student_id, school_year_id),
    KEY idx_enrollment_grade (grade_level_id),
    KEY idx_enrollment_strand (strand_id),
    KEY idx_enrollment_school_year (school_year_id),
    CONSTRAINT fk_enrollments_student
        FOREIGN KEY (student_id) REFERENCES students(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_enrollments_grade_level
        FOREIGN KEY (grade_level_id) REFERENCES grade_levels(id),
    CONSTRAINT fk_enrollments_strand
        FOREIGN KEY (strand_id) REFERENCES strands(id),
    CONSTRAINT fk_enrollments_school_year
        FOREIGN KEY (school_year_id) REFERENCES school_years(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE otp_codes (
    id INT NOT NULL AUTO_INCREMENT,
    student_id INT NOT NULL,
    phone VARCHAR(30) NOT NULL,
    code VARCHAR(10) NOT NULL,
    purpose ENUM('register', 'login') NOT NULL DEFAULT 'register',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_otp_phone_code (phone, code),
    CONSTRAINT fk_otp_codes_student
        FOREIGN KEY (student_id) REFERENCES students(id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO grade_levels (level) VALUES
('11'),
('12');

INSERT INTO strands (code, name) VALUES
('ABM', 'ABM'),
('STEM', 'STEM'),
('HUMSS', 'HUMSS'),
('TVL-PROG', 'TVL (Programming)'),
('TVL-COOK', 'TVL (Cookery)');
