<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/templates/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

$timber_post     = Timber::get_post();
$context['post'] = $timber_post;
if ( get_field( 'archive_group', $timber_post->ID ) ) {
	$archive_term = get_field( 'archive_group', $timber_post->ID );
	$args         = array(
		'post_type'   => 'section',
		'post_status' => 'publish',
		'tax_query'   => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'header',
				'field'    => 'slug',
				'terms'    => array(
					'archive',
					$archive_term->slug,
				),
			),
		),
	);
	$query                    = new WP_Query( $args );
	$archive_posts            = $query->get_posts();
	foreach ( $archive_posts as $archive_post ) {
		$context['archive_posts'][] = Timber::get_post( $archive_post->ID );
	}
}

Timber::render( array( 'page-' . $timber_post->post_name . '.twig', 'page.twig' ), $context );
