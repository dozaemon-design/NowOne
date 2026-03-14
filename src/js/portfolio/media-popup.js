(() => {
  const selector = '[data-portfolio-popup="1"]';
  const FADE_MS = 250;

  const buildModal = () => {
    const overlay = document.createElement('div');
    overlay.className = 'p-portfolio-popup';
    overlay.setAttribute('aria-hidden', 'true');

    overlay.innerHTML = `
      <div class="p-portfolio-popup__backdrop" data-popup-close="1"></div>
      <div class="p-portfolio-popup__panel" role="dialog" aria-modal="true">
        <button type="button" class="p-portfolio-popup__close" data-popup-close="1"></button>
        <div class="p-portfolio-popup__content">
          <figure class="p-portfolio-popup__figure">
            <img class="p-portfolio-popup__img" alt="" decoding="async" loading="eager">
            <figcaption class="p-portfolio-popup__caption"></figcaption>
          </figure>
        </div>
      </div>
    `;

    document.body.appendChild(overlay);
    return overlay;
  };

  const getModal = (() => {
    let modal = null;
    return () => {
      if (!modal) modal = buildModal();
      return modal;
    };
  })();

  const open = ({ src, alt, title }) => {
    const modal = getModal();
    const img = modal.querySelector('.p-portfolio-popup__img');
    const caption = modal.querySelector('.p-portfolio-popup__caption');

    modal.classList.remove('is-closing');
    img.src = src;
    img.alt = alt || '';
    caption.textContent = title || '';

    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    document.documentElement.classList.add('is-portfolio-popup-open');
  };

  const close = () => {
    const modal = getModal();
    const img = modal.querySelector('.p-portfolio-popup__img');
    const caption = modal.querySelector('.p-portfolio-popup__caption');

    if (!modal.classList.contains('is-open')) return;

    modal.classList.add('is-closing');
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    document.documentElement.classList.remove('is-portfolio-popup-open');

    window.setTimeout(() => {
      if (modal.classList.contains('is-open')) return;
      img.removeAttribute('src');
      img.alt = '';
      caption.textContent = '';
      modal.classList.remove('is-closing');
    }, FADE_MS);
  };

  document.addEventListener('click', (e) => {
    const trigger = e.target.closest(selector);
    if (trigger) {
      const src = trigger.getAttribute('data-popup-src') || trigger.getAttribute('href') || '';
      if (!src) return;

      e.preventDefault();
      open({
        src,
        alt: trigger.getAttribute('data-popup-alt') || '',
        title: trigger.getAttribute('data-popup-title') || '',
      });
      return;
    }

    if (e.target && e.target.closest && e.target.closest('[data-popup-close="1"]')) {
      e.preventDefault();
      close();
    }
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const modal = document.querySelector('.p-portfolio-popup.is-open');
      if (modal) close();
    }
  });
})();

