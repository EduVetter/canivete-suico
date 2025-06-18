<?php
/**
 * Lógica do Módulo de Modo Manutenção para o plugin Canivete Suíço.
 */

// Medida de segurança
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Verifica se o modo manutenção deve ser ativado.
 */
function cs_maintenance_mode_redirect() {
    if ( ! is_admin() && ! is_user_logged_in() ) {
        
        // TRADUÇÃO: Preparamos as strings traduzíveis primeiro.
        // Usamos esc_html__() para segurança e para retornar o texto.
        $page_title = esc_html__( 'Site em Manutenção', 'canivete-suico' );
        $message_h1 = esc_html__( 'Site em Manutenção', 'canivete-suico' );
        // TRADUÇÃO: A sua excelente correção agora está aqui e é traduzível!
        $message_p  = esc_html__( 'Estamos realizando algumas atualizações. Por favor, volte mais tarde.', 'canivete-suico' );

        wp_die(
            "<h1>{$message_h1}</h1><p>{$message_p}</p>", // Usamos as variáveis com o texto traduzido.
            $page_title,
            array( 'response' => 503 )
        );
    }
}
add_action( 'template_redirect', 'cs_maintenance_mode_redirect' );