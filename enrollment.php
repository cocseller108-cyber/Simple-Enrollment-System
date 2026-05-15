<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Enrollment Application</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body class="form-page">
    <header class="site-header compact">
        <a class="brand" href="index.php">
            <span class="brand-mark">N</span>
            <span>
                <strong>Northbridge</strong>
                <small>Enrollment Office</small>
            </span>
        </a>
        <nav class="site-nav" aria-label="Enrollment navigation">
            <a href="index.php">Home</a>
            <a href="student_login.php">Student Portal</a>
        </nav>
    </header>

    <main class="form-layout">
        <aside class="form-aside">
            <p class="eyebrow">Online Application</p>
            <h1>Submit your senior high enrollment application.</h1>
            <p>
                Fill out the required details carefully. After submission, you will receive an OTP for verification.
            </p>

            <div class="requirements-card">
                <h2>Before you begin</h2>
                <ul>
                    <li>Use an active mobile number for OTP verification.</li>
                    <li>Prepare your previous school details.</li>
                    <li>Review your strand and grade level before submitting.</li>
                </ul>
            </div>
        </aside>

        <section class="application-card">
            <div class="stepper" aria-label="Application progress">
                <button class="step-indicator active" type="button" data-step-label="1">Personal</button>
                <button class="step-indicator" type="button" data-step-label="2">Academic</button>
                <button class="step-indicator" type="button" data-step-label="3">Review</button>
            </div>

            <form action="enroll.php" method="POST" id="enrollmentForm">
                <div class="form-step active" id="step1">
                    <div class="form-section-heading">
                        <p class="eyebrow">Step 1</p>
                        <h2>Personal Information</h2>
                    </div>

                    <div class="form-grid">
                        <label>
                            First Name
                            <input type="text" name="firstname" placeholder="Juan" required>
                        </label>

                        <label>
                            Middle Name
                            <input type="text" name="middlename" placeholder="Optional">
                        </label>

                        <label>
                            Last Name
                            <input type="text" name="lastname" placeholder="Dela Cruz" required>
                        </label>

                        <label>
                            Birthdate
                            <input type="date" name="birthdate" id="birthdate" required>
                        </label>

                        <label>
                            Age
                            <input type="text" name="age" id="age" placeholder="Auto-computed" readonly>
                        </label>

                        <label>
                            Gender
                            <select name="gender" required>
                                <option value="">Select gender</option>
                                <option>Male</option>
                                <option>Female</option>
                            </select>
                        </label>

                        <label>
                            Nationality
                            <input type="text" name="nationality" placeholder="Filipino" required>
                        </label>

                        <label>
                            Phone Number
                            <input type="text" name="phone" placeholder="09XXXXXXXXX" required>
                        </label>

                        <label>
                            Email Address
                            <input type="email" name="email" placeholder="student@example.com" required>
                        </label>

                        <label>
                            Parent / Guardian Contact
                            <input type="text" name="guardian_phone" placeholder="09XXXXXXXXX" required>
                        </label>

                        <label class="span-2">
                            Complete Address
                            <input type="text" name="address" placeholder="House no., street, barangay, city" required>
                        </label>
                    </div>

                    <div class="form-actions">
                        <a class="button secondary" href="index.php">Cancel</a>
                        <button class="button primary" type="button" onclick="nextStep()">Continue</button>
                    </div>
                </div>

                <div class="form-step" id="step2">
                    <div class="form-section-heading">
                        <p class="eyebrow">Step 2</p>
                        <h2>Academic Information</h2>
                    </div>

                    <div class="form-grid">
                        <label>
                            Grade Level
                            <select name="grade_level" required>
                                <option value="">Select grade level</option>
                                <option>11</option>
                                <option>12</option>
                            </select>
                        </label>

                        <label>
                            Strand
                            <select name="strand" required>
                                <option value="">Select strand</option>
                                <option>HUMSS</option>
                                <option>ABM</option>
                                <option>STEM</option>
                                <option>TVL (Programming)</option>
                                <option>TVL (Cookery)</option>
                            </select>
                        </label>

                        <label>
                            Previous School
                            <input type="text" name="previous_school" placeholder="School name" required>
                        </label>

                        <label>
                            School Year
                            <input type="text" name="school_year" placeholder="2026-2027" required>
                        </label>
                    </div>

                    <div class="form-actions">
                        <button class="button secondary" type="button" onclick="prevStep()">Back</button>
                        <button class="button primary" type="button" onclick="reviewStep()">Review Application</button>
                    </div>
                </div>

                <div class="form-step" id="step3">
                    <div class="form-section-heading">
                        <p class="eyebrow">Step 3</p>
                        <h2>Review and Submit</h2>
                    </div>

                    <div class="review-grid" id="reviewGrid">
                        <p>Review summary will appear here.</p>
                    </div>

                    <div class="notice">
                        By submitting, you confirm that all information is true and correct.
                    </div>

                    <div class="form-actions">
                        <button class="button secondary" type="button" onclick="backToAcademic()">Back</button>
                        <button class="button primary" type="submit">Submit Enrollment</button>
                    </div>
                </div>
            </form>
        </section>
    </main>

    <script>
        const birthdateInput = document.getElementById("birthdate");
        const ageInput = document.getElementById("age");
        const stepButtons = document.querySelectorAll(".step-indicator");
        const form = document.getElementById("enrollmentForm");

        birthdateInput.addEventListener("change", function() {
            const birthdate = new Date(this.value);
            const today = new Date();
            let age = today.getFullYear() - birthdate.getFullYear();
            const month = today.getMonth() - birthdate.getMonth();

            if (month < 0 || (month === 0 && today.getDate() < birthdate.getDate())) {
                age--;
            }

            ageInput.value = Number.isFinite(age) ? age : "";
        });

        function setStep(stepNumber) {
            document.querySelectorAll(".form-step").forEach((step) => {
                step.classList.remove("active");
            });

            document.getElementById("step" + stepNumber).classList.add("active");

            stepButtons.forEach((button, index) => {
                button.classList.toggle("active", index < stepNumber);
            });
        }

        function validateVisibleStep(stepId) {
            const fields = document.querySelectorAll("#" + stepId + " input, #" + stepId + " select");
            let isValid = true;

            fields.forEach((field) => {
                field.classList.remove("invalid");
                if (!field.checkValidity()) {
                    field.classList.add("invalid");
                    isValid = false;
                }
            });

            return isValid;
        }

        function nextStep() {
            if (validateVisibleStep("step1")) {
                setStep(2);
            }
        }

        function prevStep() {
            setStep(1);
        }

        function backToAcademic() {
            setStep(2);
        }

        function reviewStep() {
            if (!validateVisibleStep("step2")) {
                return;
            }

            const data = new FormData(form);
            const fields = [
                ["Full Name", `${data.get("firstname")} ${data.get("middlename")} ${data.get("lastname")}`.replace(/\s+/g, " ").trim()],
                ["Birthdate", data.get("birthdate")],
                ["Age", data.get("age")],
                ["Gender", data.get("gender")],
                ["Phone", data.get("phone")],
                ["Email", data.get("email")],
                ["Grade Level", data.get("grade_level")],
                ["Strand", data.get("strand")],
                ["Previous School", data.get("previous_school")],
                ["School Year", data.get("school_year")]
            ];

            document.getElementById("reviewGrid").innerHTML = fields.map(([label, value]) => `
                <article>
                    <span>${label}</span>
                    <strong>${value || "Not provided"}</strong>
                </article>
            `).join("");

            setStep(3);
        }

        form.addEventListener("input", (event) => {
            event.target.classList.remove("invalid");
        });
    </script>
</body>
</html>
