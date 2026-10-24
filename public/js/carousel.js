// Kutch Tours & Taxi — Bulletproof Carousel
// One full image at a time, works on all screen sizes

function initCarousel(el) {
  const track   = el.querySelector('.car-track');
  const slides  = el.querySelectorAll('.car-slide');
  const dots    = el.querySelectorAll('.car-dot');
  const btnPrev = el.querySelector('.car-prev');
  const btnNext = el.querySelector('.car-next');
  const total   = slides.length;
  if (!total) return;

  let current = 0;
  let timer   = null;
  let startX  = 0;
  let dragging = false;

  // ── Core: go to slide N ──────────────────────────────────────
  function goTo(n) {
    current = ((n % total) + total) % total;
    // Use exact pixel offset = current * carousel width
    // This is the key fix: always use clientWidth, not percentages
    const w = el.clientWidth;
    track.style.transform = `translateX(-${current * w}px)`;
    dots.forEach((d, i) => d.classList.toggle('active', i === current));
  }

  // Recalculate on resize (orientation change on mobile)
  function onResize() { goTo(current); }

  function next() { goTo(current + 1); }
  function prev() { goTo(current - 1); }

  // ── Auto play ────────────────────────────────────────────────
  // Set a relaxed delay between slides (7.5 seconds) so users can comfortably read captions and view the images
  function startAuto() { timer = setInterval(next, 7500); }
  function stopAuto()  { clearInterval(timer); timer = null; }

  // ── Arrows ───────────────────────────────────────────────────
  if (btnPrev) btnPrev.addEventListener('click', () => { stopAuto(); prev(); startAuto(); });
  if (btnNext) btnNext.addEventListener('click', () => { stopAuto(); next(); startAuto(); });

  // ── Dots ─────────────────────────────────────────────────────
  dots.forEach((d, i) => d.addEventListener('click', () => { stopAuto(); goTo(i); startAuto(); }));

  // ── Touch / swipe ────────────────────────────────────────────
  track.addEventListener('touchstart', e => {
    startX = e.touches[0].clientX;
    dragging = true;
    stopAuto();
  }, { passive: true });

  track.addEventListener('touchend', e => {
    if (!dragging) return;
    dragging = false;
    const dx = e.changedTouches[0].clientX - startX;
    if (dx < -40) next();
    else if (dx > 40) prev();
    startAuto();
  }, { passive: true });

  // ── Mouse drag ───────────────────────────────────────────────
  track.addEventListener('mousedown', e => {
    startX = e.clientX;
    dragging = true;
    stopAuto();
  });
  window.addEventListener('mouseup', e => {
    if (!dragging) return;
    dragging = false;
    const dx = e.clientX - startX;
    if (dx < -40) next();
    else if (dx > 40) prev();
    startAuto();
  });

  // ── Pause on hover ───────────────────────────────────────────
  el.addEventListener('mouseenter', stopAuto);
  el.addEventListener('mouseleave', startAuto);

  // ── Resize handler ───────────────────────────────────────────
  window.addEventListener('resize', onResize);

  // ── Init ─────────────────────────────────────────────────────
  // Disable CSS transition for initial positioning
  track.style.transition = 'none';
  goTo(0);
  // Re-enable transition after a frame
  requestAnimationFrame(() => {
    requestAnimationFrame(() => {
      // Slower, ultra-smooth transition duration (1.1s) with an elegant decelerating curve for a luxury feel
      track.style.transition = 'transform 1.1s cubic-bezier(0.25, 1, 0.5, 1)';
    });
  });

  startAuto();
}

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.carousel').forEach(initCarousel);
});
