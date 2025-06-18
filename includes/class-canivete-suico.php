<?php
// Ficheiro: includes/class-canivete-suico.php

if ( ! defined( 'ABSPATH' ) ) exit;

class Canivete_Suico {

    public function __construct() {
        $this->load_dependencies();
        $this->load_active_modules();
    }
    
    private function load_dependencies() {
        require_once plugin_dir_path( __FILE__ ) . 'core/class-settings-manager.php';
        new CS_Settings_Manager();
    }

    private function load_active_modules() {
        $options = get_option( 'canivete_suico_options' );
        if ( empty( $options ) ) return;

        $modules = [
            'maintenance_mode_module'   => 'maintenance-mode/maintenance-mode.php',
            'hide_wp_footprints'        => 'wp-obfuscation/wp-obfuscation.php',
            'enable_svg_upload'         => 'svg-support/svg-support.php',
            'disable_gutenberg'         => 'disable-gutenberg/disable-gutenberg.php',
            'enable_duplicate_post'     => 'duplicate-post/duplicate-post.php',
            'show_last_updated_date'    => 'last-updated-date/last-updated-date.php',
            // NOVO: Carrega o módulo para desativar comentários
            'disable_comments'          => 'disable-comments/disable-comments.php',
            'admin_menu_order'           => 'admin-menu-order/admin-menu-order.php',
            'meta_description_limit'     => 'meta-description-limit/meta-description-limit.php',
            'external_links_newtab'      => 'external-links-newtab/external-links-newtab.php',
            'reading_time'               => 'reading-time/reading-time.php',
            'title_limit'                => 'title-limit/title-limit.php',
            'last_login_column'          => 'last-login-column/last-login-column.php',
            'custom_login_logo'          => 'custom-login-logo/custom-login-logo.php',
            'custom_wp_admin_url'        => 'custom-wp-admin-url/custom-wp-admin-url.php',
            'visit_counter'              => 'visit-counter/visit-counter.php'
        ];
        
        foreach ( $modules as $option => $file ) {
            if ( isset( $options[$option] ) ) {
                require_once plugin_dir_path( __FILE__ ) . 'modules/' . $file;
            }
        }
        
        // Módulos com múltiplas opções
        if ( isset( $options['disable_right_click'] ) || isset( $options['disable_text_selection'] ) ) { require_once plugin_dir_path( __FILE__ ) . 'modules/content-protection/content-protection.php'; }
        if ( isset( $options['disable_rss_feeds'] ) || isset( $options['disable_rest_api'] ) ) { require_once plugin_dir_path( __FILE__ ) . 'modules/endpoint-protection/endpoint-protection.php'; }
        if ( isset( $options['enable_classic_widgets'] ) || isset( $options['disable_update_emails'] ) ) { require_once plugin_dir_path( __FILE__ ) . 'modules/admin-tweaks/admin-tweaks.php'; }
        if ( isset( $options['use_site_logo_for_login'] ) || isset( $options['use_site_icon_for_admin'] ) || ! empty( $options['custom_admin_footer'] ) || isset( $options['disable_login_lang_switcher'] ) ) { require_once plugin_dir_path( __FILE__ ) . 'modules/white-labeling/white-labeling.php'; }
    }
}