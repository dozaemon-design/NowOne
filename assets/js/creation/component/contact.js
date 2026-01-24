// wpcf7-class名はメジャーアップデート時注意
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('.wpcf7-form');
  const inputArea = document.querySelector('.js-input-area');
  const confirmArea = document.querySelector('.js-confirm-area');
  const confirmBtn = document.querySelector('.js-confirm');
  const backBtn = document.querySelector('.js-back');

  if (!form || !inputArea || !confirmArea || !confirmBtn) return;

  /* ==========================
   * 入力 → 確認画面
   * ========================== */
confirmBtn.addEventListener('click', () => {

  const requiredFields = form.querySelectorAll(
    'input[name="your-name"], input[name="your-email"],input[name="your-subject"], textarea[name="your-message"]'
  );

  let hasError = false;

  requiredFields.forEach(field => {
    const wrap = field.closest('.wpcf7-form-control-wrap');

    if (!field.value.trim()) {
      hasError = true;

      field.classList.add('wpcf7-not-valid');
      field.setAttribute('aria-invalid', 'true');

      // tip が無ければ作る
      if (wrap && !wrap.querySelector('.wpcf7-not-valid-tip')) {
        const tip = document.createElement('span');
        tip.className = 'wpcf7-not-valid-tip';
        tip.textContent = '入力してください。';
        wrap.appendChild(tip);
      }
    } else {
      field.classList.remove('wpcf7-not-valid');
      field.setAttribute('aria-invalid', 'false');

      const tip = wrap?.querySelector('.wpcf7-not-valid-tip');
      if (tip) tip.remove();
    }
  });

  const responseOutput = form.querySelector('.wpcf7-response-output');
  form.prepend(responseOutput);

  if (hasError) {
    responseOutput.textContent = '必須項目を入力してください。';
    responseOutput.style.display = 'block';
    window.scrollTo({ top: 0, behavior: 'smooth' });
    return;
  }

  // === 確認画面反映 ===
  const fields = form.querySelectorAll(
    'input:not([type="hidden"]):not([type="submit"]), textarea'
  );

  fields.forEach(field => {
    const target = confirmArea.querySelector(`[data-confirm="${field.name}"]`);
    if (target) target.textContent = field.value;
  });

  inputArea.hidden = true;
  confirmArea.hidden = false;

  window.scrollTo({ top: 0, behavior: 'smooth' });
  history.pushState(null, '', '?step=confirm');
});

  /* ==========================
   * 確認 → 入力画面へ戻る
   * ========================== */
  if (backBtn) {
    backBtn.addEventListener('click', () => {
    // 表示切替
      confirmArea.hidden = true;
      inputArea.hidden = false;
    // スクロールを戻す
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    // URL更新
      history.pushState(null, '', location.pathname);
    });
  }

  /* ==========================
   * CF7送信完了 → thanks
   * ========================== */
  document.addEventListener('wpcf7mailsent', () => {
    window.location.href = '/contact-thanks/';
  });

  /* ==========================
   * ブラウザ戻る対策
   * ========================== */
  window.addEventListener('popstate', () => {
    confirmArea.hidden = true;
    inputArea.hidden = false;
  });

  /* ==========================
   * confirm URL直打ち対策
   * ========================== */
  if (location.search.includes('step=confirm')) {
    const fields = form.querySelectorAll(
          'input[name="your-name"], input[name="your-email"], textarea[name="your-message"]'
    );

    const hasValue = [...fields].some(el => el.value.trim() !== '');

    if (hasValue) {
      inputArea.hidden = true;
      confirmArea.hidden = false;
    } else {
      history.replaceState(null, '', location.pathname);
      confirmArea.hidden = true;
      inputArea.hidden = false;
    }
  }
 document.addEventListener('wpcf7invalid', () => {
  confirmArea.hidden = true;
  inputArea.hidden = false;

  const responseOutput = form.querySelector('.wpcf7-response-output');
  form.prepend(responseOutput);

  window.scrollTo({ top: 0, behavior: 'smooth' });
});

});