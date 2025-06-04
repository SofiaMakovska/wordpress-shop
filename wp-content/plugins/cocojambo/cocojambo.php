<?php
/*
Plugin Name: cocojambo
Description: Description
Version: 0.2.5
Author: Volkov Grisha
Requires PHP: 7.4
Text Domain: cocojambo
Domain Path: /languages/
Tags: cocojambo
Text Domain: cocojambo
*/

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'COCOJAMBO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'COCOJAMBO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'COCOJAMBO_PLUGIN_NAME', dirname(plugin_dir_url( __FILE__ )));

//require_once 'autoload.php';

// логіка по активації\деактивації\видалення плагіна
register_activation_hook( __FILE__, 'activation_plugin' );
register_deactivation_hook( __FILE__, 'deactivation_plugin' );
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'cocojambo_add_plugin_links' );
require_once 'install_plugin.php';
// логіка по активації\деактивації\видалення плагіна

require_once 'src/class-cocojambo.php';
require_once 'src/class-left-menu.php';
//require_once 'src/Study.php';
function run() {
	$plugin = new Cocojambo_Panel();
	$cocojambo_menu = new Cocojambo_Left_Menu();

//	$cocojambo_study = new CocojamboStudy();
//	$cocojambo_study->convertTitle();
//	$cocojambo_study->addPrefixToPostTitle();
}
run();


add_action( 'admin_init', 'cocojambo_add_settings' );
add_action( 'init', 'cocojambo_add_post_type' );

add_filter( 'template_include', 'cocojambo_get_theme_template' );


add_filter( 'template_include', 'cocojambo_get_theme_template' );


add_shortcode( 'cocojambo_code', 'cocojambo_code' );
add_action( 'init', 'gutenberg_examples_block' );
add_action( 'add_meta_boxes', 'cocojambo_add_metaboxes' );
add_action( 'save_post', 'cocojambo_save_metaboxes' );

function cocojambo_save_metaboxes( $postId ) {
	if ( ! isset( $_POST['cocojambo_nonce'] )
	     || ! wp_verify_nonce( $_POST['cocojambo_nonce'], 'cocojambo_action' ) ) {
		return;
	}

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

	if (! current_user_can('edit_post', $postId)) return;

	if (! empty($_POST['book_pages'])) {
		update_post_meta($postId, 'book_pages', sanitize_text_field($_POST['book_pages']));
	} else {
		delete_post_meta($postId, 'book_pages');
	}

	if (! empty($_POST['book_cover']) && $_POST['book_cover'] == 'on') {
		update_post_meta($postId, 'book_cover', 'yes');
	} else {
		delete_post_meta($postId, 'book_cover');
	}
}

function cocojambo_add_metaboxes() {
	add_meta_box( 'cocojambo_book_info', __( 'Book info', 'cocojambo' ), 'cocojambo_book_info_cb', array( 'book' ) );
}

function cocojambo_book_info_cb($post) {

	wp_nonce_field( 'cocojambo_action', 'cocojambo_nonce' );
	$book_pages = get_post_meta($post->ID, 'book_pages', true);
	$book_cover = get_post_meta($post->ID, 'book_cover', true);
	?>

	<table class="form-table">
		<tbody>

		<tr>
			<th><label for="book_pages"><?php _e( 'Number of pages', 'cocojambo' ) ?></label></th>
			<td><input type="text" id="book_pages" name="book_pages"
			           value="<?php  echo esc_attr($book_pages) ?>"
			           class="regular-text"></td>
		</tr>

		<tr>
			<th><label for="book_cover"><?php _e( 'Cover?', 'cocojambo' ) ?></label></th>
			<td><input type="checkbox" id="book_cover"
			           <?php  checked('yes', $book_cover) ?>
			           name="book_cover"></td>
		</tr>

		</tbody>
	</table>

	<?php
}

function gutenberg_examples_block() {
	register_block_type( __DIR__ . '/block/first' );
}

