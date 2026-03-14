(() => {
  const overlay = document.querySelector('.js-page-transition');
  if (!overlay) return;

  const prefersReducedMotion =
    window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  if (prefersReducedMotion) return;

  const slices = Array.from(overlay.querySelectorAll('.c-page-transition__slice'));
  const lastSlice = slices[slices.length - 1];
  if (!lastSlice) return;

  const siteOrigin = window.location.origin;
  const isSameOrigin = (url) => url.origin === siteOrigin;

  const isExcludedPath = (pathname) => {
    const p = (pathname || '/').replace(/\/+$/, '') || '/';
    if (p === '/contact' || p === '/contact-thanks') return true;
    if (p === '/portfolio' || p.startsWith('/portfolio/')) return true;
    return false;
  };

  const clearCaptures = () => {
    slices.forEach((slice) => {
      while (slice.firstChild) slice.removeChild(slice.firstChild);
    });
  };

  const reset = () => {
    overlay.classList.remove('is-active');
    overlay.dataset.state = 'idle';
    clearCaptures();
  };

  const bindEnterCleanup = () => {
    if (!overlay.classList.contains('is-active')) return;
    if ((overlay.dataset.state || '') !== 'enter') return;

    const fallback = window.setTimeout(() => reset(), 3500);

    const onEnd = (e) => {
      if (e.animationName !== 'c-page-transition-enter') return;
      lastSlice.removeEventListener('animationend', onEnd);
      window.clearTimeout(fallback);
      reset();
    };
    lastSlice.addEventListener('animationend', onEnd);
  };

  const buildCapture = () => {
    const target = document.querySelector('.l-wrap') || document.body;
    const scrollY = window.scrollY || window.pageYOffset || 0;

    const sanitizeClone = (root) => {
      root.querySelectorAll('script, noscript, iframe').forEach((el) => el.remove());
      return root;
    };

    slices.forEach((slice) => {
      const rect = slice.getBoundingClientRect();
      slice.style.setProperty('--c-page-transition-x', `${-rect.left}px`);

      const content = sanitizeClone(target.cloneNode(true));
      const holder = document.createElement('div');
      holder.className = 'c-page-transition__capture';
      holder.style.top = `${-scrollY}px`;
      holder.appendChild(content);
      slice.appendChild(holder);
    });
  };

  const runLeave = (nextUrl) => {
    if ((overlay.dataset.state || '') === 'leave') return;

    const fallbackNavTimer = window.setTimeout(() => {
      window.location.href = nextUrl;
    }, 3500);

    overlay.classList.add('is-active');
    clearCaptures();

    try {
      buildCapture();
    } catch (e) {
      window.clearTimeout(fallbackNavTimer);
      window.location.href = nextUrl;
      return;
    }

    overlay.dataset.state = 'idle';
    overlay.offsetHeight; // force reflow
    overlay.dataset.state = 'leave';

    const lastCapture = lastSlice.querySelector('.c-page-transition__capture');
    if (!lastCapture) {
      window.clearTimeout(fallbackNavTimer);
      window.location.href = nextUrl;
      return;
    }

    const onEnd = (e) => {
      if (e.animationName !== 'c-page-transition-leave-capture') return;
      lastCapture.removeEventListener('animationend', onEnd);
      window.clearTimeout(fallbackNavTimer);
      window.location.href = nextUrl;
    };
    lastCapture.addEventListener('animationend', onEnd);
  };

  window.addEventListener('pageshow', (e) => {
    if (e.persisted) {
      reset();
    }
    bindEnterCleanup();
  });

  document.addEventListener('click', (e) => {
    const anchor = e.target && e.target.closest ? e.target.closest('a') : null;
    if (!anchor) return;

    if (anchor.hasAttribute('download')) return;
    if (anchor.getAttribute('target') && anchor.getAttribute('target') !== '_self') return;
    if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0) return;

    const href = anchor.getAttribute('href');
    if (!href || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('javascript:')) return;

    let url;
    try {
      url = new URL(anchor.href);
    } catch {
      return;
    }

    if (!isSameOrigin(url)) return;
    if (isExcludedPath(url.pathname)) return;

    const normalizePath = (pathname) => (pathname || '/').replace(/\/+$/, '') || '/';
    const current = new URL(window.location.href);

    if (normalizePath(url.pathname) === normalizePath(current.pathname) && url.search === current.search) {
      return;
    }

    e.preventDefault();
    runLeave(url.href);
  });
})();

