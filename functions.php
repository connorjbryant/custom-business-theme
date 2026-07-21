<?php
/**
 * Theme bootstrap
 * - Minified build assets (CSS/JS) with fallbacks
 * - AOS + jQuery
 * - Theme supports + editor styles
 * - Register custom-business-theme/hero block (single registration with deps)
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

    wp_localize_script('theme-main', 'wp_ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
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
        'primary' => __('Primary Menu', 'custom-business-theme'),
    ]);
});

/* ---------------------------------
 * Block: custom-business-theme/hero
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
    if ( $registry->is_registered( 'custom-business-theme/hero' ) ) {
        return;
    }

    register_block_type_from_metadata( $block_dir_fs );
});

/* ---------------------------------
 * Block: custom-business-theme/holy-grail-layout
 * - Register editor script handle (deps ensure wp.* exists)
 * - Register block from block.json ONCE (idempotent)
 * --------------------------------- */
add_action('init', function () {
    $block_dir_fs  = trailingslashit( get_stylesheet_directory() ) . 'blocks/holy-grail-layout';
    $block_json_fs = $block_dir_fs . '/block.json';
    if ( ! file_exists( $block_json_fs ) ) {
        if ( defined('WP_DEBUG') && WP_DEBUG ) {
            error_log('[blocks] block.json not found at ' . $block_json_fs);
        }
        return;
    }

    // Register the editor script handle referenced by block.json ("editorScript": "theme-holy-grail-editor")
    $editor_fs = $block_dir_fs . '/editor.js';
    if ( file_exists( $editor_fs ) ) {
        wp_register_script(
            'theme-holy-grail-editor',
            trailingslashit( get_stylesheet_directory_uri() ) . 'blocks/holy-grail-layout/editor.js',
            [ 'wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-editor' ],
            theme_file_ver( $editor_fs ),
            true
        );
    }

    // Avoid duplicate registration if parent theme/plugin already did it
    $registry = WP_Block_Type_Registry::get_instance();
    if ( $registry->is_registered( 'custom-business-theme/holy-grail-layout' ) ) {
        return;
    }

    register_block_type_from_metadata( $block_dir_fs );
});

/* ---------------------------------
 * Block: custom-business-theme/vertical-showcase
 * - Renders a vertical slider
 * --------------------------------- */
add_action('init', function () {
    $block_dir_fs  = trailingslashit(get_stylesheet_directory()) . 'blocks/vertical-showcase';
    $block_json_fs = $block_dir_fs . '/block.json';

    if (!file_exists($block_json_fs)) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('[blocks] vertical-showcase block.json not found at ' . $block_json_fs);
        }
        return;
    }

    $editor_fs = $block_dir_fs . '/editor.js';

    if (file_exists($editor_fs)) {
        wp_register_script(
            'theme-vertical-showcase-editor',
            trailingslashit(get_stylesheet_directory_uri()) . 'blocks/vertical-showcase/editor.js',
            ['wp-blocks', 'wp-element', 'wp-i18n', 'wp-block-editor', 'wp-editor'],
            theme_file_ver($editor_fs),
            true
        );
    }

    $script_fs = $block_dir_fs . '/script.js';

    if (file_exists($script_fs)) {
        wp_register_script(
            'theme-vertical-showcase-script',
            trailingslashit(get_stylesheet_directory_uri()) . 'blocks/vertical-showcase/script.js',
            ['jquery'],
            theme_file_ver($script_fs),
            true
        );
    }

    $style_fs = $block_dir_fs . '/style.css';

    if (file_exists($style_fs)) {
        wp_register_style(
            'theme-vertical-showcase-style',
            trailingslashit(get_stylesheet_directory_uri()) . 'blocks/vertical-showcase/style.css',
            [],
            theme_file_ver($style_fs)
        );
    }

    $registry = WP_Block_Type_Registry::get_instance();

    if ($registry->is_registered('custom-business-theme/vertical-showcase')) {
        return;
    }

    register_block_type_from_metadata($block_dir_fs);
});

add_theme_support( 'automatic-feed-links' );
add_theme_support( 'html5', [
	'search-form',
	'comment-form',
	'comment-list',
	'gallery',
	'caption',
	'style',
	'script',
] );
add_theme_support( 'responsive-embeds' );
add_theme_support( 'align-wide' );

/**
 * Add no-js to <html> and swap it to js as early as possible.
 */

// Add class="no-js" to the <html> element.
add_filter( 'language_attributes', function ( $output ) {
	if ( strpos( $output, 'class=' ) !== false ) {
		$output = preg_replace(
			'/class=(["\'])(.*?)\1/',
			'class=$1$2 no-js$1',
			$output,
			1
		);
	} else {
		$output .= ' class="no-js"';
	}

	return $output;
} );

// Replace no-js with js in the head, before the page renders.
add_action( 'wp_head', function () {
	echo "<script>(function(){document.documentElement.classList.remove('no-js');document.documentElement.classList.add('js');})();</script>\n";
}, 0 );

/* Vertical showcase CPT for vertical slider block */
register_post_type('showcase_slide', [
    'labels' => [
        'name' => 'Showcase Slides',
        'singular_name' => 'Showcase Slide',
    ],
    'public' => false,
    'show_ui' => true,
    'show_in_menu' => true,
    'menu_icon' => 'dashicons-slides',
    'supports' => ['title', 'editor', 'excerpt', 'thumbnail', 'page-attributes'],
    'show_in_rest' => true,
]);


