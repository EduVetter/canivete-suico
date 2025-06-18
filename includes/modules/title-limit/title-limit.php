<?php
/**
 * Limita títulos a 60 caracteres em todo o site.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'the_title', function( $title ) {
    return mb_substr( $title, 0, 60 );
}, 99 );
