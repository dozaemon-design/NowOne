<?php
/**
 * Admin UX
 */

remove_post_type_support('creation', 'editor');
remove_post_type_support('creation', 'excerpt');
remove_post_type_support('creation', 'comments');

add_action('admin_notices', function () {
    if (!current_user_can('manage_options')) return;
    echo '<div class="notice notice-info"><p>functions.php 更新後はパーマリンク保存を忘れずに。</p></div>';
});

// 管理画面で同一 slug を防ぐ
// add_filter('wp_unique_post_slug', function ($slug, $post_ID, $post_status, $post_type) {

//   if (in_array($post_type, ['portfolio', 'creation'], true)) {
//     $slug = $post_type . '-' . $slug;
//   }

//   return $slug;
// }, 10, 4);