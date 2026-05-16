const form = document.getElementById("enrollmentForm");
const birthdateInput = document.getElementById("birthdate");
const ageInput = document.getElementById("age");
const stepButtons = document.querySelectorAll(".step-indicator");
const formMessage = document.getElementById("formMessage");

const fieldLabels = {
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

function showMessage(message) {
    if (!formMessage) {
        return;
    }

    formMessage.textContent = message;
}

function clearMessage() {
    showMessage("");
}

function setStep(stepNumber) {
    document.querySelectorAll(".form-step").forEach((step) => {
        step.classList.remove("active");
    });

    document.getElementById("step" + stepNumber).classList.add("active");

    stepButtons.forEach((button, index) => {
        button.classList.toggle("active", index < stepNumber);
    });

    clearMessage();
}

function getFieldLabel(field) {
    return fieldLabels[field.name] || "This field";
}

function isValidPhone(value) {
    return /^(09\d{9}|\+639\d{9})$/.test(value.trim());
}

function isValidSchoolYear(value) {
    return /^\d{4}-\d{4}$/.test(value.trim());
}

function validateField(field) {
    const value = field.value.trim();
    let message = "";

    if (field.hasAttribute("required") && value === "") {
        message = `${getFieldLabel(field)} is required.`;
    } else if (field.type === "email" && value !== "" && !field.checkValidity()) {
        message = "Enter a valid email address.";
    } else if ((field.name === "phone" || field.name === "guardian_phone") && !isValidPhone(value)) {
        message = `${getFieldLabel(field)} must use 09XXXXXXXXX or +639XXXXXXXXX format.`;
    } else if (field.name === "school_year" && !isValidSchoolYear(value)) {
        message = "School year must use the format 2026-2027.";
    } else if (field.name === "age" && (Number(value) < 10 || Number(value) > 80)) {
        message = "Please check the birthdate. The computed age looks invalid.";
    }

    field.classList.toggle("invalid", message !== "");
    return message;
}

function validateVisibleStep(stepId) {
    const fields = document.querySelectorAll("#" + stepId + " input, #" + stepId + " select");
    let firstError = "";

    fields.forEach((field) => {
        const error = validateField(field);

        if (!firstError && error) {
            firstError = error;
        }
    });

    showMessage(firstError);
    return firstError === "";
}

function updateAge() {
    const birthdate = new Date(birthdateInput.value);
    const today = new Date();
    let age = today.getFullYear() - birthdate.getFullYear();
    const month = today.getMonth() - birthdate.getMonth();

    if (month < 0 || (month === 0 && today.getDate() < birthdate.getDate())) {
        age--;
    }

    ageInput.value = Number.isFinite(age) ? age : "";
    validateField(ageInput);
}

function renderReview() {
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
}

document.querySelectorAll("[data-form-action]").forEach((button) => {
    button.addEventListener("click", () => {
        const action = button.dataset.formAction;

        if (action === "next" && validateVisibleStep("step1")) {
            setStep(2);
        }

        if (action === "previous") {
            setStep(1);
        }

        if (action === "academic") {
            setStep(2);
        }

        if (action === "review" && validateVisibleStep("step2")) {
            renderReview();
            setStep(3);
        }
    });
});

birthdateInput.addEventListener("change", updateAge);

form.addEventListener("input", (event) => {
    event.target.classList.remove("invalid");
    clearMessage();
});

form.addEventListener("submit", (event) => {
    const firstStepValid = validateVisibleStep("step1");
    const secondStepValid = validateVisibleStep("step2");

    if (!firstStepValid) {
        event.preventDefault();
        setStep(1);
        validateVisibleStep("step1");
        return;
    }

    if (!secondStepValid) {
        event.preventDefault();
        setStep(2);
        validateVisibleStep("step2");
    }
});
