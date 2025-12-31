<?php
/**
 * CSS / JS enqueue
 */

function nowone_enqueue_assets() {

	/* =========================
	 * CSS
	 * ========================= */
	wp_enqueue_style(
		'nowone-app',
		get_template_directory_uri() . '/assets/css/app.css',
		array(),
		filemtime(get_template_directory() . '/assets/css/app.css'),
		'all'
	);

	/* =========================
	 * jQuery
	 * ========================= */
	// WordPress同梱のjQueryを使用
	wp_enqueue_script('jquery');

	/* =========================
	 * JS
	 * ========================= */
	wp_enqueue_script(
		'nowone-easing',
		get_template_directory_uri() . '/assets/js/lib/jquery.easing.1.3.js',
		array('jquery'),
		filemtime(get_template_directory() . '/assets/js/lib/jquery.easing.1.3.js'),
		true
	);

	wp_enqueue_script(
		'nowone-base',
		get_template_directory_uri() . '/assets/js/base.js',
		array('jquery'),
		filemtime(get_template_directory() . '/assets/js/base.js'),
		true
	);
	wp_enqueue_script(
		'nowone-global-nav',
		get_template_directory_uri() . '/assets/js/component/global-nav.js',
		array('jquery'),
		filemtime(get_template_directory() . '/assets/js/component/global-nav.js'),
		true
	);
}
add_action('wp_enqueue_scripts', 'nowone_enqueue_assets');


	/* =========================
	 * Admin CSS
	 * ========================= */
	add_action('admin_enqueue_scripts', function () {
		wp_enqueue_style(
			'nowone-admin',
			get_template_directory_uri() . '/assets/css/admin.css',
			[],
			filemtime(get_template_directory() . '/assets/css/admin.css')
		);
	});
	/* =========================
	 * Admin JS
	 * ========================= */
	function nowone_admin_scripts( $hook ) {

    if ( $hook !== 'post.php' && $hook !== 'post-new.php' ) return;

    wp_enqueue_script(
        'nowone-admin',
        get_template_directory_uri() . '/assets/js/admin.js',
        ['jquery'],
        null,
        true
				);
		}
		add_action( 'admin_enqueue_scripts', 'nowone_admin_scripts' );

	// Creation Type タクソノミーのチェックボックスを上部に固定
		add_filter('wp_terms_checklist_args', function ($args, $post_id) {
    if ($args['taxonomy'] === 'creation_type') {
        $args['checked_ontop'] = false;
    }
    return $args;
}, 10, 2);