document.addEventListener("wheel", function (event) {
  event.preventDefault(); // Prevent default scrolling
  window.scrollBy({
      top: event.deltaY * 5, // Multiply by a lower value => slow scrolling
      behavior: "smooth"
  });
}, { passive: false });
