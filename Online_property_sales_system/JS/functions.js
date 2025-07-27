document.addEventListener("DOMContentLoaded", function () {
    const sliderTrack = document.querySelector(".slider-track");
    const slideItems = document.querySelectorAll(".property-item");
    let currentSlide = 0;
    const totalSlides = slideItems.length;
    const visibleSlides = 3; // Number of slides visible at a time
  
    // Function to move the slider to the next set of slides
    function slideNext() {
      currentSlide++;
      if (currentSlide >= totalSlides / visibleSlides) {
        currentSlide = 0; // Reset to the beginning when the end is reached
      }
      updateSliderPosition();
    }
  
    // Update the slider's position based on the currentSlide value
    function updateSliderPosition() {
      const slideWidth = document.querySelector(".property-item").clientWidth;
      sliderTrack.style.transform = `translateX(-${currentSlide * slideWidth}px)`;
    }
  
    // Automatically slide every 3 seconds
    setInterval(slideNext, 3000);
  
    // Update slider position on window resize
    window.addEventListener('resize', updateSliderPosition);
  });
  