/**
 * =========================================================
 * Home text Animation Controller
 * ---------------------------------------------------------
 * 役割：
 * - ホームのテキストアニメーション制御
 * 対象：
 * -
 * 設計方針：
 * - BEMクラスのみ操作
 * - Splitting.js使用
 * 注意：
 * -
 * =========================================================
 */
document.addEventListener('DOMContentLoaded', () => {
  const title = document.querySelector('.js-split');
  if (!title) return;

  Splitting({
    target: title,
    by: 'chars' // words, chars, linesがパラメータとしてある。
  });

  requestAnimationFrame(() => {
    title.classList.add('is-visible');
  });
});


// =========================================================
// * Home Title Parallax Effect
// ---------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
  const title = document.querySelector('.p-home__title');
  if (!title) return;

  const reduceMotion = window.matchMedia(
    '(prefers-reduced-motion: reduce)'
  ).matches;

  if (reduceMotion) return;

  let t = 0;

  function parallax() {
    t += 0.01; // 揺れスピード
    const y = Math.sin(t) * 4; // 振幅(px)

    title.style.setProperty('--parallax-y', `${y}px`);
    requestAnimationFrame(parallax);
  }

  requestAnimationFrame(parallax);
});