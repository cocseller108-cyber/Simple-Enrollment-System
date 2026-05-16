# Simple Enrollment System ERD

```mermaid
erDiagram
    STUDENTS ||--|| STUDENT_CONTACTS : has
    STUDENTS ||--|| GUARDIANS : has
    STUDENTS ||--|| STUDENT_ACCOUNTS : owns
    STUDENTS ||--o{ ENROLLMENTS : submits
    STUDENTS ||--o{ OTP_CODES : receives
    GRADE_LEVELS ||--o{ ENROLLMENTS : classifies
    STRANDS ||--o{ ENROLLMENTS : selects
    SCHOOL_YEARS ||--o{ ENROLLMENTS : scheduled_for

    STUDENTS {
        int id PK
        varchar firstname
        varchar middlename
        varchar lastname
        date birthdate
        int age
        varchar gender
        varchar nationality
        varchar address
        timestamp created_at
    }

    STUDENT_CONTACTS {
        int id PK
        int student_id FK
        varchar phone
        varchar email
    }

    GUARDIANS {
        int id PK
        int student_id FK
        varchar guardian_phone
    }

    STUDENT_ACCOUNTS {
        int id PK
        int student_id FK
        varchar student_number
        varchar password_hash
        timestamp created_at
    }

    GRADE_LEVELS {
        int id PK
        varchar level
    }

    STRANDS {
        int id PK
        varchar code
        varchar name
    }

    SCHOOL_YEARS {
        int id PK
        varchar name
    }

    ENROLLMENTS {
        int id PK
        int student_id FK
        int grade_level_id FK
        int strand_id FK
        int school_year_id FK
        varchar previous_school
        enum status
        timestamp enrolled_at
    }

    OTP_CODES {
        int id PK
        int student_id FK
        varchar phone
        varchar code
        enum purpose
        timestamp created_at
    }
```

## Relationship Summary

- `students` stores only the learner's personal profile.
- `student_contacts`, `guardians`, and `student_accounts` are one-to-one tables related to `students`.
- `enrollments` connects a student to one `grade_level`, one `strand`, and one `school_year`.
- `otp_codes` stores temporary OTP records for verification and is removed after successful use.
- Foreign keys use `ON DELETE CASCADE` for student-owned data, so deleting a student also removes their contacts, guardian record, account, enrollments, and OTP codes.
