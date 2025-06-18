<?php
/**
 * Abre automaticamente todos os links externos em nova guia nas postagens.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'the_content', function( $content ) {
    $site_url = home_url();
    return preg_replace_callback( '/<a[^>]+href=["\\\']([^"\\\']+)["\\\'][^>]*>/i', function( $matches ) use ( $site_url ) {
        $href = $matches[1];
        if ( strpos( $href, $site_url ) === false && strpos( $href, 'http' ) === 0 ) {
            // Adiciona target e rel se for externo
            $tag = $matches[0];
            if ( strpos( $tag, 'target=' ) === false ) {
                $tag = str_replace( '<a', '<a target="_blank" rel="noopener noreferrer"', $tag );
            }
            return $tag;
        }
        return $matches[0];
    }, $content );
}, 99 );
