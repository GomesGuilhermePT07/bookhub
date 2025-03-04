let index = 0;

const slides = document.querySelector('.slides');
const totalSlides = document.querySelectorAll('.slide').length;

document.querySelector('.next').addEventListener('click', () => moveSlide(1));
document.querySelector('.prev').addEventListener('click', () => moveSlide(-1));

function moveSlide(direction) {
    index = (index + direction + totalSlides) % totalSlides;
    slides.style.transform = `translateX(-${index * 100}%)`;
}

setInterval(() => moveSlide(1), 4000); // Alterna automaticamente a cada 4 segundos