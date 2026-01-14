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
document.addEventListener('DOMContentLoaded', () => {
const revealItems = document.querySelectorAll('.js-reveal');

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
    item.style.setProperty('--delay', `${i * 0.08}s`);
    observer.observe(item);
  });
});