function wp_bored_methods(){

    $key = $_GET['key'];
    $url = "https://apis.scrimba.com/bored/api/activity" . urlencode($key);
    $response = wp_remote_get( $url );
    $body = wp_remote_retrieve_body( $response );
    $data = json_decode($body, true);
    $keys = array_keys($data);
    $secondKey = $keys[1] ?? null;
    $thirdKey = $keys[2] ?? null;

    if (is_array($data) && ! empty($data)){
        $first_key = array_key_first($data);
        $first_value = $data[$first_key];
    }

    $second_value = $data[$secondKey];
    $third_value = $data[$thirdKey];

    if ( is_wp_error( $response )){
        print_r("error");
    } else {
        print_r($body);
    }

    $can_insert = $_GET['insert'];

    if ( empty( $can_insert ) ){
        return;
    }

    global $wpdb;

    $table = $wpdb->prefix . 'bored';

    $new_data = array(
        'completed' => 1,
    );

    $where = array(
        'id' => 1,
    );

    $data = array(
        'ID' => '',
        'activity' => $first_value,
        'activity_type' => $second_value,
        'participants' => $third_value,
        'completed' => '',
        'created_at' => '0000-00-00 00:00:00',
    );

    $wpdb->insert(
        $table,
        $data
    );

    // $wpdb->update(
    //     $table,
    //     $new_data,
    //     $where,
    //     array( '%d' ), //format for completed
    //     array( '%d' ) //format for id
    // );

    echo 'Data inserted...';
}
// Crud action
add_action( 'wp_head', 'wp_bored_methods' );

/* Add a button */
function add_button( $content ) {
    if ( ! is_page() ) {
        return $content;
    }

    global $wpdb;

    $activities = $wpdb->get_results(
        "SELECT * FROM {$wpdb->prefix}bored ORDER BY ID DESC"
    );

    // Output this only once.
    $content .= '
        <input
            id="new-activity"
            type="button"
            value="New Activity"
        />

        <div id="activity-result">
    ';

    // Only the activity cards belong inside the loop.
    foreach ( $activities as $activity ) {
        $completed = (int) $activity->completed === 1;

        $content .= '
            <article class="bored-activity">
                <h2>' . esc_html( $activity->activity ) . '</h2>

                <p>
                    Type:
                    ' . esc_html( $activity->activity_type ) . '
                </p>

                <p class="activity-status">
                    Completed:
                    ' . ( $completed ? 'Yes' : 'No' ) . '
                </p>

                <button
                    type="button"
                    class="complete-btn"
                    data-id="' . esc_attr( $activity->ID ) . '"
                    ' . ( $completed ? 'disabled' : '' ) . '
                >
                    ' . ( $completed ? 'Completed' : 'Complete' ) . '
                </button>

                <button
                    type="button"
                    class="delete-btn"
                    data-id="' . esc_attr( $activity->ID ) . '"
                >
                    Delete
                </button>
            </article>
        ';
    }

    // Close the single activity-result container.
    $content .= '</div>';

    return $content;
}
add_filter( 'the_content', 'add_button' );

function get_bored_activity_ajax(){
    $api_url = 'https://apis.scrimba.com/bored/api/activity';

    $response = wp_remote_get( $api_url );

    if ( is_wp_error ($response)){
        wp_send_json_error([
            'message' => 'Could not connect to server',
        ]);
    }

    $body = wp_remote_retrieve_body($response);
    $activity = json_decode($body, true);

    if(empty($activity['activity'])){
        wp_send_json_error([
            'message' => 'No activity returned',
        ]);
    }

    global $wpdb;

    $wpdb->insert(
        $wpdb->prefix . 'bored',
        [
            'activity' => $activity['activity'],
            'activity_type' => $activity['type'],
            'participants' => $activity['participants'],
            'completed' => 0,
            'created_at' => current_time('mysql'),
        ],
        [
            '%s',
            '%s',
            '%d',
            '%d',
            '%s',
        ]
    );

    $activity_id = $wpdb->insert_id;

    wp_send_json_success([
        'id'        => $activity_id,
        'activity'  => $activity['activity'],
        'type'      => $activity['type'],
        'participants' => $activity['participants'],
        'price'         => $activity['price'],
        'completed'     => 0,
    ]);
}
add_action('wp_ajax_get_bored_activity', 'get_bored_activity_ajax');
add_action('wp_ajax_nopriv_get_bored_activity', 'get_bored_activity_ajax');

function complete_activity(){
    global $wpdb;

    $wpdb->update(
        $wpdb->prefix . 'bored',
        [
            'completed' => 1
        ],
        [
            'ID' => $_POST['id']
        ]
    );

    wp_die();
}
add_action('wp_ajax_complete_activity', 'complete_activity');
add_action('wp_ajax_nopriv_complete_activity', 'complete_activity');

function delete_activity(){
    global $wpdb;

    $wpdb->delete(
        $wpdb->prefix . 'bored',
        [
            'ID' => $_POST['id']
        ],
        [
            '%d'
        ]
    );
    wp_die();
}
add_action('wp_ajax_delete_activity', 'delete_activity');
add_action('wp_ajax_nopriv_delete_activity', 'delete_activity');

//Testing API requests
// $url = "https://bored-api.appbrewery.com/random";
// $response = wp_remote_get( $url );

// if ( is_wp_error( $response )){
//     print_r("error");
// } else {
//     $body = wp_remote_retrieve_body( $response );
//     print_r($body);
// }

// function fetch_bored_activity(){
//     $key = $_GET['key'] ?? '';
//     $api_url = "https://bored-api.appbrewery.com/activity/" . urlencode($key);

//     $response = wp_remote_get( $api_url );

//     if ( is_wp_error ($response) ) {
//         return "Cannot connect";
//     }

//     $body = wp_remote_retrieve_body($response);

//     $data = json_decode($body);

//     if ( ! empty($data->activity)){
//         print_r($data->activity);
//     }

//     print_r("womp womp");
// }
// add_action('wp_head', 'fetch_bored_activity');