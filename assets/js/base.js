/**
 * =========================================================
 * Global Base
 * ---------------------------------------------------------
 * 役割：
// base.js に書くもの
  - body スクロールロック補助
  - iOS / Safari 対策
  - CSS変数制御
  - 共通ユーティリティ
  - 初期化ログ（開発用）
 * =========================================================
 */

const isSP = () =>
  window.matchMedia('(max-width: 1023px)').matches;

const lockBody = () =>
  document.body.classList.add('is-locked');

const unlockBody = () =>
  document.body.classList.remove('is-locked');