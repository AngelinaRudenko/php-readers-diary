// validates username while typing
let username = document.getElementById('username');
username.addEventListener("keyup", function () {
    check('username');
});

// validates email while typing
let email = document.getElementById('email');
email.addEventListener("keyup", function () {
    check('email');
});

// validates password and confirmPassword while typing
let password = document.getElementById('password');
password.addEventListener("keyup", function () {
    checkConfirmPassword(password.value)
});
password.addEventListener("keyup", function () {
    check('password');
});

let confirmPassword = document.getElementById('confirmPassword');
confirmPassword.addEventListener("keyup", function () {
    checkConfirmPassword(password.value)
});

let form = document.getElementById("registrationForm");
form.addEventListener("submit", onSubmit, true);

// check if confirm password is correct
function checkConfirmPassword(passwordValue) {
    let elementOutput = document.getElementById("confirmPasswordValidationError");

    if (passwordValue === confirmPassword.value) {
        showValid(confirmPassword, elementOutput);
    } else {
        showInvalid(confirmPassword, elementOutput, 'Passwords don\'t match');
    }
}

// validate fields on submit
function onSubmit(event) {
    let confirmPassword = document.getElementById('confirmPassword');

    if (!username.value) {
        printError(event, "Username is required");
    } else if (username.value !== username.value.trim() || username.value.indexOf(" ") > 0) {
        printError(event, "Username should not have spaces or tabs");
    } else if (/[\'^£$%&*()}{@#~?><>,|=_+¬-]/.test(username.value)) {
        printError(event, "Username should not have special characters");
    } else if (username.value.length < 6) {
        printError(event, "Username length must be at least 6 characters");
    } else if (username.value.length > 50) {
        printError(event, "Username length must be no more than 50 characters");
    } else if (!email.value) {
        printError(event, "Email is required");
    } else if (email.value > 254) {
        printError(event, "Email is too long");
    } else if (!password.value) {
        printError(event, "Password is required");
    } else if (password.value !== password.value.trim() || password.value.indexOf(" ") > 0) {
        printError(event, "Password should not have spaces or tabs");
    } else if (/[\'^£$%&*()}{@#~?><>,|=_+¬-]/.test(password.value)) {
        printError(event, "Password should not have special characters");
    } else if (password.value.length < 8) {
        printError(event, "Password length must be at least 8 characters");
    } else if (password.value.length > 50) {
        printError(event, "Password length must be no more than 50 characters");
    } else if (password.value != confirmPassword.value) {
        printError(event, "Passwords don't match");
    }
}


