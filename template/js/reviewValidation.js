let dateRead = document.getElementById('dateRead');
dateRead.addEventListener("change", function() {
    check('dateRead');
}, false);

let grade = document.getElementById('grade');
grade.addEventListener("change", function() {
    check('grade');
}, false);

let comment = document.getElementById('comment');
comment.addEventListener("keyup", function() {
    check('comment');
}, false);

let note = document.getElementById('note');
note.addEventListener("keyup", function() {
    check('note');
}, false);