const canvas = document.getElementById('bg-lines');
const ctx = canvas.getContext('2d');
const reduceMotion = window.matchMedia(
  '(prefers-reduced-motion: reduce)'
).matches; //スマホ対応
// ★ これが抜けている
const STAR_COUNT = reduceMotion ? 2 : 8;
const STAR_SPEED = reduceMotion ? 0.0005 : 0.002;
let width, height;

const spacing = 40;

let lines = [];

/* ===== ハイライト設定 ===== */
const highlights = Array.from({ length: STAR_COUNT }, () => ({
  xIndex: Math.floor(Math.random() * 1000),
  y: Math.random() * window.innerHeight,
  length: 160,
  life: Math.random(),
  speed: STAR_SPEED
}));
const highlightLength = 60;
const highlightGap = 220;

/* =========================
   Resize
========================= */
function resize() {
  const dpr = window.devicePixelRatio || 1;
  width = window.innerWidth;
  height = window.innerHeight;

  canvas.width = width * dpr;
  canvas.height = height * dpr;
  canvas.style.width = width + 'px';
  canvas.style.height = height + 'px';

  ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

  lines = [];
  for (let i = -height; i < width + height; i += spacing) {
    lines.push({
      x: i,
      phase: Math.random() * 300
    });
  }
}

window.addEventListener('resize', resize);
resize();

/* =========================
   Draw
========================= */
function draw() {

  // フェード（残像）
  ctx.fillStyle = 'rgba(46,49,51,0.5)';
  ctx.fillRect(0, 0, width, height);

  /* ===== 下地ライン（確認用） ===== */
  ctx.strokeStyle = 'rgba(255,255,255,0.00)';
  ctx.lineWidth = 1;
  ctx.setLineDash([]);

  lines.forEach(line => {
    ctx.beginPath();
    ctx.moveTo(line.x, 0);
    ctx.lineTo(line.x + height, height);
    ctx.stroke();
  });


/* ===== 流れ星 ===== */
ctx.save();
ctx.globalCompositeOperation = 'color-dodge';
ctx.lineWidth = 1;

highlights.forEach(h => {
  h.life += h.speed;
  if (h.life >= 1) {
    h.life = 0;
    h.y = Math.random() * height;
    h.xIndex = Math.floor(Math.random() * lines.length);
  }

  const t = h.life;

  // 先端 → 尾
  const head = t * h.length;
  const tail = Math.max(0, head - h.length * 0.4);

  // ===== フェード制御 =====
  const fadeInEnd = 0.2;
  const fadeOutStart = 0.35;
  let alpha = 1;

  if (t < fadeInEnd) {
    alpha = t / fadeInEnd;
  } else if (t > fadeOutStart) {
    alpha = (1 - t) / (1 - fadeOutStart);
  }

  if (alpha <= 0) return;

  const line = lines[h.xIndex % lines.length];

  // ===== グラデーション（⑤はここ） =====
  const grad = ctx.createLinearGradient(
    line.x + tail,
    h.y + tail * 0.6,
    line.x + head,
    h.y + head * 0.6
  );

  grad.addColorStop(0, `rgba(255,255,255,0)`);
  grad.addColorStop(0.4, `rgba(255,255,255,${alpha * 0.25})`);
grad.addColorStop(1, `rgba(255,255,255,${alpha * 0.25})`);

  ctx.strokeStyle = grad;

  ctx.beginPath();
  ctx.moveTo(
    line.x + tail,
    h.y + tail * 0.6
  );
  ctx.lineTo(
    line.x + head,
    h.y + head * 0.6
  );
  ctx.stroke();
});
  ctx.restore();
  ctx.setLineDash([]);
  requestAnimationFrame(draw);
}

draw();