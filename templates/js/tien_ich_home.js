// JavaScript cuộn theo scroll
document.addEventListener("DOMContentLoaded", function () {
    var body = document.body;

    window.addEventListener("scroll", function () {
        if (window.scrollY > 0) {
            body.classList.add("scrolling");
        } else {
            body.classList.remove("scrolling");
        }
    });
});