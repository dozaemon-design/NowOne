/**
 * =========================================================
 * Global Navigation Controller
 * ---------------------------------------------------------
 * 役割：
 * - SP時のグローバルナビ開閉制御
 * - フォーカストラップ（アクセシビリティ）
 * - bodyスクロール制御
 *
 * 対象：
 * - .c-menu-toggle
 * - .p-global-nav
 *
 * 設計方針：
 * - BEMクラスのみ操作
 * - is-xxx クラスで状態管理
 * - HTML構造には依存しない
 *
 * 注意：
 * - PC時は常時表示
 * - レイアウト制御はCSS責務
 * =========================================================
 */

document.addEventListener('DOMContentLoaded', () => {

  /* =====================================================
   * DOM取得
   * ===================================================== */
  const toggle = document.querySelector('.c-menu-toggle');
  const nav = document.querySelector('.p-global-nav');
  const body = document.body;

  // ナビが存在しないページでは何もしない
  if (!toggle || !nav) return;


  /* =====================================================
   * 業界標準定義（触らないゾーン）
   * ===================================================== */
  const focusableSelectors = `
    a[href],
    button:not([disabled]),
    input:not([disabled]),
    textarea:not([disabled]),
    select:not([disabled]),
    [tabindex]:not([tabindex="-1"])
  `;


  /* =====================================================
   * 状態管理用変数
   * ===================================================== */
  let focusableItems;
  let firstItem;
  let lastItem;

  /* ==========================
    ユーティリティ
  ========================== */

  // SP判定
  const isSP = () => {
    return window.matchMedia('(max-width: 1023px)').matches;
  };

  // aria同期
  const syncAriaState = () => {
    if (isSP()) {
      nav.setAttribute(
        'aria-hidden',
        !nav.classList.contains('is-open')
      );
      toggle.setAttribute(
        'aria-expanded',
        nav.classList.contains('is-open')
      );
    } else {
      nav.removeAttribute('aria-hidden');
      toggle.setAttribute('aria-expanded', 'false');
    }
  };

  /* =====================================================
   * ナビを開く
   * ===================================================== */
const openMenu = () => {
  nav.classList.add('is-open');
  body.classList.add('is-locked');

  syncAriaState();

  focusableItems = nav.querySelectorAll(focusableSelectors);
  firstItem = focusableItems[0];
  lastItem = focusableItems[focusableItems.length - 1];
  firstItem?.focus();
};


  /* =====================================================
   * ナビを閉じる
   * ===================================================== */
  const closeMenu = () => {
    nav.classList.remove('is-open');
    body.classList.remove('is-locked');

    syncAriaState();
    toggle.focus();
  };


  /* =====================================================
   * イベント登録
   * ===================================================== */

  // トグルクリック
  toggle.addEventListener('click', () => {
    nav.classList.contains('is-open')
      ? closeMenu()
      : openMenu();
  });

  // ESCキー
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && nav.classList.contains('is-open')) {
      closeMenu();
    }
  });

  // Tabフォーカストラップ
  nav.addEventListener('keydown', (e) => {
    if (e.key !== 'Tab') return;
    if (!firstItem || !lastItem) return;

    if (e.shiftKey && document.activeElement === firstItem) {
      e.preventDefault();
      lastItem.focus();
    }

    if (!e.shiftKey && document.activeElement === lastItem) {
      e.preventDefault();
      firstItem.focus();
    }
  });
  // リサイズ対応
  // window.addEventListener('resize', syncAriaState);
  window.addEventListener('resize', () => {
    if (!isSP() && nav.classList.contains('is-open')) {
      closeMenu();
    }
    syncAriaState();
  });
  /* ==========================
    初期化
  ========================== */
  syncAriaState();
});