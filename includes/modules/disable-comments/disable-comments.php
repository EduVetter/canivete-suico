<?php
/**
 * Módulo para Desativar Globalmente os Comentários
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class CS_Disable_Comments {

    public function __construct() {
        // Desativa o suporte a comentários em todos os tipos de post
        add_action('init', array( $this, 'remove_comment_support' ), 100);

        // Fecha os comentários e pings no frontend
        add_filter('comments_open', '__return_false', 20, 2);
        add_filter('pings_open', '__return_false', 20, 2);

        // Esconde os comentários existentes
        add_filter('comments_array', '__return_empty_array', 10, 2);

        // Remove links e menus do painel de administração
        add_action('admin_menu', array( $this, 'remove_admin_menus' ));
        add_action('admin_init', array( $this, 'remove_comment_metabox' ));
        add_action('wp_before_admin_bar_render', array( $this, 'remove_admin_bar_links' ));
    }

    public function remove_comment_support() {
        // Percorre todos os tipos de post registados
        $post_types = get_post_types();
        foreach ($post_types as $post_type) {
            // Se o tipo de post suporta comentários, remove esse suporte
            if (post_type_supports($post_type, 'comments')) {
                remove_post_type_support($post_type, 'comments');
                remove_post_type_support($post_type, 'trackbacks');
            }
        }
    }

    public function remove_admin_menus() {
        // Remove a página principal de "Comentários" do menu
        remove_menu_page('edit-comments.php');
    }

    public function remove_comment_metabox() {
        // Remove a metabox "Discussão" das telas de edição de posts
        remove_meta_box('discussiondiv', 'post', 'normal');
        remove_meta_box('discussiondiv', 'page', 'normal');
        // Remove a metabox "Comentários"
        remove_meta_box('commentsdiv', 'post', 'normal');
        remove_meta_box('commentsdiv', 'page', 'normal');
    }

    public function remove_admin_bar_links() {
        global $wp_admin_bar;
        // Remove o link de comentários da barra de administração no topo
        $wp_admin_bar->remove_node('comments');
    }
}
new CS_Disable_Comments();