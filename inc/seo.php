<?php
/**
 * SEO 関連フック
 *
 * このファイルは、sitemap の公開範囲調整と
 * taxonomy ページの meta description 出力を担当する。
 */

// ユーザー sitemap は公開しない。
add_filter('wp_sitemaps_add_provider', function ($provider, $name) {
  if ($name === 'users') {
    return false;
  }

  return $provider;
}, 10, 2);

// portfolio 投稿タイプは sitemap から除外する。
add_filter('wp_sitemaps_post_types', function ($post_types) {
  unset($post_types['portfolio']);
  return $post_types;
});

// portfolio 関連 taxonomy は sitemap から除外する。
add_filter('wp_sitemaps_taxonomies', function ($taxonomies) {
  unset($taxonomies['portfolio_genre']);
  unset($taxonomies['portfolio_tool']);
  unset($taxonomies['portfolio_role']);
  return $taxonomies;
});

// taxonomy ページでは簡易的な description を head に出力する。
add_action('wp_head', function () {
  if (!is_tax()) {
    return;
  }

  $term = get_queried_object();
  if ($term && !is_wp_error($term)) {
    $desc = 'NowOneの' . esc_html($term->name) . '作品一覧ページ。';
    echo '<meta name="description" content="' . esc_attr($desc) . '">' . "\n";
  }
});
?>
