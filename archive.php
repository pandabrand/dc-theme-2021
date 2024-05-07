<?php
/**
 * The template for displaying Archive pages.
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.2
 */

$templates = array( 'archive-section.twig', 'archive.twig', 'index.twig' );

$context          = Timber::context();
$context['posts'] = new Timber\PostQuery();

$context['title'] = '';
if ( is_day() ) {
	$context['title'] = 'Archive: ' . get_the_date( 'D M Y' );
} elseif ( is_month() ) {
	$context['title'] = 'Archive: ' . get_the_date( 'M Y' );
} elseif ( is_year() ) {
	$context['title'] = 'Archive: ' . get_the_date( 'Y' );
} elseif ( is_tag() ) {
	$context['title'] = single_tag_title( '', false );
} elseif ( is_category() ) {
	$context['title'] = single_cat_title( '', false );
	array_unshift( $templates, 'archive-' . get_query_var( 'cat' ) . '.twig' );
} elseif ( is_post_type_archive() ) {
	$context['title'] = post_type_archive_title( '', false );
	array_unshift( $templates, 'archive-' . get_post_type() . '.twig' );
} elseif ( is_tax( 'header' ) && 'animation' === get_query_var( 'term' ) ) {
	$works = array_map(
		function ( $post ) {
				return get_field( 'works', $post->ID );
		},
		$context['posts']->get_posts(),
	);

	foreach ( $works[0] as $work ) {
		$context['works'][] = new Timber\Post( $work->ID );
	}
	array_unshift( $templates, 'taxonomy-' . get_query_var( 'taxonomy' ) . '-' . get_query_var( 'term' ) . '.twig' );
	$term             = get_term_by( 'slug', get_query_var( 'term' ), 'header' );
	$context['title'] = $term->name;
} elseif ( is_tax( 'header' ) ) {
	$term             = get_term_by( 'slug', get_query_var( 'term' ), 'header' );
	$context['title'] = $term->name;
}

if ( is_tax( 'header' ) && ( 'drawings-paintings' === get_query_var( 'term' ) || 'projects' === get_query_var( 'term' ) ) ) {
	$args = array(
		'post_type'   => 'section',
		'post_status' => 'publish',
		'tax_query'   => array(
			'relation' => 'AND',
			array(
				'taxonomy' => 'header',
				'field'    => 'slug',
				'terms'    => array(
					'archive',
				),
			),
			array(
				'taxonomy' => 'header',
				'field'    => 'slug',
				'terms'    => array(
					get_query_var( 'term' ),
				),
			),
		),
	);
	$query         = new WP_Query( $args );
	$archive_posts = $query->get_posts();
	$context['archive_posts'] = $archive_posts;

	if ( ! empty( $archive_posts ) ) {
		$context['archive_link'] = '/archive-' . get_query_var( 'term' );
	}
}
Timber::render( $templates, $context );
