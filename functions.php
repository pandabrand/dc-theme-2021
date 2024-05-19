<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */
// Load Composer dependencies.
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/src/DCSite.php';

/** asset path definitions */
define( 'ASSET_DIR', '/build/' );
define( 'ASSET_CSS', ASSET_DIR . 'css/' );
define( 'ASSET_JS', ASSET_DIR . 'js/' );
define( 'ASSET_IMG', ASSET_DIR . 'img/' );

function asset_path( $filename ) {
	$manifest_path = get_stylesheet_directory() . ASSET_DIR . 'manifest.json';

	if ( file_exists( $manifest_path ) ) {
		$manifest = json_decode( file_get_contents( $manifest_path ), true );
	} else {
		$manifest = array();
	}

	if ( array_key_exists( $filename, $manifest ) ) {
		return $manifest[ $filename ];
	}

	return $filename;
}

function dc2020_enqueue() {
	wp_enqueue_style( 'long-cang', 'https://fonts.googleapis.com/css2?family=Long+Cang' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'letterize', 'https://cdn.jsdelivr.net/npm/letterizejs@2.0.1/lib/letterize.min.js', array(), true );
	wp_enqueue_script( 'anime', 'https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js', array(), true );
	wp_enqueue_script( 'dc2020', get_stylesheet_directory_uri() . ASSET_JS . asset_path( 'site.js' ), array( 'anime' ), true );
	wp_enqueue_style( 'gl-lightbox-css', 'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css' );
	wp_enqueue_script( 'gl-lightbox-js', 'https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js', array(), true );
	wp_enqueue_style( 'dc2020', get_stylesheet_directory_uri() . ASSET_CSS . asset_path( 'style.css' ) );
}
add_action( 'wp_enqueue_scripts', 'dc2020_enqueue' );

function dc2021_nav_menu() {
	register_nav_menus(
		array(
			'front' => __( 'Front Menu', 'dc20201-domain' ),
		)
	);
}

add_action( 'after_setup_theme', 'dc2021_nav_menu', 0 );

$timber = Timber\Timber::init();

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

new DCSite();

include_once( 'inc/acf.php' );
include_once( 'inc/twig-func.php' );

