<?php
/*===============================
 * IMAGE helpers
 * シングルページ専用
 * WebP/AVIF はプラグイン側（例：Converter for Media等）に任せる
 * - 生成先ディレクトリや配信方式（rewrite / <picture>）が環境依存のため、テーマ側で拡張子固定のURLを組み立てない
===============================*/

function nowone_get_attachment_id_from_acf($img): ?int {
  if (empty($img)) return null;

  if (is_numeric($img)) {
    $id = (int) $img;
    return $id > 0 ? $id : null;
  }

  if (is_array($img)) {
    $id = $img['id'] ?? $img['ID'] ?? null;
    if (is_numeric($id)) {
      $id = (int) $id;
      return $id > 0 ? $id : null;
    }

    // ACFの返り値がURLの場合に備える
    $url = $img['url'] ?? null;
    if (is_string($url) && $url !== '') {
      $maybe_id = attachment_url_to_postid($url);
      return $maybe_id ? (int) $maybe_id : null;
    }
  }

  if (is_string($img) && $img !== '') {
    $maybe_id = attachment_url_to_postid($img);
    return $maybe_id ? (int) $maybe_id : null;
  }

  return null;
}

function get_img_data($img, $size = 'full') {
  if (!$img) return null;

  $id = nowone_get_attachment_id_from_acf($img);
  if (!$id) return null;

  $meta = wp_get_attachment_metadata($id);
  $size_meta = $meta['sizes'][$size] ?? null;

  return [
    'id'     => $id,
    'size'   => $size,
    'url'    => wp_get_attachment_image_url($id, $size),
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
  $img,
  string $context = 'single',
  bool $is_lcp = false
) {
  if (!$img) return '';

  $sizes = [
    'archive' => '(max-width: 768px) 100vw, 33vw',
    'single'  => '(max-width: 768px) 100vw, 800px',
  ];

  // get_img_data() の返り値 / ACFそのまま / ID を許容
  $attachment_id = null;
  $size = 'full';

  if (is_array($img) && !empty($img['id'])) {
    $attachment_id = (int) $img['id'];
    $size = (string) ($img['size'] ?? $size);
  } else {
    $attachment_id = nowone_get_attachment_id_from_acf($img);
  }

  if (!$attachment_id || !wp_attachment_is_image($attachment_id)) {
    return '';
  }

  $attrs = [
    'loading'   => $is_lcp ? 'eager' : 'lazy',
    'decoding'  => 'async',
    'sizes'     => $sizes[$context] ?? '100vw',
  ];
  if ($is_lcp) {
    $attrs['fetchpriority'] = 'high';
  }

  // 画像フォーマットの出し分け（WebP/AVIF）はWP/プラグイン側のフィルタに委譲
  return wp_get_attachment_image($attachment_id, $size, false, $attrs);
}

function render_portfolio_acf_image($acf_value, string $size = 'portfolio_thumb', array $attrs = []) {
  if (!is_array($acf_value) || empty($acf_value['ID'])) {
    return '';
  }

  $attachment_id = (int) $acf_value['ID'];

  if (!$attachment_id || !wp_attachment_is_image($attachment_id)) {
    return '';
  }

  $default_attrs = [
    'loading'  => 'lazy',
    'decoding' => 'async',
  ];

  return wp_get_attachment_image(
    $attachment_id,
    $size,
    false,
    array_merge($default_attrs, $attrs)
  );
}
