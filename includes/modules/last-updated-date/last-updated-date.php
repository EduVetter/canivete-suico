<?php
/**
 * Módulo para Exibir a Data da Última Atualização nos Posts
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class CS_Last_Updated_Date {

    public function __construct() {
        // O filtro 'the_content' permite modificar o conteúdo principal de um post
        add_filter( 'the_content', array( $this, 'display_last_updated_date' ) );
    }

    /**
     * Adiciona a data de publicação e atualização antes do conteúdo do post.
     */
    public function display_last_updated_date( $content ) {
        // 1. CONDIÇÕES DE EXIBIÇÃO
        // Só queremos que isto apareça em páginas de post single, e não em listas de arquivo, home, etc.
        // in_the_loop() e is_main_query() são verificações extra para garantir que estamos no local certo.
        if ( is_single() && in_the_loop() && is_main_query() ) {

            // 2. OBTENÇÃO DAS DATAS
            // 'U' retorna a data como um timestamp (número de segundos desde 1970), o que facilita a comparação.
            $published_time = get_the_time('U');
            $modified_time = get_the_modified_time('U');

            // 3. COMPARAÇÃO DAS DATAS
            // Comparamos para ver se o post foi de facto modificado (com uma margem de 1 minuto para evitar mostrar em pequenas correções no momento de publicar)
            if ( $modified_time >= ( $published_time + 60 ) ) {

                // 4. MONTAGEM DO HTML
                // Se foi modificado, mostramos ambas as datas.
                $published_date = get_the_time( get_option('date_format') ); // Formato de data definido em Configurações > Geral
                $modified_date = get_the_modified_time( get_option('date_format') );

                $display_text = sprintf(
                    '<p class="cs-last-updated"><em>%1$s %2$s | %3$s %4$s</em></p>',
                    esc_html__( 'Publicado em:', 'canivete-suico' ),
                    esc_html( $published_date ),
                    esc_html__( 'Atualizado em:', 'canivete-suico' ),
                    esc_html( $modified_date )
                );
            } else {
                // Se não foi modificado, mostramos apenas a data de publicação.
                $published_date = get_the_time( get_option('date_format') );
                $display_text = sprintf(
                    '<p class="cs-last-updated"><em>%1$s %2$s</em></p>',
                    esc_html__( 'Publicado em:', 'canivete-suico' ),
                    esc_html( $published_date )
                );
            }
            
            // 5. RETORNO DO CONTEÚDO
            // Adicionamos o nosso texto ANTES do conteúdo original do post.
            return $display_text . $content;
        }

        // Se as condições não forem atendidas, retorna o conteúdo original sem modificação.
        return $content;
    }
}
new CS_Last_Updated_Date();