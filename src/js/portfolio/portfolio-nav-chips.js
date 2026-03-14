(() => {
  const containers = document.querySelectorAll('.js-portfolio-chips');
  if (!containers.length) return;

  containers.forEach((container) => {
    const current = container.querySelector('[aria-current="page"]');
    if (!current) return;

    try {
      current.scrollIntoView({ block: 'nearest', inline: 'center', behavior: 'smooth' });
    } catch {
      current.scrollIntoView(true);
    }
  });
})();

