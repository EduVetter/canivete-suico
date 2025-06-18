<?php
/**
 * Permite alterar o link de acesso ao wp-admin/login.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'init', function() {
    $options = get_option( 'canivete_suico_options' );
    $custom_slug = isset($options['custom_wp_admin_url']) ? trim($options['custom_wp_admin_url'], '/') : '';
    if ( $custom_slug ) {
        add_rewrite_rule( '^' . preg_quote($custom_slug, '#') . '/?$', 'wp-login.php', 'top' );
    }
});

add_filter( 'site_url', function( $url, $path, $scheme, $blog_id ) {
    $options = get_option( 'canivete_suico_options' );
    $custom_slug = isset($options['custom_wp_admin_url']) ? trim($options['custom_wp_admin_url'], '/') : '';
    if ( $custom_slug && in_array($path, ['wp-login.php', 'wp-login.php?action=register', 'wp-login.php?action=lostpassword']) ) {
        return home_url( '/' . $custom_slug . '/', $scheme );
    }
    return $url;
}, 10, 4 );

add_action( 'template_redirect', function() {
    $options = get_option( 'canivete_suico_options' );
    $custom_slug = isset($options['custom_wp_admin_url']) ? trim($options['custom_wp_admin_url'], '/') : '';
    $request_uri = trim( $_SERVER['REQUEST_URI'], '/' );
    if ( $custom_slug ) {
        // Bloqueia acesso ao wp-login.php e redireciona
        if ( preg_match( '#wp-login\.php#', $request_uri ) && $request_uri !== $custom_slug ) {
            wp_redirect( home_url() );
            exit;
        }
        // Bloqueia acesso ao wp-admin para não logados
        if ( preg_match( '#wp-admin#', $request_uri ) && !is_user_logged_in() ) {
            wp_redirect( home_url() );
            exit;
        }
        // Permite acesso ao novo slug
        if ( $request_uri === $custom_slug ) {
            require_once ABSPATH . 'wp-login.php';
            exit;
        }
    }
});

// Recomenda flush nas regras de reescrita ao salvar a opção
add_action( 'update_option_canivete_suico_options', function($old, $new) {
    if ( $old['custom_wp_admin_url'] !== $new['custom_wp_admin_url'] ) {
        flush_rewrite_rules();
    }
}, 10, 2 );
