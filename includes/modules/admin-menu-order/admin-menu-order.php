<?php
/**
 * Permite reordenar itens do menu de administração.
 * Exemplo: Coloca Posts, Mídia, Páginas, Comentários no topo.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_filter( 'custom_menu_order', '__return_true' );
add_filter( 'menu_order', function( $menu_order ) {
    $desired = array( 'index.php', 'edit.php', 'upload.php', 'edit.php?post_type=page', 'edit-comments.php' );
    $new_order = array();
    foreach ( $desired as $item ) {
        if ( ( $key = array_search( $item, $menu_order ) ) !== false ) {
            $new_order[] = $menu_order[$key];
            unset( $menu_order[$key] );
        }
    }
    return array_merge( $new_order, $menu_order );
} );
