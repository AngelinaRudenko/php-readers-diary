// validates book name while typing
let bookName = document.getElementById('bookName');
bookName.addEventListener("keyup", function () {
    check('bookName');
});

// validates book author while typing
let bookAuthor = document.getElementById('bookAuthor');
bookAuthor.addEventListener("keyup", function () {
    check('bookAuthor');
});

// validates book description while typing
let bookDescription = document.getElementById('bookDescription');
bookDescription.addEventListener("keyup", function () {
    check('bookDescription');
})

let form = document.getElementById("reviewForm");
form.addEventListener("submit", onSubmit, true);

// validate fields on submit
function onSubmit(event) {
    if (!bookName.value) {
        printError(event, "Book name is required");
    } else if (bookName.value.length < 1) {
        printError(event, "Book name length must be at least 1 character");
    } else if (bookName.value.length > 50) {
        printError(event, "Book name length must be no more than 50 characters");
    } else if (!bookAuthor.value.trim()) {
        printError(event, "Author is required");
    } else if (/\d/.test(bookAuthor.value)) {
        printError(event, "Author name should not have numbers");
    } else if (/[\'^£$%&*()}{@#~?><>,|=_+¬-]/.test(bookAuthor.value)) {
        printError(event, "Author name should not have special characters");
    } else if (bookAuthor.value.length < 6) {
        printError(event, "Author name length must be at least 6 characters");
    } else if (bookAuthor.value.length > 50) {
        printError(event, "Author name length must be no more than 50 characters");
    } else if (bookDescription.value && bookDescription.value.length > 2000) {
        printError(event, "Description length must be no more than 2000 characters");
    } else if (!dateRead.value) {
        printError(event, "Date read is required");
    } else if (new Date(dateRead.value) > Date.now()) {
        printError(event, "Date read can't be in future");
    } else if (grade.value && parseInt(grade.value) < 1) {
        printError(event, "Grade can't be less then 1");
    } else if (grade.value && parseInt(grade.value) > 10) {
        printError(event, "Grade can't be more then 10");
    } else if (comment.value && comment.value.length > 2000) {
        printError(event, "Comment length must be no more than 2000 characters");
    } else if (note.value && note.value.length > 2000) {
        printError(event, "Note length must be no more than 2000 characters");
    }
}