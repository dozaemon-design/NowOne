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
		NOWONE_THEME_VERSION,
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
	wp_enqueue_script( // jQuery Easing JS
		'nowone-easing',
		get_template_directory_uri() . '/assets/js/lib/jquery.easing.1.3.js',
		array('jquery'),
		NOWONE_THEME_VERSION,
		true
	);
	// Splitting.js
	wp_enqueue_script(
		'splitting',
		get_template_directory_uri() . '/assets/js/vendor/splitting.min.js',
		[],
		NOWONE_THEME_VERSION,
		true
	);
	// Splitting用CSS
	wp_enqueue_style(
		'splitting',
		get_template_directory_uri() . '/assets/css/vendor/splitting.css',
		[],
		NOWONE_THEME_VERSION
	);
	// Home text用JS
	wp_enqueue_script(
		'home-text',
		get_template_directory_uri() . '/assets/js/creation/production/home-text.js',
		['splitting'],
		NOWONE_THEME_VERSION,
		true
	);
	wp_enqueue_script( // Base JS
		'nowone-base',
		get_template_directory_uri() . '/assets/js/base.js',
		array('jquery'),
		NOWONE_THEME_VERSION,
		true
	);
	wp_enqueue_script( // Global Navigation JS
		'nowone-global-nav',
		get_template_directory_uri() . '/assets/js/creation/component/global-nav.js',
		array('jquery'),
		NOWONE_THEME_VERSION,
		true
	);
	wp_enqueue_script( // YouTube embed JS
		'nowone-youtube',
		get_template_directory_uri() . '/assets/js/creation/component/youtube.js',
		array('jquery'),
		NOWONE_THEME_VERSION,
		true
	);
	if (is_front_page()) { //トップページのみ読み込み
		wp_enqueue_script(
			'nowone-home',
			get_template_directory_uri() . '/assets/js/creation/production/home.js',
			['jquery'],
			NOWONE_THEME_VERSION,
			true
		);
	}
	wp_enqueue_script( // list animation JS
		'nowone-reveal',
		get_template_directory_uri() . '/assets/js/creation/component/reveal.js',
		array('jquery'),
		NOWONE_THEME_VERSION,
		true
	);
	wp_enqueue_script( // list animation JS
		'nowone-header',
		get_template_directory_uri() . '/assets/js/creation/component/header.js',
		array('jquery'),
		NOWONE_THEME_VERSION,
		true
	);
	if (is_page('contact')) { // お問い合わせのみ読み込み
		wp_enqueue_script(
			'contact-form',
			get_theme_file_uri('/assets/js/creation/component/contact.js'),
			[],
			NOWONE_THEME_VERSION,
			true
		);
	};
} //end
add_action('wp_enqueue_scripts', 'nowone_enqueue_assets');


	/* =========================
	 * Admin CSS
	 * ========================= */
	add_action('admin_enqueue_scripts', function () {
		wp_enqueue_style(
			'nowone-admin',
			get_template_directory_uri() . '/assets/css/admin.css',
			[],
			NOWONE_THEME_VERSION
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
        NOWONE_THEME_VERSION,
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