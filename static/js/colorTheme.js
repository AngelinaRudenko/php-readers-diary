let xhr = null;

// send request to controller to change color theme
let styleDropdown = document.getElementById('colorTheme');
styleDropdown.addEventListener("change", function () {
    if (xhr != null) {
        xhr.abort();
    }
    xhr = new XMLHttpRequest();
    xhr.addEventListener('load', function () {
        location.reload();
    });
    xhr.open('POST', '/changeColorTheme');
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("colorTheme=" + encodeURIComponent(styleDropdown.value)); // POST
});