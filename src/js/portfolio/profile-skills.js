const PROFILE_SELECTOR = '.p-portfolio-profile__content';
const RATING_SELECTOR = '.p-portfolio-profile__skillRating';

const clampToHalfStep = (value) => {
  const safeValue = Number.isFinite(value) ? value : 0;
  return Math.max(0, Math.min(5, Math.round(safeValue * 2) / 2));
};

const parseRating = (rawText) => {
  const text = rawText.trim();
  if (!text) {
    return null;
  }

  const numericMatch = text.match(/(\d+(?:\.\d+)?)/);
  if (numericMatch) {
    return clampToHalfStep(Number(numericMatch[1]));
  }

  const fullStars = (text.match(/[★⭐🌟]/g) || []).length;
  const halfStars = (text.match(/[½]/g) || []).length;
  const rating = fullStars + (halfStars > 0 ? 0.5 : 0);

  return rating > 0 ? clampToHalfStep(rating) : null;
};

const formatRating = (rating) => {
  return Number.isInteger(rating) ? String(rating) : rating.toFixed(1);
};

const createRatingGauge = (rating) => {
  const gauge = document.createElement('span');
  const value = document.createElement('span');
  const track = document.createElement('span');
  const fill = document.createElement('span');
  const label = document.createElement('span');

  gauge.className = 'p-portfolio-profile__rating';
  gauge.setAttribute('aria-label', `${formatRating(rating)} / 5`);
  gauge.style.setProperty('--fill-width', `${(rating / 5) * 100}%`);

  value.className = 'p-portfolio-profile__ratingValue';
  value.textContent = formatRating(rating);

  track.className = 'p-portfolio-profile__ratingTrack';
  fill.className = 'p-portfolio-profile__ratingFill';
  track.append(fill);

  label.className = 'p-portfolio-profile__ratingStars';
  label.setAttribute('aria-hidden', 'true');
  label.textContent = '/ 5';

  gauge.append(value, track, label);
  return gauge;
};

const enhanceRatings = (content, observer) => {
  content.querySelectorAll(RATING_SELECTOR).forEach((ratingNode) => {
    if (ratingNode.querySelector('.p-portfolio-profile__rating')) {
      return;
    }

    const datasetRating = ratingNode.dataset.rating ? Number(ratingNode.dataset.rating) : null;
    const rating = Number.isFinite(datasetRating)
      ? clampToHalfStep(datasetRating)
      : parseRating(ratingNode.textContent || '');
    if (rating === null) {
      return;
    }

    ratingNode.textContent = '';
    const gauge = createRatingGauge(rating);
    ratingNode.append(gauge);

    if (observer) {
      observer.observe(gauge);
    } else {
      gauge.classList.add('is-visible');
    }
  });
};

const createObserver = () => {
  if (!('IntersectionObserver' in window)) {
    return null;
  }

  return new IntersectionObserver((entries, activeObserver) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) {
        return;
      }

      entry.target.classList.add('is-visible');
      activeObserver.unobserve(entry.target);
    });
  }, {
    threshold: 0.3,
  });
};

const initProfileSkills = () => {
  const contents = document.querySelectorAll(PROFILE_SELECTOR);
  if (!contents.length) {
    return;
  }

  const observer = createObserver();

  contents.forEach((content) => {
    enhanceRatings(content, observer);
  });
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initProfileSkills, { once: true });
} else {
  initProfileSkills();
}
