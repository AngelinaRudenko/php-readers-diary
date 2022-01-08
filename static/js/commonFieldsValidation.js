// Key - value dictionary. Key - field name, value - route
let dictionary = {
    "username": "validateUsername",
    "email": "validateEmail",
    "password": "validatePassword",
    "bookName": "validateBookName",
    "bookAuthor": "validateBookAuthor",
    "bookDescription": "validateBookDescription",
    "dateRead": "validateReviewDateRead",
    "grade": "validateReviewGrade",
    "comment": "validateReviewComment",
    "note": "validateReviewNote"
}

let timer = null;
// let xhr = null  already declared in colorTheme.js

// set timer for validation
function check(elementId) {
    if (timer != null) {
        clearTimeout(timer);
        timer = null;
    }
    timer = setTimeout(function () {
        send(elementId);
    }, 200);
}

// send request to controller to validate field
function send(elementId) {
    let input = document.getElementById(elementId);
    if (xhr != null) {
        xhr.abort();
    }
    xhr = new XMLHttpRequest();
    xhr.addEventListener('load', function () {
        show(elementId);
    }, false);
    xhr.open('POST', '/' + dictionary[elementId]);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(elementId + "=" + encodeURIComponent(input.value)); // POST
}

// show errors or success
function show(elementId) {
    let element = document.getElementById(elementId);
    let elementOutput = document.getElementById(elementId + "ValidationError");

    if (xhr.response === 'OK') {
        showValid(element, elementOutput);
    } else {
        showInvalid(element, elementOutput, xhr.response);
    }
    xhr = null;
}

// show valid
function showValid(element, elementOutput) {
    element.classList.add('valid');
    element.classList.remove('invalid');
    elementOutput.innerText = '';
}

// show invalid
function showInvalid(element, elementOutput, errorMessage) {
    element.classList.add('invalid');
    element.classList.remove('valid');
    elementOutput.innerText = errorMessage;
}

function printError(event, message) {
    event.preventDefault();
    let child = document.getElementById('errorsOutput');
    let parent = document.getElementById('errorSection');

    if (child) {
        parent.removeChild(child);
    }

    let ul = document.createElement('ul');
    ul.setAttribute('id', 'errorsOutput');
    ul.setAttribute('class', 'alert-block alert-error');

    parent.appendChild(ul)
    let li = document.createElement('li');
    ul.appendChild(li);
    li.innerHTML = message;
}