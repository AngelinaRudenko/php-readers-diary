let username = document.getElementById('username');
username.addEventListener("keyup", function() {
    check('username');
    });

let email = document.getElementById('email');
email.addEventListener("keyup", function() {
    check('email');
});

let password = document.getElementById('password');
password.addEventListener("keyup", function () {
    checkConfirmPassword(password.value)
}, false);
password.addEventListener("keyup", function() {
    check('password');
});


let form = document.getElementById("registrationForm");
form.addEventListener("submit", onSubmit, true);

function checkConfirmPassword(passwordValue) {
    let confirmPassword = document.getElementById('confirmPassword');
    let elementOutput = document.getElementById("confirmPasswordValidationError");

    if (passwordValue === confirmPassword.value) {
        showValid(confirmPassword, elementOutput);
    } else {
        showInvalid(confirmPassword, elementOutput, 'Passwords don\'t match');
    }
}

function onSubmit(event) {
    let confirmPassword = document.getElementById('confirmPassword');
    if (password.value !== confirmPassword.value || !username.value || !email.value) {

        event.preventDefault();

        let child = document.getElementById('errorsOutput');
        let parent = document.getElementById('errorSection');

        if (child) {
            parent.removeChild(child);
        }

        let ul = document.createElement('ul');
        ul.setAttribute('id','errorsOutput');
        ul.setAttribute('class','alert-block alert-error');

        parent.appendChild(ul)
        let li = document.createElement('li');
        ul.appendChild(li);
        li.innerHTML = 'Passwords must match';
    }
}


