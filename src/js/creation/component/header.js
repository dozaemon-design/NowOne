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
const getHeaderEl = () => document.querySelector('.l-header, .l-header--portfolio');

const setHeaderHeight = () => {
  const header = getHeaderEl();
  const height = header ? header.offsetHeight : 0;
  document.documentElement.style.setProperty('--header-h', `${height}px`);
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', setHeaderHeight, { once: true });
} else {
  setHeaderHeight();
}

window.addEventListener('resize', setHeaderHeight);

