/**
 * =========================================================
 * reveal.js Controller
 * 一覽リスト用アニメーション制御
 * ---------------------------------------------------------
 * 役割：
 * - YouTube埋め込み制御
 * 対象：
 * -
 * 設計方針：
 * - BEMクラスのみ操作
 *
 * 注意：
 * -
 * =========================================================
 */
const initReveal = () => {
  const revealItems = document.querySelectorAll('.js-reveal');
  if (!revealItems.length) {
    return;
  }

  if (!('IntersectionObserver' in window)) {
    revealItems.forEach((item) => item.classList.add('is-visible'));
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible');
          observer.unobserve(entry.target);
        }
      });
    },
    {
      threshold: 0.1,
    }
  );

  revealItems.forEach((item, i) => {
    if (item.classList.contains('is-visible')) {
      return;
    }
    item.style.setProperty('--delay', `${i * 0.08}s`);
    observer.observe(item);
  });
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initReveal, { once: true });
} else {
  initReveal();
}

// BFCache/最適化ツールで DOMContentLoaded が走らない or 既に完了している場合の保険
window.addEventListener('pageshow', (e) => {
  if (e.persisted) {
    initReveal();
  }
});

