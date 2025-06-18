<?php
/**
 * Módulo de Proteção de Conteúdo
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class CS_Content_Protection {
    private $options;

    public function __construct() {
        $this->options = get_option( 'canivete_suico_options' );
        add_action( 'wp_enqueue_scripts', array( $this, 'apply_protection' ), 999 );
    }

    public function apply_protection() {
        // A proteção só se aplica se o utilizador NÃO for um administrador.
        if ( ! current_user_can( 'manage_options' ) ) {

            // Prepara as variáveis para o JavaScript de forma segura
            $protection_vars = array();
            
            // Lógica do Clique Direito
            if ( isset( $this->options['disable_right_click'] ) ) {
                $protection_vars['right_click_message'] = isset( $this->options['disable_right_click_message'] ) ? $this->options['disable_right_click_message'] : '';
            }

            // Lógica da Seleção/Cópia de Texto
            if ( isset( $this->options['disable_text_selection'] ) ) {
                $protection_vars['copy_message'] = isset( $this->options['disable_copy_message'] ) ? $this->options['disable_copy_message'] : '';
                
                // Adiciona o estilo CSS para desativar a seleção visual
                add_action( 'wp_head', function() {
                    echo "<style>body, body * {-webkit-user-select: none !important; -moz-user-select: none !important; -ms-user-select: none !important; user-select: none !important;}</style>";
                });
            }
            
            // Adiciona o script no rodapé se alguma proteção estiver ativa
            if ( ! empty( $protection_vars ) ) {
                add_action( 'wp_footer', function() use ( $protection_vars ) {
                    ?>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Proteção contra Clique Direito
                        const rightClickMessage = <?php echo json_encode( $protection_vars['right_click_message'] ?? '' ); ?>;
                        if (rightClickMessage !== undefined) {
                            document.addEventListener('contextmenu', function(event) {
                                event.preventDefault();
                                if (rightClickMessage) {
                                    alert(rightClickMessage);
                                }
                            });
                        }

                        // Proteção contra Cópia (Ctrl+C)
                        const copyMessage = <?php echo json_encode( $protection_vars['copy_message'] ?? '' ); ?>;
                        if (copyMessage !== undefined) {
                            document.addEventListener('copy', function(event) {
                                event.preventDefault();
                                if (copyMessage) {
                                    alert(copyMessage);
                                }
                            });
                        }
                    });
                    </script>
                    <?php
                });
            }
        }
    }
}
new CS_Content_Protection();