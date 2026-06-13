/**
 * TravelWorld Theme - Main JavaScript
 */
(function () {
  'use strict';

  const data = window.travelworldData || {};

  /* Hero Slider */
  function initHeroSlider() {
    const slider = document.querySelector('.hero-slider');
    if (!slider) return;

    const slides = slider.querySelectorAll('.hero-slide');
    const prevBtn = slider.querySelector('.hero-slider__arrow--prev');
    const nextBtn = slider.querySelector('.hero-slider__arrow--next');
    const dotsContainer = slider.querySelector('.hero-slider__dots');
    let current = 0;
    let interval;

    slides.forEach((_, i) => {
      const dot = document.createElement('button');
      dot.className = 'hero-slider__dot' + (i === 0 ? ' is-active' : '');
      dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
      dot.addEventListener('click', () => goTo(i));
      dotsContainer.appendChild(dot);
    });

    const dots = dotsContainer.querySelectorAll('.hero-slider__dot');

    function goTo(index) {
      slides[current].classList.remove('is-active');
      dots[current].classList.remove('is-active');
      current = (index + slides.length) % slides.length;
      slides[current].classList.add('is-active');
      dots[current].classList.add('is-active');
    }

    function next() { goTo(current + 1); }
    function prev() { goTo(current - 1); }

    if (prevBtn) prevBtn.addEventListener('click', prev);
    if (nextBtn) nextBtn.addEventListener('click', next);

    function startAutoplay() {
      interval = setInterval(next, 5000);
    }
    function stopAutoplay() {
      clearInterval(interval);
    }

    slider.addEventListener('mouseenter', stopAutoplay);
    slider.addEventListener('mouseleave', startAutoplay);
    startAutoplay();
  }

  /* Mobile Menu */
  function initMobileMenu() {
    const toggle = document.querySelector('.mobile-menu-toggle');
    const nav = document.querySelector('.main-nav');
    if (!toggle || !nav) return;

    toggle.addEventListener('click', () => {
      const isOpen = nav.classList.toggle('is-open');
      toggle.setAttribute('aria-expanded', isOpen);
    });
  }

  /* Inquiry Modal */
  function initInquiryModal() {
    const modal = document.getElementById('inquiry-modal');
    if (!modal) return;

    const backdrop = modal.querySelector('.modal__backdrop');
    const closeBtn = modal.querySelector('.modal__close');
    const form = document.getElementById('inquiry-form');
    const packageField = document.getElementById('inquiry-package');
    const messageEl = document.getElementById('inquiry-message');

    function openModal(packageName) {
      if (packageField && packageName) {
        packageField.value = packageName;
      }
      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    }

    function closeModal() {
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
      if (messageEl) {
        messageEl.className = 'form-message';
        messageEl.textContent = '';
      }
    }

    document.querySelectorAll('.inquiry-trigger').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const pkg = btn.getAttribute('data-package') || '';
        openModal(pkg);
      });
    });

    if (backdrop) backdrop.addEventListener('click', closeModal);
    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && modal.classList.contains('is-open')) {
        closeModal();
      }
    });

    if (form) {
      form.addEventListener('submit', (e) => {
        e.preventDefault();
        submitInquiry(form, messageEl, () => {
          setTimeout(closeModal, 2000);
        });
      });
    }
  }

  /* Contact Page Form */
  function initContactForm() {
    const form = document.getElementById('contact-inquiry-form');
    const messageEl = document.getElementById('contact-message');
    if (!form) return;

    form.addEventListener('submit', (e) => {
      e.preventDefault();
      submitInquiry(form, messageEl);
    });
  }

  /* Submit Inquiry via AJAX */
  function submitInquiry(form, messageEl, onSuccess) {
    const formData = new FormData(form);
    formData.append('action', 'travelworld_inquiry');
    formData.append('nonce', data.nonce);

    const submitBtn = form.querySelector('[type="submit"]');
    if (submitBtn) {
      submitBtn.disabled = true;
      submitBtn.textContent = 'Sending...';
    }

    fetch(data.ajaxUrl, {
      method: 'POST',
      body: formData,
    })
      .then((res) => res.json())
      .then((response) => {
        if (messageEl) {
          if (response.success) {
            messageEl.className = 'form-message is-success';
            messageEl.textContent = response.data.message;
            form.reset();
            if (onSuccess) onSuccess();
          } else {
            messageEl.className = 'form-message is-error';
            messageEl.textContent = response.data.message || 'Something went wrong.';
          }
        }
      })
      .catch(() => {
        if (messageEl) {
          messageEl.className = 'form-message is-error';
          messageEl.textContent = 'Network error. Please try again.';
        }
      })
      .finally(() => {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.textContent = submitBtn.dataset.originalText || 'Submit Inquiry';
        }
      });
  }

  /* Itinerary Accordion */
  function initItineraryAccordion() {
    document.querySelectorAll('.itinerary-day__header').forEach((header) => {
      header.addEventListener('click', () => {
        const day = header.closest('.itinerary-day');
        const content = day.querySelector('.itinerary-day__content');
        const isOpen = day.classList.contains('is-open');

        document.querySelectorAll('.itinerary-day').forEach((d) => {
          d.classList.remove('is-open');
          const c = d.querySelector('.itinerary-day__content');
          if (c) c.hidden = true;
          const h = d.querySelector('.itinerary-day__header');
          if (h) h.setAttribute('aria-expanded', 'false');
        });

        if (!isOpen) {
          day.classList.add('is-open');
          if (content) content.hidden = false;
          header.setAttribute('aria-expanded', 'true');
        }
      });
    });
  }

  /* Trip Type Pills */
  function initTripTypePills() {
    document.querySelectorAll('.trip-type-pills .pill').forEach((pill) => {
      pill.addEventListener('click', () => {
        const input = pill.querySelector('input');
        if (input) input.checked = true;
      });
    });
  }

  /* Init */
  document.addEventListener('DOMContentLoaded', () => {
    initHeroSlider();
    initMobileMenu();
    initInquiryModal();
    initContactForm();
    initItineraryAccordion();
    initTripTypePills();

    document.querySelectorAll('form [type="submit"]').forEach((btn) => {
      btn.dataset.originalText = btn.textContent;
    });
  });
})();
