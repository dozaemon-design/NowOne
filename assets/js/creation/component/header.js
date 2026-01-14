/**
 * =========================================================
 * Global Navigation Controller
 * ---------------------------------------------------------
 * 役割：
 * - SP時のp-homeの高さ削り
 *
 * 対象：
 * - .l-header
 *
 * 設計方針：
 * - BEMクラスのみ操作
 * - HTML構造には依存しない
 *
 * 注意：
 * - PC時は通常表示
 * - レイアウト制御はCSS責務
 * =========================================================
 */
const header = document.querySelector('.l-header');

const setHeaderHeight = () => {
  document.documentElement.style.setProperty(
    '--header-h',
    `${header.offsetHeight}px`
  );
};

setHeaderHeight();
window.addEventListener('resize', setHeaderHeight);