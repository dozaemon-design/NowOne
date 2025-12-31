<?php
/**
 * Admin UX
 */

/**
 * 管理画面：creation 一覧にサムネイル表示
 */
add_filter('manage_creation_posts_columns', function ($columns) {

  $new = [];

  foreach ($columns as $key => $label) {
    // チェックボックスの次に差し込む
    if ($key === 'cb') {
      $new['thumb'] = '画像';
    }
    $new[$key] = $label;
  }

  return $new;
});

add_action('manage_creation_posts_custom_column', function ($column, $post_id) {

  if ($column !== 'thumb') {
    return;
  }

  // ACF画像フィールド（配列返却）
  $image = get_field('fg_thumb', $post_id);

  if ($image && isset($image['sizes']['creation_thumb'])) {
    echo '<img src="' . esc_url($image['sizes']['creation_thumb']) . '" style="width:80px;height:auto;" />';
  }

}, 10, 2);