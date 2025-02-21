// Popular Products Slider Functionality
class ProductSlider {
    constructor() {
        this.slider = document.querySelector('.slider-wrapper');
        this.slides = document.querySelectorAll('.slide');
        this.prevBtn = document.querySelector('.prev');
        this.nextBtn = document.querySelector('.next');
        this.currentIndex = 0;
        this.slidesToShow = window.innerWidth < 768 ? 1 : 3;
        this.slideWidth = window.innerWidth < 768 ? window.innerWidth - 40 : 320;
        
        if (this.slider && this.slides.length > 0) {
            this.init();
            window.addEventListener('resize', () => {
                this.slidesToShow = window.innerWidth < 768 ? 1 : 3;
                this.slideWidth = window.innerWidth < 768 ? window.innerWidth - 40 : 320;
                this.updateButtonStates();
                this.slide('current');
            });
        }
    }

    init() {
        // Add click events
        this.prevBtn.addEventListener('click', () => this.slide('prev'));
        this.nextBtn.addEventListener('click', () => this.slide('next'));
        
        // Initial button state
        this.updateButtonStates();
    }

    slide(direction) {
        const maxIndex = Math.ceil(this.slides.length / this.slidesToShow) - 1;
        
        if (direction === 'prev') {
            this.currentIndex = Math.max(0, this.currentIndex - 1);
        } else {
            this.currentIndex = Math.min(maxIndex, this.currentIndex + 1);
        }

        const translateX = -(this.currentIndex * (this.slideWidth * this.slidesToShow));
        this.slider.style.transform = `translateX(${translateX}px)`;
        
        this.updateButtonStates();
    }

    updateButtonStates() {
        const maxIndex = Math.ceil(this.slides.length / this.slidesToShow) - 1;
        
        // Update prev button
        this.prevBtn.disabled = this.currentIndex <= 0;
        this.prevBtn.style.opacity = this.currentIndex <= 0 ? '0.5' : '1';
        
        // Update next button
        this.nextBtn.disabled = this.currentIndex >= maxIndex;
        this.nextBtn.style.opacity = this.currentIndex >= maxIndex ? '0.5' : '1';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new ProductSlider();
});
