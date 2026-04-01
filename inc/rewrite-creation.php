<?php
/**
 * Creation 用 rewrite / permalink 設定
 *
 * このファイルは、creation 投稿タイプと creation_type taxonomy の
 * URL 構造を WordPress 標準からカスタム形式へ調整する。
 */

// creation single の URL を /music/slug/ のような形式に変換する。
add_filter('post_type_link', function ($link, $post) {
  if ($post->post_type !== 'creation') {
    return $link;
  }

  $terms = get_the_terms($post->ID, 'creation_type');
  if (empty($terms) || is_wp_error($terms)) {
    return $link;
  }

  return home_url('/' . $terms[0]->slug . '/' . $post->post_name . '/');
}, 10, 2);

// /music/slug/ を creation single として解決する。
add_action('init', function () {
  add_rewrite_rule(
    '^(music|movie|artwork)/([^/]+)/?$',
    'index.php?post_type=creation&name=$matches[2]',
    'top'
  );
});

// creation_type taxonomy の URL を /music/ のような形式に変換する。
add_filter('term_link', function ($url, $term, $taxonomy) {
  if ($taxonomy === 'creation_type') {
    return home_url('/' . $term->slug . '/');
  }

  return $url;
}, 10, 3);

// music / movie / artwork のアーカイブ URL を taxonomy として解決する。
add_action('init', function () {
  $reserved = [
    'about',
    'blog',
    'contact',
    'privacy',
    'wp-admin',
    'wp-login.php',
  ];

  add_rewrite_rule(
    '^(?!' . implode('|', $reserved) . ')(music|movie|artwork)/?$',
    'index.php?creation_type=$matches[1]',
    'top'
  );
});
?>
