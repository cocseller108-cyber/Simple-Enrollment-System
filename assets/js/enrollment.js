const form = document.getElementById("enrollmentForm");
const birthdate = document.getElementById("birthdate");
const age = document.getElementById("age");
const message = document.getElementById("formMessage");
const reviewGrid = document.getElementById("reviewGrid");

const fieldNames = {
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

function fieldName(field) {
    return fieldNames[field.name] || "This field";
}

function goToStep(stepNumber) {
    document.querySelectorAll(".form-step").forEach(function (step) {
        step.classList.remove("active");
    });

    document.getElementById("step" + stepNumber).classList.add("active");

    document.querySelectorAll(".step-indicator").forEach(function (button, index) {
        button.classList.toggle("active", index < stepNumber);
    });

    showMessage("");
}

function checkPhone(value) {
    return /^(09\d{9}|\+639\d{9})$/.test(value);
}

function checkSchoolYear(value) {
    return /^\d{4}-\d{4}$/.test(value);
}

function getFieldError(field) {
    const value = field.value.trim();

    if (field.required && value === "") {
        return fieldName(field) + " is required.";
    }

    if (field.type === "email" && value !== "" && !field.checkValidity()) {
        return "Enter a valid email address.";
    }

    if ((field.name === "phone" || field.name === "guardian_phone") && !checkPhone(value)) {
        return fieldName(field) + " must use 09XXXXXXXXX or +639XXXXXXXXX format.";
    }

    if (field.name === "school_year" && !checkSchoolYear(value)) {
        return "School year must use the format 2026-2027.";
    }

    if (field.name === "age" && (Number(value) < 10 || Number(value) > 80)) {
        return "Please check the birthdate. The computed age looks invalid.";
    }

    return "";
}

function validateField(field) {
    const error = getFieldError(field);
    field.classList.toggle("invalid", error !== "");
    return error;
}

function validateStep(stepNumber) {
    const fields = document.querySelectorAll("#step" + stepNumber + " input, #step" + stepNumber + " select");
    let firstError = "";

    fields.forEach(function (field) {
        const error = validateField(field);

        if (firstError === "" && error !== "") {
            firstError = error;
        }
    });

    showMessage(firstError);
    return firstError === "";
}

function updateAge() {
    const selectedDate = new Date(birthdate.value);

    if (birthdate.value === "") {
        age.value = "";
        return;
    }

    const today = new Date();
    let computedAge = today.getFullYear() - selectedDate.getFullYear();

    const birthdayNotYetPassed =
        today.getMonth() < selectedDate.getMonth() ||
        (today.getMonth() === selectedDate.getMonth() && today.getDate() < selectedDate.getDate());

    if (birthdayNotYetPassed) {
        computedAge--;
    }

    age.value = computedAge;
    validateField(age);
}

function showReview() {
    const data = new FormData(form);

    const rows = [
        ["Full Name", [data.get("firstname"), data.get("middlename"), data.get("lastname")].join(" ").replace(/\s+/g, " ").trim()],
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

    let html = "";

    rows.forEach(function (row) {
        const label = row[0];
        const value = row[1] || "Not provided";

        html += "<article>";
        html += "<span>" + label + "</span>";
        html += "<strong>" + value + "</strong>";
        html += "</article>";
    });

    reviewGrid.innerHTML = html;
}

function handleButtonClick(button) {
    const action = button.dataset.formAction;

    if (action === "next" && validateStep(1)) {
        goToStep(2);
    }

    if (action === "previous") {
        goToStep(1);
    }

    if (action === "academic") {
        goToStep(2);
    }

    if (action === "review" && validateStep(2)) {
        showReview();
        goToStep(3);
    }
}

document.querySelectorAll("[data-form-action]").forEach(function (button) {
    button.addEventListener("click", function () {
        handleButtonClick(button);
    });
});

birthdate.addEventListener("change", updateAge);

form.addEventListener("input", function (event) {
    event.target.classList.remove("invalid");
    showMessage("");
});

form.addEventListener("submit", function (event) {
    if (!validateStep(1)) {
        event.preventDefault();
        goToStep(1);
        validateStep(1);
        return;
    }

    if (!validateStep(2)) {
        event.preventDefault();
        goToStep(2);
        validateStep(2);
    }
});
