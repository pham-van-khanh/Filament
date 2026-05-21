import './bootstrap';
import Alpine from 'alpinejs';
import Sortable from 'sortablejs';
import Swiper from 'swiper/bundle';
import 'swiper/css/bundle';
import PhotoSwipeLightbox from 'photoswipe/lightbox';
import 'photoswipe/style.css';

window.Alpine = Alpine;
window.Sortable = Sortable;
window.Swiper = Swiper;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-memory-slider]').forEach((element) => {
    new Swiper(element, {
      loop: element.dataset.loop === 'true',
      autoplay: element.dataset.autoplay === 'true' ? { delay: 4200 } : false,
      pagination: {
        el: element.querySelector('.swiper-pagination'),
        clickable: true,
      },
      navigation: {
        nextEl: element.querySelector('.swiper-button-next'),
        prevEl: element.querySelector('.swiper-button-prev'),
      },
      slidesPerView: element.dataset.slidesPerView || 1,
      spaceBetween: Number(element.dataset.spaceBetween || 16),
    });
  });

  document.querySelectorAll('[data-lightbox]').forEach((gallery) => {
    const lightbox = new PhotoSwipeLightbox({
      gallery,
      children: 'a',
      pswpModule: () => import('photoswipe'),
    });

    lightbox.init();
  });
});
