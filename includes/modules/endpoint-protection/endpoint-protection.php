<?php
/**
 * Módulo de Proteção de Endpoints
 * - Desativa Feeds RSS
 * - Desativa REST API para o público
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class CS_Endpoint_Protection {
    
    private $options;

    public function __construct() {
        $this->options = get_option( 'canivete_suico_options' );

        if ( isset( $this->options['disable_rss_feeds'] ) ) {
            $this->disable_feeds();
        }

        if ( isset( $this->options['disable_rest_api'] ) ) {
            $this->disable_rest_api();
        }
    }

    /**
     * Adiciona os hooks para desativar todos os tipos de feed.
     */
    private function disable_feeds() {
        $feeds = ['do_feed', 'do_feed_rdf', 'do_feed_rss', 'do_feed_rss2', 'do_feed_atom'];
        foreach ($feeds as $feed) {
            add_action( $feed, array( $this, 'feed_disabled_message' ), 1 );
        }
    }

    /**
     * Mostra uma mensagem de erro e morre. É a função chamada pelos hooks de feed.
     */
    public function feed_disabled_message() {
        $message = __( 'Os feeds RSS foram desativados neste site.', 'canivete-suico' );
        wp_die( esc_html( $message ) );
    }

    /**
     * Adiciona o filtro para desativar a REST API para utilizadores não logados.
     */
    private function disable_rest_api() {
        add_filter( 'rest_authentication_errors', array( $this, 'only_allow_logged_in_rest_access' ) );
    }

    /**
     * Retorna um erro se um utilizador não logado tentar aceder à REST API.
     * Permite que administradores e outros plugins continuem a usar a API normalmente.
     */
    public function only_allow_logged_in_rest_access( $result ) {
        if ( ! is_user_logged_in() ) {
            return new WP_Error(
                'rest_not_logged_in',
                __( 'Acesso negado. A REST API não está disponível para o público.', 'canivete-suico' ),
                array( 'status' => 401 ) // 401 é o código para "Não Autorizado"
            );
        }
        return $result;
    }
}
new CS_Endpoint_Protection();