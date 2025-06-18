<?php
/**
 * Adiciona uma coluna "Último login" à tabela de usuários.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// Salva o horário do último login
add_action( 'wp_login', function( $user_login, $user ) {
    update_user_meta( $user->ID, 'cs_last_login', current_time( 'mysql' ) );
}, 10, 2 );

// Adiciona a coluna
add_filter( 'manage_users_columns', function( $columns ) {
    $columns['cs_last_login'] = __( 'Último login', 'canivete-suico' );
    return $columns;
} );

// Preenche a coluna
add_filter( 'manage_users_custom_column', function( $value, $column_name, $user_id ) {
    if ( 'cs_last_login' === $column_name ) {
        $last_login = get_user_meta( $user_id, 'cs_last_login', true );
        return $last_login ? date_i18n( get_option( 'date_format' ) . ' H:i', strtotime( $last_login ) ) : '-';
    }
    return $value;
}, 10, 3 );
