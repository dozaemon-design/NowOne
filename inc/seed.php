<?php
/**
 * Default term seed
 */

add_action('init', function () {

    if (get_option('nowone_terms_seeded') === 'yes') return;

    foreach (['Trance','ElectroRock','Instrument'] as $g) {
        if (!term_exists($g, 'genre_music')) {
            wp_insert_term($g, 'genre_music');
        }
    }

    update_option('nowone_terms_seeded', 'yes');
}, 20);