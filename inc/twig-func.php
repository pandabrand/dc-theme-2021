<?php

add_filter( 'timber/twig', 'add_to_twig' );

function add_to_twig( $twig ) {
	$twig->addFunction( new Twig\TwigFunction( 'masonry_order', 'masonry_order' ) );
	return $twig;
}

function masonry_order( $posts, $cols = 2 ) {
	$reorder = array();
	$col     = 0;
	while ( $col < $cols ) {
		for ( $i = 0; $i < count( $posts ); $i += $cols ) {
			$val = $posts[ $i + $col ] ?? null;

			if ( ! empty( $val ) ) {
				$reorder[] = $val;
			}
		}
		$col++;
	}
	return $reorder;
}
