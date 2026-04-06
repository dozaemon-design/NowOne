const PAGE_TOP_SELECTOR = '.js-page-top';
const SHOW_SCROLL_Y = 160;
const FIXED_OFFSET = 24;

const updatePageTopButton = (button) => {
  const footer = button.closest('footer');

  if (!footer) {
    return;
  }

  const shouldShow = window.scrollY > SHOW_SCROLL_Y;
  const footerRect = footer.getBoundingClientRect();
  const stopLine = window.innerHeight;
  const shouldStop = footerRect.top <= stopLine;

  button.classList.toggle('is-visible', shouldShow);
  button.classList.toggle('is-stopped', shouldShow && shouldStop);
};

const mountPageTopButton = (button) => {
  let ticking = false;

  const requestUpdate = () => {
    if (ticking) {
      return;
    }

    ticking = true;
    window.requestAnimationFrame(() => {
      updatePageTopButton(button);
      ticking = false;
    });
  };

  button.addEventListener('click', () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth',
    });
  });

  window.addEventListener('scroll', requestUpdate, { passive: true });
  window.addEventListener('resize', requestUpdate);
  requestUpdate();
};

const initPageTopButtons = () => {
  const buttons = document.querySelectorAll(PAGE_TOP_SELECTOR);

  buttons.forEach((button) => {
    mountPageTopButton(button);
  });
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initPageTopButtons, { once: true });
} else {
  initPageTopButtons();
}
