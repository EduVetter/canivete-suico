<?php
/**
 * Módulo com vários ajustes para o Painel de Administração do WordPress
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class CS_Admin_Tweaks {
    
    private $options;

    public function __construct() {
        $this->options = get_option( 'canivete_suico_options' );

        // Ativa os widgets clássicos, se a opção estiver marcada
        if ( isset( $this->options['enable_classic_widgets'] ) ) {
            $this->enable_classic_widgets();
        }

        // Desativa os e-mails de atualização automática, se a opção estiver marcada
        if ( isset( $this->options['disable_update_emails'] ) ) {
            // Para o CORE do WordPress
            add_filter( 'auto_core_update_send_email', '__return_false' );
            // Para PLUGINS
            add_filter( 'auto_plugin_update_send_email', '__return_false' );
            // Para TEMAS
            add_filter( 'auto_theme_update_send_email', '__return_false' );
        }
    }

    /**
     * Função para reativar a interface clássica de widgets
     */
    private function enable_classic_widgets() {
        // Este filtro desativa o editor de blocos na página de widgets
        add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
        add_filter( 'use_widgets_block_editor', '__return_false' );

        // NOVO: Força a exibição do menu de widgets caso o tema o tenha escondido.
        // Isto acontece em temas de bloco (Full Site Editing).
        add_action( 'admin_menu', function() {
            global $submenu;
            // Verifica se o submenu 'widgets.php' já existe dentro de 'themes.php' (Aparência)
            if ( isset( $submenu['themes.php'] ) ) {
                foreach ( $submenu['themes.php'] as $item ) {
                    // Se encontrar, não faz nada
                    if ( $item[2] === 'widgets.php' ) {
                        return;
                    }
                }
            }
            // Se não encontrou, adiciona o menu de volta
            add_submenu_page(
                'themes.php', // O menu "pai" (Aparência)
                esc_html__( 'Widgets', 'canivete-suico' ), // O título da página
                esc_html__( 'Widgets', 'canivete-suico' ), // O texto do menu
                'edit_theme_options', // A permissão necessária para ver
                'widgets.php' // O link para a página de widgets clássica
            );
        });
    }
}

new CS_Admin_Tweaks();