<?php
/**
 * Módulo de contador de visitas com integração Elementor e Gutenberg.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// Incrementa o contador a cada visita
add_action( 'init', function() {
    if ( ! is_admin() ) {
        $count = (int) get_option( 'cs_visit_counter', 0 );
        update_option( 'cs_visit_counter', $count + 1 );
    }
});

// Shortcode para exibir o contador
add_shortcode( 'cs_visit_counter', function( $atts ) {
    $count = (int) get_option( 'cs_visit_counter', 0 );
    $atts = shortcode_atts([
        'class' => 'cs-visit-counter',
    ], $atts);
    return '<span class="' . esc_attr($atts['class']) . '">' . esc_html($count) . '</span>';
});

// Bloco Gutenberg
add_action( 'init', function() {
    if ( function_exists('register_block_type') ) {
        register_block_type( 'canivete-suico/visit-counter', [
            'render_callback' => function() {
                $count = (int) get_option( 'cs_visit_counter', 0 );
                return '<span class="cs-visit-counter">' . esc_html($count) . '</span>';
            },
            'attributes' => [],
        ] );
    }
});

// Widget Elementor
add_action( 'elementor/widgets/register', function( $widgets_manager ) {
    if ( class_exists( '\Elementor\Widget_Base' ) ) {
        require_once __DIR__ . '/widget-elementor-visit-counter.php';
        $widgets_manager->register( new \CS_Elementor_Visit_Counter_Widget() );
    }
});
