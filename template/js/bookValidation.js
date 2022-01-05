let bookName = document.getElementById('bookName');
bookName.addEventListener("keyup", function() {
    check('bookName');
}, false);

let bookAuthor = document.getElementById('bookAuthor');
bookAuthor.addEventListener("keyup", function() {
    check('bookAuthor');
}, false);

let bookDescription = document.getElementById('bookDescription');
bookDescription.addEventListener("keyup", function() {
    check('bookDescription');
}, false);