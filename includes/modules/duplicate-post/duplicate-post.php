<?php
/**
 * Módulo para Duplicar Posts, Páginas e Tipos de Post Personalizados
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class CS_Duplicate_Post {

    public function __construct() {
        // Adiciona o link "Duplicar" na lista de Posts
        add_filter( 'post_row_actions', array( $this, 'add_duplicate_link' ), 10, 2 );
        // Adiciona o link "Duplicar" na lista de Páginas
        add_filter( 'page_row_actions', array( $this, 'add_duplicate_link' ), 10, 2 );
        // Adiciona a ação que vai de facto executar a duplicação
        add_action( 'admin_action_cs_duplicate_post_action', array( $this, 'duplicate_post_logic' ) );
    }

    /**
     * Adiciona o link "Duplicar" ao array de ações da linha do post.
     */
    public function add_duplicate_link( $actions, $post ) {
        // Apenas adiciona o link se o utilizador tiver permissão para editar posts
        if ( ! current_user_can( 'edit_posts' ) ) {
            return $actions;
        }
        
        // Constrói a URL para a ação de duplicação
        // wp_nonce_url cria uma URL com um "token" de segurança (nonce) para evitar ataques CSRF
        $url = wp_nonce_url(
            admin_url( 'admin.php?action=cs_duplicate_post_action&post=' . $post->ID ),
            'cs_duplicate_nonce', // Nome da ação do nonce
            'cs_nonce'            // Nome do argumento do nonce na URL
        );

        // Adiciona o nosso link ao array de ações existentes
        $actions['duplicate'] = sprintf( '<a href="%s" aria-label="%s">%s</a>',
            esc_url( $url ),
            esc_attr( sprintf( __( 'Duplicar “%s”', 'canivete-suico' ), get_the_title( $post->ID ) ) ),
            esc_html__( 'Duplicar', 'canivete-suico' )
        );
        
        return $actions;
    }

    /**
     * A lógica principal que executa a duplicação.
     */
    public function duplicate_post_logic() {
        // 1. VERIFICAÇÕES DE SEGURANÇA
        // Se o nonce não existir ou for inválido, interrompe.
        if ( ! isset( $_GET['cs_nonce'] ) || ! wp_verify_nonce( $_GET['cs_nonce'], 'cs_duplicate_nonce' ) ) {
            wp_die( esc_html__( 'A verificação de segurança falhou.', 'canivete-suico' ) );
        }
        // Se o ID do post não for passado, interrompe.
        if ( ! isset( $_GET['post'] ) || ! is_numeric( $_GET['post'] ) ) {
            wp_die( esc_html__( 'Nenhum post para duplicar foi fornecido.', 'canivete-suico' ) );
        }
        // Se o utilizador não tiver permissão, interrompe.
        if ( ! current_user_can( 'edit_posts' ) ) {
            wp_die( esc_html__( 'Você não tem permissão para duplicar posts.', 'canivete-suico' ) );
        }

        $post_id = absint( $_GET['post'] );
        $post = get_post( $post_id );

        // 2. PREPARA OS DADOS DO NOVO POST
        $current_user = wp_get_current_user();
        $new_post_args = array(
            'post_author'    => $current_user->ID,
            'post_content'   => $post->post_content,
            'post_title'     => sprintf( __( 'Cópia de: %s', 'canivete-suico' ), $post->post_title ),
            'post_excerpt'   => $post->post_excerpt,
            'post_status'    => 'draft', // Cria a cópia como um rascunho
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_password'  => $post->post_password,
            'post_parent'    => $post->post_parent,
            'post_type'      => $post->post_type,
            'menu_order'     => $post->menu_order,
        );

        // 3. INSERE O NOVO POST NO BANCO DE DADOS
        $new_post_id = wp_insert_post( $new_post_args );
        if ( is_wp_error( $new_post_id ) ) {
            wp_die( $new_post_id->get_error_message() );
        }

        // 4. COPIA AS TAXONOMIAS (CATEGORIAS, TAGS, ETC.)
        $taxonomies = get_object_taxonomies( $post->post_type );
        foreach ( $taxonomies as $taxonomy ) {
            $post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
            wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
        }

        // 5. COPIA OS METADADOS (CAMPOS PERSONALIZADOS)
        $post_meta = get_post_meta( $post_id );
        foreach ( $post_meta as $meta_key => $meta_values ) {
            // Não copiamos certos campos internos
            if ( in_array( $meta_key, array( '_edit_lock', '_edit_last' ) ) ) {
                continue;
            }
            foreach ( $meta_values as $meta_value ) {
                add_post_meta( $new_post_id, $meta_key, maybe_unserialize( $meta_value ) );
            }
        }
        
        // 6. REDIRECIONA O UTILIZADOR DE VOLTA À LISTA DE POSTS
        wp_safe_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );
        exit;
    }
}
new CS_Duplicate_Post();