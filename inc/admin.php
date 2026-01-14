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
