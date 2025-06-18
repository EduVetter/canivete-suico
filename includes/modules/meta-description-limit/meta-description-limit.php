<?php
/**
 * Limita a meta descrição (resumo) a 160 caracteres em todo o site.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'wpseo_metadesc', function( $desc ) {
    return mb_substr( $desc, 0, 160 );
}, 99 );

add_filter( 'get_the_excerpt', function( $excerpt ) {
    return mb_substr( $excerpt, 0, 160 );
}, 99 );
