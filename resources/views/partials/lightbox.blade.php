<!-- LIGHTBOX -->
<div class="hidden [&.open]:flex fixed inset-0 z-[2000] bg-black/90 backdrop-blur-sm items-center justify-center"
  id="lightbox" onclick="closeLightbox(event)">
  <button
    class="absolute top-5 right-6 text-white text-[2rem] cursor-pointer bg-transparent border-none leading-none z-[2020]"
    onclick="document.getElementById('lightbox').classList.remove('open'); document.body.style.overflow = '';">✕</button>

  <!-- Prev button -->
  <button
    class="absolute left-4 md:left-8 text-white/70 hover:text-white text-[2.5rem] md:text-[3.5rem] cursor-pointer bg-black/20 hover:bg-black/40 rounded-full w-12 h-12 md:w-16 md:h-16 flex items-center justify-center transition-all duration-300 z-[2010] outline-none select-none"
    onclick="event.stopPropagation(); navigateLightbox('prev')">‹</button>

  <!-- Next button -->
  <button
    class="absolute right-4 md:right-8 text-white/70 hover:text-white text-[2.5rem] md:text-[3.5rem] cursor-pointer bg-black/20 hover:bg-black/40 rounded-full w-12 h-12 md:w-16 md:h-16 flex items-center justify-center transition-all duration-300 z-[2010] outline-none select-none"
    onclick="event.stopPropagation(); navigateLightbox('next')">›</button>

  <img id="lb-img" src="" alt=""
    class="max-w-[85vw] max-h-[80vh] md:max-w-[80vw] md:max-h-[85vh] rounded-[10px] object-contain select-none pointer-events-none">
  <div
    class="absolute bottom-6 text-white/80 text-[0.85rem] font-semibold bg-black/50 px-4 py-1.5 rounded-full select-none text-center max-w-[90vw]"
    id="lb-cap"></div>
</div>

<script>
  let currentLightboxEl = null;

  function openLightbox(el) {
    currentLightboxEl = el;
    updateLightboxContent();
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
  }

  function closeLightbox(e) {
    if (e.target.id === 'lightbox' || e.target.classList.contains('lightbox-close') || e.target.tagName === 'BUTTON' && e.target.innerText === '✕') {
      document.getElementById('lightbox').classList.remove('open');
      document.body.style.overflow = '';
      currentLightboxEl = null;
    }
  }

  function updateLightboxContent() {
    if (!currentLightboxEl) return;
    const img = currentLightboxEl.querySelector('img');
    document.getElementById('lb-img').src = img.src;
    document.getElementById('lb-img').alt = img.alt || '';
    const captionEl = currentLightboxEl.querySelector('.dest-name, span, .car-slide-caption, div:last-child');
    document.getElementById('lb-cap').innerHTML = captionEl ? captionEl.innerHTML : '';
  }

  function navigateLightbox(direction) {
    if (!currentLightboxEl) return;
    
    let selector = '.hero-gallery-item';
    if (currentLightboxEl.classList.contains('dest-gallery-item')) {
      selector = '.dest-gallery-item';
    } else if (currentLightboxEl.classList.contains('gallery-item-clickable')) {
      selector = '.gallery-item-clickable';
    } else if (currentLightboxEl.classList.contains('pkg-gallery-item')) {
      selector = '.pkg-gallery-item';
    } else if (currentLightboxEl.classList.contains('car-slide')) {
      selector = '.car-slide';
    } else {
      selector = '[onclick*="openLightbox"]';
    }
    
    const items = Array.from(document.querySelectorAll(selector));
    if (items.length === 0) return;

    let currentIndex = items.indexOf(currentLightboxEl);
    if (currentIndex === -1) {
      currentIndex = items.findIndex(item => item === currentLightboxEl || item.querySelector('img')?.src === currentLightboxEl.querySelector('img')?.src);
    }
    if (currentIndex === -1) return;

    if (direction === 'next') {
      currentIndex = (currentIndex + 1) % items.length;
    } else if (direction === 'prev') {
      currentIndex = (currentIndex - 1 + items.length) % items.length;
    }

    currentLightboxEl = items[currentIndex];
    updateLightboxContent();
  }

  document.addEventListener('keydown', (e) => {
    const lightbox = document.getElementById('lightbox');
    if (lightbox && lightbox.classList.contains('open')) {
      if (e.key === 'ArrowRight') {
        navigateLightbox('next');
      } else if (e.key === 'ArrowLeft') {
        navigateLightbox('prev');
      } else if (e.key === 'Escape') {
        lightbox.classList.remove('open');
        document.body.style.overflow = '';
        currentLightboxEl = null;
      }
    }
  });

  let touchStartX = 0;
  let touchEndX = 0;

  document.addEventListener('DOMContentLoaded', () => {
    const lb = document.getElementById('lightbox');
    if (lb) {
      lb.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
      }, { passive: true });

      lb.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        const swipeThreshold = 50;
        if (touchEndX < touchStartX - swipeThreshold) {
          navigateLightbox('next');
        } else if (touchEndX > touchStartX + swipeThreshold) {
          navigateLightbox('prev');
        }
      }, { passive: true });
    }

    let startX = 0;
    let startY = 0;

    document.querySelectorAll('.car-slide').forEach(slide => {
      slide.addEventListener('mousedown', e => {
        startX = e.clientX;
        startY = e.clientY;
      });

      slide.addEventListener('touchstart', e => {
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
      }, { passive: true });

      slide.addEventListener('click', e => {
        const clientX = e.clientX ?? 0;
        const clientY = e.clientY ?? 0;
        const dx = clientX - startX;
        const dy = clientY - startY;
        const dist = Math.sqrt(dx * dx + dy * dy);
        
        if (clientX === 0 || dist < 20 || isNaN(dist)) {
          openLightbox(slide);
        }
      });
    });
  });
</script>
