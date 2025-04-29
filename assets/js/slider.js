let index = 0;

const slides = document.querySelector('.slides');
const totalSlides = document.querySelectorAll('.slide').length;

document.querySelector('.next').addEventListener('click', () => moveSlide(1));
document.querySelector('.prev').addEventListener('click', () => moveSlide(-1));

function moveSlide(direction) {
    index = (index + direction + totalSlides) % totalSlides;
    slides.style.transform = `translateX(-${index * 100}%)`;
}

setInterval(() => moveSlide(1), 2000); // Alterna automaticamente a cada 2 segundos

function adjustSliderHeight() {
    const slider = document.querySelector('.slider');
    const width = slider.offsetWidth;
    slider.style.height = (width * 0.5625) + 'px';
}

// Executar no carregamento e no redimensionamento da janela
// window.addEventListener('load', adjustSliderHeight);
// window.addEventListener('resize', adjustSliderHeight);