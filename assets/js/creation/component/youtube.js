/**
 * =========================================================
 * YouTube Controller
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
document.addEventListener('click', function (e) {
  const wrapper = e.target.closest('.js-youtube');
  if (!wrapper) return;

  e.preventDefault();

  const videoId = wrapper.dataset.videoId;
  if (!videoId) return;

  wrapper.innerHTML = `
    <iframe
      src="https://www.youtube.com/embed/${videoId}?autoplay=1"
      title="YouTube video"
      frameborder="0"
      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
      allowfullscreen
    ></iframe>
  `;
});