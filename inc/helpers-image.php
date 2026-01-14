<?php
/*===============================
 * IMAGE helpers
 * シングルページ専用
 * Webp対応用
 * Plug-inで実装するかは要件等。
===============================*/

function get_img_data($img, $size = 'full') {
  if (!$img) return null;

  $id = is_array($img) ? ($img['ID'] ?? null) : $img;
  if (!$id) return null;

  $meta = wp_get_attachment_metadata($id);
  $size_meta = $meta['sizes'][$size] ?? null;

  return [
    'url'    => wp_get_attachment_image_url($id, $size),
    'webp'   => wp_get_attachment_image_url($id, "{$size}_webp"),
    'alt'    => get_post_meta($id, '_wp_attachment_image_alt', true),
    'width'  => $size_meta['width']  ?? $meta['width'],
    'height' => $size_meta['height'] ?? $meta['height'],
  ];
}

function get_creation_image(
  string $role = 'detail',
  string $size = 'creation_detail'
) {
  // ① archive / single 共通：taxonomy 優先
  $terms = get_the_terms(get_the_ID(), 'creation_type');
  $type  = $terms[0]->slug ?? null;

  // ② fallback：single 用 ACF
  if (!$type) {
    $type = get_field('creation_type_ui');
  }

  if (!$type) return null;

  // creation_type ごとの ACF フィールド対応表
  $map = [
    'music' => [
      'detail' => 'music_detail_img',
      'thumb'  => 'music_thumb_img',
    ],
    'movie' => [
      'detail' => 'movie_detail_img',
      'thumb'  => 'movie_thumb_img',
    ],
    'artwork' => [
      'detail' => 'artwork_detail_img',
      'thumb'  => 'artwork_thumb_img',
    ],
  ];

  $field = $map[$type][$role] ?? null;
  if (!$field) return null;

  // 各タイプの ACF グループ（music_fields など）
  $group = get_field("{$type}_fields");
  if (empty($group[$field])) return null;

  return get_img_data($group[$field], $size);
}

function render_creation_picture(
  ?array $img,
  string $context = 'single',
  bool $is_lcp = false
) {
  if (!$img) return '';

  $sizes = [
    'archive' => '(max-width: 768px) 100vw, 33vw',
    'single'  => '(max-width: 768px) 100vw, 800px',
  ];

  ob_start();
?>
<picture>
  <?php if (!empty($img['webp']) && str_ends_with($img['webp'], '.webp')): ?>
    <source srcset="<?= esc_url($img['webp']); ?>" type="image/webp">
  <?php endif; ?>

  <img
    src="<?= esc_url($img['url']); ?>"
    alt="<?= esc_attr($img['alt']); ?>"
    width="<?= esc_attr($img['width']); ?>"
    height="<?= esc_attr($img['height']); ?>"
    loading="<?= $is_lcp ? 'eager' : 'lazy'; ?>"
    <?= $is_lcp ? 'fetchpriority="high"' : ''; ?>
    decoding="async"
    sizes="<?= esc_attr($sizes[$context] ?? '100vw'); ?>"
  >
</picture>
<?php
  return ob_get_clean();
}