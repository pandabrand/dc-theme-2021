<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

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

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

	add_action(
		'admin_notices',
		function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function( $template ) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
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


/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class DCSite extends Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_filter( 'image_size_names_choose', array( $this, 'dc_custom_sizes' ) );
		add_action( 'pre_get_posts', array( $this, 'target_main_query' ) );
		parent::__construct();
	}
	/** This is where you can register custom post types. */
	public function register_post_types() {

	}
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		// $context['foo']   = 'bar';
		// $context['stuff'] = 'I am a value set in your functions.php file';
		// $context['notes'] = 'These values are available everytime you call Timber::context();';
		$context['menu']  = new Timber\Menu();
		$context['site']  = $this;
		return $context;
	}

	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );
		add_image_size( 'archive-cover', 600 );
	}

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param string $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig\Extension\StringLoaderExtension() );
		$twig->addFilter( new Twig\TwigFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		return $twig;
	}


	function dc_custom_sizes( $sizes ) {
		return array_merge(
			$sizes,
			array(
				'archive-cover' => __( 'Archive Cover' ),
			),
		);
	}

	function target_main_query( $query ) {
		if ( ! is_admin() && $query->is_main_query() && ! is_home() ) {
				$query->set( 'orderby', 'menu_order' );
				$query->set( 'order', 'ASC' );
				$query->set( 'posts_per_page', 15 );

			if ( is_archive( 'section' ) ) {
				$current_tax_query   = $query->tax_query->queries;
				$current_tax_query[] = array(
					'taxonomy' => 'header',
					'terms'    => 'archive',
					'field'    => 'slug',
					'operator' => 'NOT IN',
				);
				$query->set( 'tax_query', $current_tax_query );
			}
		}
	}
}

new DCSite();

include_once( 'inc/acf.php' );
include_once( 'inc/twig-func.php' );

