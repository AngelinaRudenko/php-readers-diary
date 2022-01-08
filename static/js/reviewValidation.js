// validates date read when changed
let dateRead = document.getElementById('dateRead');
dateRead.addEventListener("change", function() {
    check('dateRead');
}, false);

// validates grade while typing
let grade = document.getElementById('grade');
grade.addEventListener("change", function() {
    check('grade');
}, false);

// validates comment while typing
let comment = document.getElementById('comment');
comment.addEventListener("keyup", function() {
    check('comment');
}, false);

// validates note while typing
let note = document.getElementById('note');
note.addEventListener("keyup", function() {
    check('note');
}, false);

let form = document.getElementById("reviewForm");
form.addEventListener("submit", onSubmitReviewValidation, true);

// validate fields on submit
function onSubmitReviewValidation(event) {
    if (!dateRead.value) {
        printError(event, "Date read is required");
    } else if (new Date(dateRead.value) > Date.now()) {
        printError(event, "Date read can't be in future");
    } else if (grade.value && grade.value < 1) {
        printError(event, "Grade can't be less then 1");
    } else if (grade.value && grade.value > 10) {
        printError(event, "Grade can't be more then 10");
    } else if (comment.value.length > 2000) {
        printError(event, "Comment length must be no more than 2000 characters");
    } else if (note.value.length > 2000) {
        printError(event, "Note length must be no more than 2000 characters");
    }
}