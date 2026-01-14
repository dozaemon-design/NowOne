<?php
/**
 * Post Types & Taxonomies
 *
 * 方針：
 * - creation を基軸に作品を一元管理
 * - music / movie / artwork は taxonomy で分類
 * - 手動 rewrite や permalink 操作は行わない（事故防止）
 */


/* =====================================================
 * Creation Custom Post Type
 * ===================================================== */
function nowone_register_creation_cpt() {
    register_post_type('creation', [
        'labels' => [
            'name' => 'Creation',
            'singular_name' => 'Creation',
            'menu_name' => '作品',
        ],
        'public'       => true,
        'show_ui'      => true,
        'show_in_rest' => true,

        // ★ rewrite は触らない
        // 'rewrite' => true,
        'rewrite' => [
        'slug' => 'creation',
        'with_front' => false,
        ],
        'has_archive' => true,

        'supports' => [
            'title',
            'thumbnail',
        ],
        'menu_icon' => 'dashicons-format-gallery',
    ]);
}
add_action('init', 'nowone_register_creation_cpt');


/* =====================================================
 * Creation Type Taxonomy
 * ===================================================== */
function nowone_register_creation_type_taxonomy() {
    register_taxonomy('creation_type', ['creation'], [
        'labels' => [
            'name' => 'Creation Type',
        ],
        'hierarchical' => true,
        'show_ui'      => false,
        'show_in_rest' => true,
        // ★ ここだけ修正
        'rewrite' => [
            'slug' => 'type',   // ← 仮プレフィックス
            'with_front' => false,
        ],
        // 'rewrite' => true,
    ]);
}
add_action('init', 'nowone_register_creation_type_taxonomy');

/* =====================================================
 * Genre Taxonomies（用途別ジャンル）
 * ===================================================== */
function nowone_register_creation_genre_taxonomies() {

    $taxonomies = [
        'genre_music'   => 'Music ジャンル',
        'genre_movie'   => 'Movie ジャンル',
        'genre_artwork' => 'Artwork ジャンル',
    ];

    foreach ($taxonomies as $slug => $label) {

        register_taxonomy($slug, ['creation'], [
            'labels' => [
                'name'          => $label,
                'singular_name' => $label,
            ],

            'hierarchical'      => true,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'rewrite' => false,
        ]);
    }
}
add_action('init', 'nowone_register_creation_genre_taxonomies');

/* =====================================================
 * Portfolio Custom Post Type
 * ===================================================== */
function nowone_register_portfolio_cpt() {

    register_post_type('portfolio', [

        /* -------------------------
         * 管理画面表示名
         * ----------------------- */
        'labels' => [
            'name'          => 'Portfolio',
            'singular_name' => 'Portfolio',
            'add_new_item'  => '実績を追加',
            'edit_item'     => '実績を編集',
            'all_items'     => 'すべての実績',
            'menu_name'     => 'Portfolio',
        ],

        /* -------------------------
         * 基本設定
         * ----------------------- */
        'public'             => true, // 秘密サイト前提
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'show_in_rest'       => true,

        /* -------------------------
         * URL / アーカイブ
         * ----------------------- */
        'has_archive' => true,
        'rewrite' => [
            'slug'       => 'portfolio',
            'with_front' => false,
        ],

        /* -------------------------
         * 編集画面機能
         * ----------------------- */
        'supports' => [
            'title',
            'thumbnail',
        ],

        /* -------------------------
         * 管理画面アイコン
         * ----------------------- */
        'menu_icon' => 'dashicons-portfolio',
    ]);
}
add_action('init', 'nowone_register_portfolio_cpt');


/* =====================================================
 * Portfolio Genre Taxonomy
 * ===================================================== */
function nowone_register_portfolio_genre_taxonomy() {

    register_taxonomy('portfolio_genre', ['portfolio'], [

        /* -------------------------
         * 管理画面表示
         * ----------------------- */
        'labels' => [
            'name'          => 'Portfolio ジャンル',
            'singular_name' => 'Portfolio ジャンル',
        ],

        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_rest'      => true,
        'show_admin_column' => true,

        /* -------------------------
         * URL 設計
         * /portfolio/bnr/
         * ----------------------- */
        'rewrite' => [
            'slug'       => 'portfolio-genre',
            'with_front' => false,
            'pages'      => true,
            'feeds'      => false,
        ],
    ]);
}
add_action('init', 'nowone_register_portfolio_genre_taxonomy');


/* --------------------------------
 * taxonomyジャンル追加時変更必須（内容は自動同期）
 * -------------------------------- */
add_action('save_post_creation', function ($post_id) {

    if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
        return;
    }

    $map = [
        'genre_music'   => 'music',
        'genre_movie'   => 'movie',
        'genre_artwork' => 'artwork',
    ];

    foreach ($map as $genre_tax => $type_slug) {
        if (has_term('', $genre_tax, $post_id)) {
            wp_set_object_terms($post_id, $type_slug, 'creation_type', false);
            return; // ★ 1つ決まったら終了（前提：1作品1タイプ）
        }
    }

}, 10, 1);