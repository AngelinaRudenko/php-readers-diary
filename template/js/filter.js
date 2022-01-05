let minRating = document.getElementById('minRating');
let maxRating = document.getElementById('maxRating');
let output = document.getElementById('ratingFilterValidation');

minRating.addEventListener("change", function() {
    validateMinMaxRating()
});

maxRating.addEventListener("change", function() {
    validateMinMaxRating();
});

function validateMinMaxRating() {
    if (maxRating && maxRating.value && maxRating.value < minRating.value) {
        minRating.classList.add('invalid');
        minRating.classList.remove('valid');
        maxRating.classList.add('invalid');
        maxRating.classList.remove('valid');
        output.innerText = "Max rating can't be less then min rating";
        return;
    }
    minRating.classList.add('valid');
    minRating.classList.remove('invalid');
    maxRating.classList.add('valid');
    maxRating.classList.remove('invalid');
    output.innerText = '';
}