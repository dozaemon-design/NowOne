<?php
/**
 * Portfolio 固有のフロント制御
 *
 * このファイルは、Portfolio 一覧や固定ページまわりの
 * テンプレート分岐とクエリ制御を担当する。
 */

/**
 * /portfolio-pages/ 配下の固定ページを共通ブリッジテンプレートへ委譲する。
 * /portfolio/ は portfolio CPT archive と競合するため固定ページでは使用しない。
 */
add_filter('template_include', function ($template) {
  // 固定ページ以外は対象外。
  if (!is_page()) {
    return $template;
  }

  // portfolio-pages 親ページが存在しなければ何もしない。
  $root = get_page_by_path('portfolio-pages');
  if (!$root || is_wp_error($root) || empty($root->ID)) {
    return $template;
  }

  // 現在の固定ページ ID を取得する。
  $page_id = get_queried_object_id();
  if (!$page_id) {
    return $template;
  }

  // portfolio-pages 配下のページだけを共通ブリッジへ渡す。
  $ancestors = get_post_ancestors($page_id);
  $is_in_portfolio_pages = ((int) $page_id === (int) $root->ID) || in_array((int) $root->ID, array_map('intval', $ancestors), true);
  if (!$is_in_portfolio_pages) {
    return $template;
  }

  $bridge = get_theme_file_path('page-portfolio-pages.php');
  return file_exists($bridge) ? $bridge : $template;
});

add_action('pre_get_posts', function ($query) {
  // 管理画面やメインクエリ以外は変更しない。
  if (is_admin() || !$query->is_main_query()) {
    return;
  }

  // portfolio 関連アーカイブだけを対象にする。
  if (!($query->is_post_type_archive('portfolio') || $query->is_tax('portfolio_genre'))) {
    return;
  }

  // 一覧件数は 20 件で固定する。
  $query->set('posts_per_page', 20);

  // profile 専用投稿は一覧から除外する。
  $profile_id = nowone_get_portfolio_profile_post_id();
  if (!$profile_id) {
    return;
  }

  $post__not_in = (array) $query->get('post__not_in');
  $post__not_in[] = $profile_id;
  $query->set('post__not_in', array_values(array_unique(array_map('intval', $post__not_in))));
});
?>
