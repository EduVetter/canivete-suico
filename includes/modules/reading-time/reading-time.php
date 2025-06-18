<?php
/**
 * Exibe o tempo estimado de leitura antes do conteúdo da postagem.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'the_content', function( $content ) {
    if ( is_single() && in_the_loop() && is_main_query() ) {
        $words = str_word_count( wp_strip_all_tags( $content ) );
        $minutes = max( 1, ceil( $words / 200 ) );
        $label = sprintf( __( 'Tempo de leitura: %d min', 'canivete-suico' ), $minutes );
        $html = '<p class="cs-reading-time"><em>' . esc_html( $label ) . '</em></p>';
        return $html . $content;
    }
    return $content;
}, 8 );
