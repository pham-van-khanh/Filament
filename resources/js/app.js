import './bootstrap';
import Alpine from 'alpinejs';
import Sortable from 'sortablejs';
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';

window.Alpine = Alpine;
window.Sortable = Sortable;
window.Swiper = Swiper;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-memory-slider]').forEach((element) => {
    const sliderSection = element.closest('section') || element;

    new Swiper(element, {
      loop: element.dataset.loop === 'true',
      autoplay: element.dataset.autoplay === 'true' ? { delay: 4200 } : false,
      pagination: {
        el: element.querySelector('.swiper-pagination'),
        clickable: true,
      },
      navigation: {
        nextEl: element.querySelector('.swiper-button-next') || sliderSection.querySelector('.memory-slider-next'),
        prevEl: element.querySelector('.swiper-button-prev') || sliderSection.querySelector('.memory-slider-prev'),
      },
      slidesPerView: element.dataset.slidesPerView || 1,
      spaceBetween: Number(element.dataset.spaceBetween || 16),
    });
  });

  initMemoryLightbox();
});

function initMemoryLightbox() {
  const galleries = document.querySelectorAll('[data-lightbox]');

  if (!galleries.length) {
    return;
  }

  const lightbox = document.createElement('div');
  lightbox.className = 'memory-lightbox';
  lightbox.setAttribute('aria-hidden', 'true');
  lightbox.innerHTML = `
    <button type="button" class="memory-lightbox__close" data-lightbox-close aria-label="Dong anh">
      <span aria-hidden="true">&times;</span>
    </button>
    <button type="button" class="memory-lightbox__nav memory-lightbox__nav--prev" data-lightbox-prev aria-label="Anh truoc">
      <span aria-hidden="true">&#8249;</span>
    </button>
    <button type="button" class="memory-lightbox__nav memory-lightbox__nav--next" data-lightbox-next aria-label="Anh tiep theo">
      <span aria-hidden="true">&#8250;</span>
    </button>
    <div class="memory-lightbox__stage">
      <figure class="memory-lightbox__frame" data-memory-lightbox-frame>
        <img class="memory-lightbox__image" alt="">
        <figcaption class="memory-lightbox__caption"></figcaption>
      </figure>
    </div>
    <div class="memory-lightbox__counter" aria-live="polite"></div>
  `;
  document.body.appendChild(lightbox);

  const image = lightbox.querySelector('.memory-lightbox__image');
  const caption = lightbox.querySelector('.memory-lightbox__caption');
  const counter = lightbox.querySelector('.memory-lightbox__counter');
  const prevButton = lightbox.querySelector('[data-lightbox-prev]');
  const nextButton = lightbox.querySelector('[data-lightbox-next]');

  let items = [];
  let currentIndex = 0;

  const close = () => {
    lightbox.classList.remove('is-open');
    lightbox.setAttribute('aria-hidden', 'true');
    document.documentElement.classList.remove('memory-lightbox-open');
    image.removeAttribute('src');
  };

  const update = () => {
    const item = items[currentIndex];

    if (!item) {
      return;
    }

    image.src = item.href;
    image.alt = item.label;
    caption.textContent = item.label;
    caption.hidden = !item.label;
    counter.textContent = `${currentIndex + 1} / ${items.length}`;
    prevButton.hidden = items.length < 2;
    nextButton.hidden = items.length < 2;
  };

  const open = (nextItems, index) => {
    items = nextItems;
    currentIndex = index;
    update();
    lightbox.classList.add('is-open');
    lightbox.setAttribute('aria-hidden', 'false');
    document.documentElement.classList.add('memory-lightbox-open');
  };

  const move = (direction) => {
    if (items.length < 2) {
      return;
    }

    currentIndex = (currentIndex + direction + items.length) % items.length;
    update();
  };

  galleries.forEach((gallery) => {
    gallery.addEventListener('click', (event) => {
      const link = event.target.closest('a[data-pswp-width]');

      if (!link || !gallery.contains(link)) {
        return;
      }

      event.preventDefault();

      const galleryItems = Array.from(gallery.querySelectorAll('a[data-pswp-width]')).map((anchor) => {
        const img = anchor.querySelector('img');

        return {
          href: anchor.href,
          label: (img?.getAttribute('alt') || anchor.textContent || '').trim(),
        };
      });

      const clickedIndex = galleryItems.findIndex((item) => item.href === link.href);

      open(galleryItems, clickedIndex >= 0 ? clickedIndex : 0);
    });
  });

  lightbox.addEventListener('click', (event) => {
    if (event.target.closest('[data-lightbox-close]')) {
      close();
      return;
    }

    if (event.target.closest('[data-lightbox-prev]')) {
      move(-1);
      return;
    }

    if (event.target.closest('[data-lightbox-next]')) {
      move(1);
      return;
    }

    if (!event.target.closest('[data-memory-lightbox-frame]')) {
      close();
    }
  });

  document.addEventListener('keydown', (event) => {
    if (!lightbox.classList.contains('is-open')) {
      return;
    }

    if (event.key === 'Escape') {
      close();
    }

    if (event.key === 'ArrowLeft') {
      move(-1);
    }

    if (event.key === 'ArrowRight') {
      move(1);
    }
  });
}
