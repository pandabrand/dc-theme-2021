<?php
/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context          = Timber::context();
$timber_post      = Timber::get_post();
$terms            = get_the_terms( $timber_post->ID, 'header' );
$context['post']  = $timber_post;
$context['terms'] = $terms;

if ( ! empty( $terms ) ) {
	$slug_array               = array_map( 'get_archive_slugs', $terms );
	if ( in_array( 'archive', $slug_array, true ) ) {
		$context['archive_title'] = get_archive_slug_title( $terms );
		$context['archive_link']  = '/' . implode( '-', $slug_array );
	}
}

if ( 'work' === get_post_type( $timber_post->ID ) ) {
	$sections            = get_posts(
		array(
			'post_type'  => 'section',
			'meta_query' => array(
				array(
					'value'   => '"' . $timber_post->ID . '"',
					'compare' => 'LIKE',
				),
			),
		),
	);
	$timber_sections     = array_map(
		function( $section ) {
			return new Timber\Post( $section->ID );
		},
		$sections,
	);
	$context['sections'] = $timber_sections;
}

if ( post_password_required( $timber_post->ID ) ) {
	Timber::render( 'single-password.twig', $context );
} else {
	Timber::render( array( 'single-' . $timber_post->ID . '.twig', 'single-' . $timber_post->post_type . '.twig', 'single-' . $timber_post->slug . '.twig', 'single.twig' ), $context );
}

function get_archive_slugs( $term ) {
	return $term->slug;
}

function get_archive_slug_title( $array ) {
	foreach ( $array as $term ) {
		if ( 'Archive' !== $term->name ) {
			return $term->name;
		}
	}
	return false;
}
