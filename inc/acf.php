<?php
/*===============================
 * ACF 関連はすべてここ
===============================*/

/**
 * ACF JSON 同期設定
 */

add_filter('acf/settings/save_json', function ($path) {
    return get_stylesheet_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function ($paths) {
    unset($paths[0]);
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
});

/**
 * ACF hooks
 */

/**
 * creation_type_ui → creation_type taxonomy 同期
 */
// add_action('acf/save_post', function ($post_id) {

//     if (get_post_type($post_id) !== 'creation') return;
//     if (wp_is_post_revision($post_id)) return;

//     $type = get_field('creation_type_ui', $post_id);
//     if (!$type) return;

//     wp_set_object_terms($post_id, $type, 'creation_type', false);

// }, 20);
add_action('acf/save_post', function ($post_id) {

    if (get_post_type($post_id) !== 'creation') return;
    if (wp_is_post_revision($post_id)) return;

    // ★ fieldキーで取得
    $type = $_POST['acf']['field_694f970f9fda1'] ?? null;

    error_log('creation_type_ui raw = ' . print_r($type, true));

    if (!$type) return;

    // taxonomy に反映（slug を渡す）
    wp_set_object_terms($post_id, [$type], 'creation_type', false);

}, 20);


add_action('acf/save_post', function ($post_id) {
    error_log('acf/save_post fired: ' . $post_id);
}, 1);