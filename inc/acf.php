<?php
add_filter( 'acf/settings/save_json', 'dc_acf_save_point' );

function dc_acf_save_point( $path ) {
    $path = get_stylesheet_directory() . '/acf-json';
    return $path;
}

add_filter( 'acf/settings/load_json', 'dc_acf_load_point' );

function dc_acf_load_point( $paths ) {
    unset( $paths[0] );
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
}
