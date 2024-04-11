var slideIndex = 1;
showSlides(slideIndex);

function plusSlides(n) {
    showSlides(slideIndex += n);
}

function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("mySlides");
    var prevButton = document.querySelector(".prev");

    if (n > slides.length) {
        slideIndex = 1;
    }

    if (n < 1) {
        slideIndex = slides.length;
    }

    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    slides[slideIndex - 1].style.display = "block";

    // Kiểm tra nếu slide đang hiển thị là slide đầu tiên
    if (slideIndex === 1) {
        prevButton.style.pointerEvents = "none"; // Ngăn chặn sự kiện onclick của nút "prev"
    } else {
        prevButton.style.pointerEvents = "auto"; // Cho phép sự kiện onclick của nút "prev"
    }
}