function cocojambo_code( $attr ) {
	$attr  = shortcode_atts( [
		'tag'   => 'h3',
		'class' => 'btn btn-primary'
	], $attr );
	$tag   = esc_html( $attr['tag'] );
	$class = esc_html( $attr['class'] );

	return " <{$tag} class='{$class}'> Shortcode!!!</{$tag}>";
}



function cocojambo_get_theme_template( $template ) {
	if ( is_singular( 'book' ) ) {
		if ( ! file_exists( get_template_directory() . '/single-book.php' ) ) {
			return COCOJAMBO_PLUGIN_DIR . 'templates/public/single-book.php';
		}
	}

	if ( is_post_type_archive( 'book' ) ) {
		if ( ! file_exists( get_template_directory() . '/archive-book.php' ) ) {
			return COCOJAMBO_PLUGIN_DIR . 'templates/public/archive-book.php';
		}
	}

	if ( is_tax( 'genre' ) ) {
		if ( ! file_exists( get_template_directory() . '/taxonomy-genre.php' ) ) {
			return COCOJAMBO_PLUGIN_DIR . 'templates/public/taxonomy-genre.php';
		}
	}

	return $template;
}

function cocojambo_add_post_type() {

	register_taxonomy( 'genre', 'book', [
		'hierarchical'      => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => [
			'slug' => 'books/genre',
		],
		'labels'            => [
			'name'                => __( 'Genres', 'cocojambo' ),
			'singular_name'       => __( 'Genre', 'cocojambo' ),
			'all_items'           => __( 'All Genres', 'cocojambo' ),
			'edit_item'           => __( 'Edit Genres', 'cocojambo' ),
			'update_item'         => __( 'Update Genres', 'cocojambo' ),
			'add_new_item'        => __( 'Add New Genres', 'cocojambo' ),
			'new_item_name'       => __( 'New Genres Name', 'cocojambo' ),
			'add_or_remove_items' => __( 'Add or remove Genres', 'cocojambo' ),
			'menu_name'           => __( 'Genres', 'cocojambo' ),
		]
	] );
	register_post_type( 'book', [
		'label'        => __( 'Books', 'cocojambo' ),
		'public'       => true,
		'supports'     => [
			'title',
			'editor',
			'thumbnail'
		],
		'has_archive'  => true,
		'rewrite'      => [
			'slug' => 'books'
		],
		'show_in_rest' => true
	] );
}

function cocojambo_add_settings() {
	register_setting( 'cocojambo_main_group', 'cocojambo_main_email' );
	register_setting( 'cocojambo_main_group', 'cocojambo_main_name' );

	add_settings_section(
		'cocojambo_main_first',
		__( 'Main Section One', 'cocojambo' ),
		function () {
			echo '<p>' . __( 'Main Section Description', 'cocojambo' ) . ' </p>';
		},
		'add-new-slide' );

	add_settings_section(
		'cocojambo_main_second',
		__( 'Main Section Second', 'cocojambo' ),
		'',
		'add-new-slide' );

	add_settings_field(
		'cocojambo_main_email',
		__( 'Email', 'cocojambo' ),
		'main_email_field',
		'add-new-slide',
		'cocojambo_main_first',
		[ 'label_for' => 'cocojambo_main_email' ]
	);

	add_settings_field(
		'cocojambo_main_name',
		__( 'Name', 'cocojambo' ),
		'main_name_field',
		'add-new-slide',
		'cocojambo_main_second',
		[ 'label_for' => 'cocojambo_main_name' ]
	);
}

function main_email_field() {
	echo '<input 
	name="cocojambo_main_email" 
	id="cocojambo_main_email" 
	type="text" 
	value="' . esc_attr( get_option( 'cocojambo_main_email' ) ) . '"
	class="regular-text code" />';
}

function main_name_field() {
	echo '<input 
	name="cocojambo_main_name" 
	id="cocojambo_main_name" 
	type="time" 
	value="' . esc_attr( get_option( 'cocojambo_main_name' ) ) . '"
	class="regular-text code" />';
}


