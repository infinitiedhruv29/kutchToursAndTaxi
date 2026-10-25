// Kutch Tours & Taxi — Continuous Circular Wheel Carousel
// Seamless infinite loop rotating continuously like a wheel without rewinding

function initCarousel(el) {
  const track = el.querySelector('.car-track');
  const originalSlides = Array.from(el.querySelectorAll('.car-slide'));
  const dots = el.querySelectorAll('.car-dot');
  const btnPrev = el.querySelector('.car-prev');
  const btnNext = el.querySelector('.car-next');
  const total = originalSlides.length;
  if (total <= 1) return;

  // Clone first and last slides for infinite circular loop
  const firstClone = originalSlides[0].cloneNode(true);
  const lastClone = originalSlides[total - 1].cloneNode(true);
  firstClone.classList.add('car-clone');
  lastClone.classList.add('car-clone');

  track.appendChild(firstClone);
  track.insertBefore(lastClone, track.firstChild);

  // current index: 1 corresponds to original slide 0
  let current = 1;
  let isTransitioning = false;
  let timer = null;
  let startX = 0;
  let dragging = false;

  const transitionDuration = '0.85s';
  const transitionEase = 'cubic-bezier(0.25, 1, 0.5, 1)';

  function updateDots() {
    const activeDotIndex = (current - 1 + total) % total;
    dots.forEach((d, i) => d.classList.toggle('active', i === activeDotIndex));
  }

  function setPosition(index, animate = true) {
    const w = el.clientWidth;
    if (animate) {
      isTransitioning = true;
      track.style.transition = `transform ${transitionDuration} ${transitionEase}`;
    } else {
      track.style.transition = 'none';
    }
    track.style.transform = `translateX(-${index * w}px)`;
    updateDots();
  }

  // Handle seamless infinite wheel wrapping on transitionend
  track.addEventListener('transitionend', () => {
    isTransitioning = false;
    if (current >= total + 1) {
      // Reached past the last slide (on firstClone) -> snap seamlessly to slide 0 without animation
      current = 1;
      setPosition(current, false);
    } else if (current <= 0) {
      // Reached before first slide (on lastClone) -> snap seamlessly to slide N-1 without animation
      current = total;
      setPosition(current, false);
    }
  });

  function next() {
    if (isTransitioning) return;
    current++;
    setPosition(current, true);
  }

  function prev() {
    if (isTransitioning) return;
    current--;
    setPosition(current, true);
  }

  function goTo(slideIndex) {
    if (isTransitioning) return;
    current = slideIndex + 1;
    setPosition(current, true);
  }

  // Auto-play: natural, relaxed interval (5.5s)
  function startAuto() {
    stopAuto();
    timer = setInterval(next, 5500);
  }
  function stopAuto() {
    if (timer) {
      clearInterval(timer);
      timer = null;
    }
  }

  // Navigation controls
  if (btnPrev) btnPrev.addEventListener('click', (e) => { e.stopPropagation(); stopAuto(); prev(); startAuto(); });
  if (btnNext) btnNext.addEventListener('click', (e) => { e.stopPropagation(); stopAuto(); next(); startAuto(); });

  dots.forEach((d, i) => {
    d.addEventListener('click', (e) => {
      e.stopPropagation();
      stopAuto();
      goTo(i);
      startAuto();
    });
  });

  let moved = false;

  // Touch / swipe handlers
  track.addEventListener('touchstart', e => {
    startX = e.touches[0].clientX;
    dragging = true;
    moved = false;
    stopAuto();
  }, { passive: true });

  track.addEventListener('touchmove', e => {
    if (dragging && Math.abs(e.touches[0].clientX - startX) > 10) {
      moved = true;
    }
  }, { passive: true });

  track.addEventListener('touchend', e => {
    if (!dragging) return;
    dragging = false;
    const dx = e.changedTouches[0].clientX - startX;
    if (dx < -40) next();
    else if (dx > 40) prev();
    startAuto();
  }, { passive: true });

  // Mouse drag
  track.addEventListener('mousedown', e => {
    startX = e.clientX;
    dragging = true;
    moved = false;
    stopAuto();
  });

  window.addEventListener('mousemove', e => {
    if (dragging && Math.abs(e.clientX - startX) > 10) {
      moved = true;
    }
  });

  window.addEventListener('mouseup', e => {
    if (!dragging) return;
    dragging = false;
    const dx = e.clientX - startX;
    if (dx < -40) next();
    else if (dx > 40) prev();
    startAuto();
  });

  // Clicking anywhere on carousel image/slide advances the slide
  track.addEventListener('click', e => {
    if (moved) {
      moved = false;
      return;
    }
    if (e.target.closest('.car-prev, .car-next, .car-dots, a, button')) return;
    stopAuto();
    next();
    startAuto();
  });

  // Pause on hover
  el.addEventListener('mouseenter', stopAuto);
  el.addEventListener('mouseleave', startAuto);

  // Resize handler
  window.addEventListener('resize', () => {
    setPosition(current, false);
  });

  // Initialize at slide 0 (DOM index 1)
  setPosition(1, false);
  startAuto();
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.carousel').forEach(initCarousel);
});
