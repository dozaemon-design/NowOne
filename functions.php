<?php
/**
 * Theme bootstrap
 */
// テーマ全体のバージョン定義（style.css の更新時刻を基準）
define('NOWONE_THEME_VERSION', filemtime(get_template_directory() . '/style.css'));

$inc_files = [
    'theme-support.php',
    'acf.php',
    'helpers.php',
    'helpers-image.php',
    'post-types.php',
    'seed.php',
    'image-sizes.php',
    'admin.php',
    'admin-term-order.php',
    'admin-columns.php',
    'head-cleanup.php',
    'seo.php',
    'rewrite-creation.php',
    'security.php',
    'enqueue.php',
    'portfolio.php',
    'portfolio-access.php',
    'admin-access.php',
];

foreach ($inc_files as $file) {
    $path = get_template_directory() . '/inc/' . $file;
    if (file_exists($path)) {
        require_once $path;
    }
}
