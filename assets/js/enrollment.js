const form = document.getElementById("enrollmentForm");
const message = document.getElementById("formMessage");
const birthdate = document.getElementById("birthdate");
const age = document.getElementById("age");
const reviewGrid = document.getElementById("reviewGrid");

// Friendly names used in validation messages.
const labels = {
    firstname: "First name",
    lastname: "Last name",
    birthdate: "Birthdate",
    age: "Age",
    gender: "Gender",
    nationality: "Nationality",
    address: "Complete address",
    phone: "Phone number",
    email: "Email address",
    guardian_phone: "Parent / guardian contact",
    grade_level: "Grade level",
    strand: "Strand",
    previous_school: "Previous school",
    school_year: "School year"
};

function showMessage(text) {
    message.textContent = text;
}

function showStep(stepNumber) {
    const steps = document.querySelectorAll(".form-step");
    const buttons = document.querySelectorAll(".step-indicator");

    steps.forEach(function (step) {
        step.classList.remove("active");
    });

    document.getElementById("step" + stepNumber).classList.add("active");

    buttons.forEach(function (button, index) {
        button.classList.toggle("active", index < stepNumber);
    });

    showMessage("");
}

function markInvalid(field, errorMessage) {
    field.classList.add("invalid");
    showMessage(errorMessage);
}

function clearInvalidFields(stepNumber) {
    const fields = document.querySelectorAll("#step" + stepNumber + " input, #step" + stepNumber + " select");

    fields.forEach(function (field) {
        field.classList.remove("invalid");
    });
}

function validateStep(stepNumber) {
    const fields = document.querySelectorAll("#step" + stepNumber + " input, #step" + stepNumber + " select");

    clearInvalidFields(stepNumber);
    showMessage("");

    for (const field of fields) {
        const value = field.value.trim();
        const name = labels[field.name] || "This field";

        if (field.required && value === "") {
            markInvalid(field, name + " is required.");
            return false;
        }

        if (field.type === "email" && value !== "" && !field.checkValidity()) {
            markInvalid(field, "Enter a valid email address.");
            return false;
        }

        if ((field.name === "phone" || field.name === "guardian_phone") && !/^(09\d{9}|\+639\d{9})$/.test(value)) {
            markInvalid(field, name + " must use 09XXXXXXXXX or +639XXXXXXXXX format.");
            return false;
        }

        if (field.name === "school_year" && !/^\d{4}-\d{4}$/.test(value)) {
            markInvalid(field, "School year must use the format 2026-2027.");
            return false;
        }

        if (field.name === "age" && (Number(value) < 10 || Number(value) > 80)) {
            markInvalid(field, "Please check the birthdate. The computed age looks invalid.");
            return false;
        }
    }

    return true;
}

// Automatically compute age from birthdate.
function updateAge() {
    if (birthdate.value === "") {
        age.value = "";
        return;
    }

    const today = new Date();
    const birthday = new Date(birthdate.value);
    let computedAge = today.getFullYear() - birthday.getFullYear();

    const hasBirthdayPassed =
        today.getMonth() > birthday.getMonth() ||
        (today.getMonth() === birthday.getMonth() && today.getDate() >= birthday.getDate());

    if (!hasBirthdayPassed) {
        computedAge--;
    }

    age.value = computedAge;
}

// Add one item to the final review screen.
function addReviewItem(label, value) {
    const item = document.createElement("article");
    const itemLabel = document.createElement("span");
    const itemValue = document.createElement("strong");

    itemLabel.textContent = label;
    itemValue.textContent = value || "Not provided";

    item.appendChild(itemLabel);
    item.appendChild(itemValue);
    reviewGrid.appendChild(item);
}

// Show the student's entered information before final submit.
function showReview() {
    const data = new FormData(form);
    const fullName = [
        data.get("firstname"),
        data.get("middlename"),
        data.get("lastname")
    ].join(" ").replace(/\s+/g, " ").trim();

    reviewGrid.innerHTML = "";
    addReviewItem("Full Name", fullName);
    addReviewItem("Birthdate", data.get("birthdate"));
    addReviewItem("Age", data.get("age"));
    addReviewItem("Gender", data.get("gender"));
    addReviewItem("Phone", data.get("phone"));
    addReviewItem("Email", data.get("email"));
    addReviewItem("Grade Level", data.get("grade_level"));
    addReviewItem("Strand", data.get("strand"));
    addReviewItem("Previous School", data.get("previous_school"));
    addReviewItem("School Year", data.get("school_year"));
}

document.querySelector("[data-form-action='next']").addEventListener("click", function () {
    if (validateStep(1)) {
        showStep(2);
    }
});

document.querySelector("[data-form-action='previous']").addEventListener("click", function () {
    showStep(1);
});

document.querySelector("[data-form-action='review']").addEventListener("click", function () {
    if (validateStep(2)) {
        showReview();
        showStep(3);
    }
});

document.querySelector("[data-form-action='academic']").addEventListener("click", function () {
    showStep(2);
});

birthdate.addEventListener("change", updateAge);

form.addEventListener("input", function (event) {
    event.target.classList.remove("invalid");
    showMessage("");
});

form.addEventListener("submit", function (event) {
    if (!validateStep(1)) {
        event.preventDefault();
        showStep(1);
        validateStep(1);
        return;
    }

    if (!validateStep(2)) {
        event.preventDefault();
        showStep(2);
        validateStep(2);
    }
});
