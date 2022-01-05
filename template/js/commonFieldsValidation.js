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
// let xhr = null; // already declared in colorTheme.js

function check(elementId) {
    if (timer != null) {
        clearTimeout(timer);
        timer = null;
    }
    timer = setTimeout(function() {
        send(elementId);
    }, 200);
}

function send(elementId) {
    let input = document.getElementById(elementId);
    if (xhr != null) {
        xhr.abort();
    }
    xhr = new XMLHttpRequest();
    xhr.addEventListener('load', function() {
        show(elementId);
    }, false);
    xhr.open('POST', '/' + dictionary[elementId]);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send(elementId + "=" + encodeURIComponent(input.value)); // POST
}

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

function showValid(element, elementOutput) {
    element.classList.add('valid');
    element.classList.remove('invalid');
    elementOutput.innerText = '';
}

function showInvalid(element, elementOutput, errorMessage) {
    element.classList.add('invalid');
    element.classList.remove('valid');
    elementOutput.innerText = errorMessage;
}