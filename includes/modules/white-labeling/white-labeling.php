<?php
/**
 * Módulo para Personalização (White Labeling) do WordPress
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class CS_White_Labeling {
    
    private $options;

    public function __construct() {
        $this->options = get_option( 'canivete_suico_options' );
        
        if ( isset( $this->options['use_site_logo_for_login'] ) ) {
            add_action( 'login_head', array( $this, 'custom_login_logo' ) );
            add_filter( 'login_headerurl', array( $this, 'custom_login_logo_url' ) );
        }
        if ( isset( $this->options['use_site_icon_for_admin'] ) ) {
            add_action( 'admin_head', array( $this, 'custom_admin_bar_logo' ) );
        }
        if ( ! empty( $this->options['custom_admin_footer'] ) ) {
            add_filter( 'admin_footer_text', array( $this, 'custom_admin_footer_text' ) );
        }
        if ( isset( $this->options['disable_login_lang_switcher'] ) ) {
            add_filter( 'login_display_language_dropdown', '__return_false' );
        }
    }

    public function custom_login_logo() {
        if ( has_custom_logo() ) {
            $logo_id = get_theme_mod( 'custom_logo' );
            $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
            echo "<style>#login h1 a, .login h1 a { background-image: url(" . esc_url( $logo_url ) . "); background-size: contain; width: 100%; height: 100px; }</style>";
        }
    }
    public function custom_admin_bar_logo() {
        if ( has_site_icon() ) {
            $icon_url = get_site_icon_url();
            echo "<style>#wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before { background-image: url(" . esc_url( $icon_url ) . ") !important; background-size: contain; background-position: center; color: rgba(0,0,0,0); }</style>";
        }
    }
    public function custom_login_logo_url() { return home_url(); }

    public function custom_admin_footer_text() {
        return wp_kses_post( $this->options['custom_admin_footer'] );
    }
}
new CS_White_Labeling();