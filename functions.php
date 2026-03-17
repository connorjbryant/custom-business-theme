<?php
/**
 * Theme bootstrap
 * - Minified build assets (CSS/JS) with fallbacks
 * - AOS + jQuery
 * - Theme supports + editor styles
 * - Register tlk-supply/hero block (single registration with deps)
 */

/* ---------------------------------
 * Helpers
 * --------------------------------- */
function theme_file_ver( $fs_path ) {
    return file_exists( $fs_path ) ? filemtime( $fs_path ) : null;
}

/* ---------------------------------
 * Front-end assets
 * --------------------------------- */
add_action('wp_enqueue_scripts', function () {
    // AOS (optional)
    wp_enqueue_style(
        'aos',
        'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css',
        [],
        '2.3.4'
    );
    wp_enqueue_script(
        'aos',
        'https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js',
        [],
        '2.3.4',
        true
    );

    // CSS: prefer build/css/style.min.css → fallback to root style.css
    $build_css_fs = get_stylesheet_directory() . '/build/css/style.min.css';
    if ( file_exists( $build_css_fs ) ) {
        wp_enqueue_style(
            'theme-style',
            get_stylesheet_directory_uri() . '/build/css/style.min.css',
            [],
            theme_file_ver( $build_css_fs )
        );
    } else {
        // Root style.css
        $root_css_fs = get_stylesheet_directory() . '/style.css';
        wp_enqueue_style(
            'theme-style',
            get_stylesheet_uri(),
            [],
            theme_file_ver( $root_css_fs ) ?: wp_get_theme()->get('Version')
        );
    }

    // jQuery (WP bundled)
    wp_enqueue_script('jquery');

    // JS: prefer build/js/main.min.js → fallback to /js/main.js
    $build_js_fs = get_stylesheet_directory() . '/build/js/main.min.js';
    if ( file_exists( $build_js_fs ) ) {
        wp_enqueue_script(
            'theme-main',
            get_stylesheet_directory_uri() . '/build/js/main.min.js',
            ['jquery'],
            theme_file_ver( $build_js_fs ),
            true
        );
    } else {
        $plain_js_fs = get_stylesheet_directory() . '/js/main.js';
        if ( file_exists( $plain_js_fs ) ) {
            wp_enqueue_script(
                'theme-main',
                get_stylesheet_directory_uri() . '/js/main.js',
                ['jquery'],
                theme_file_ver( $plain_js_fs ),
                true
            );
        }
    }
});

/* ---------------------------------
 * Theme supports + menus + editor styles
 * --------------------------------- */
add_action('after_setup_theme', function () {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');

    // Editor styles (prefer build/css/editor.min.css if present)
    add_theme_support('editor-styles');
    $editor_build_fs = get_stylesheet_directory() . '/build/css/editor.min.css';
    if ( file_exists( $editor_build_fs ) ) {
        add_editor_style( 'build/css/editor.min.css' );
    }

    register_nav_menus([
        'primary' => __('Primary Menu', 'theme'),
    ]);
});

/* ---------------------------------
 * Block: tlk-supply/hero
 * - Register editor script handle (deps ensure wp.* exists)
 * - Register block from block.json ONCE (idempotent)
 * --------------------------------- */
add_action('init', function () {
    $block_dir_fs  = trailingslashit( get_stylesheet_directory() ) . 'blocks/hero';
    $block_json_fs = $block_dir_fs . '/block.json';
    if ( ! file_exists( $block_json_fs ) ) {
        if ( defined('WP_DEBUG') && WP_DEBUG ) {
            error_log('[blocks] block.json not found at ' . $block_json_fs);
        }
        return;
    }

    // Register the editor script handle referenced by block.json ("editorScript": "theme-hero-editor")
    $editor_fs = $block_dir_fs . '/editor.js';
    if ( file_exists( $editor_fs ) ) {
        wp_register_script(
            'theme-hero-editor',
            trailingslashit( get_stylesheet_directory_uri() ) . 'blocks/hero/editor.js',
            [ 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-editor' ],
            theme_file_ver( $editor_fs ),
            true
        );
    }

    // Avoid duplicate registration if parent theme/plugin already did it
    $registry = WP_Block_Type_Registry::get_instance();
    if ( $registry->is_registered( 'tlk-supply/hero' ) ) {
        return;
    }

    register_block_type_from_metadata( $block_dir_fs );
});
