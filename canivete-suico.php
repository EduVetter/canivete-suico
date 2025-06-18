<?php
/**
 * Plugin Name:       Canivete Suíço
 * Plugin URI:        https://eduvetter.com/canivete-suico
 * Description:       Um conjunto de ferramentas e funcionalidades para WordPress e Elementor. Um plugin para governar todos eles.
 * Version:           1.0.1
 * Author:            Eduardo Vetter
 * Author URI:        https://eduvetter.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       canivete-suico
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Carrega a classe principal do plugin
require_once plugin_dir_path( __FILE__ ) . 'includes/class-canivete-suico.php';

// Inicia o plugin
new Canivete_Suico();

/**
 * Cria a página do menu do plugin no painel de administração.
 */
function cs_create_admin_menu() {
    $icon_svg_path = plugin_dir_path( __FILE__ ) . 'assets/images/menu-icon.svg';
    $icon_base64 = 'data:image/svg+xml;base64,' . base64_encode( file_get_contents( $icon_svg_path ) );
    
    add_menu_page(
        __( 'Canivete Suíço', 'canivete-suico' ),
        __( 'Canivete Suíço', 'canivete-suico' ),
        'manage_options',
        'canivete-suico', // O slug da página
        'cs_render_admin_page',
        $icon_base64,
        6
    );
}
add_action( 'admin_menu', 'cs_create_admin_menu' );

/**
 * Renderiza o conteúdo da página de configurações.
 */
function cs_render_admin_page() {
    // Carrega o CSS customizado apenas na página do plugin
    echo '<link rel="stylesheet" href="' . plugins_url( 'assets/css/admin-settings.css', __FILE__ ) . '" type="text/css" media="all" />';
    ?>
    <script>
    // Dark mode toggle
    document.addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('cs-dark-toggle');
        if(btn) {
            btn.onclick = function() {
                document.body.classList.toggle('cs-dark-mode');
                localStorage.setItem('cs-dark-mode', document.body.classList.contains('cs-dark-mode'));
            };
            // Carrega preferência
            if(localStorage.getItem('cs-dark-mode') === 'true') {
                document.body.classList.add('cs-dark-mode');
            }
        }
    });
    </script>
    <div class="cs-admin-header">
        <img src="<?php echo plugins_url( 'assets/images/menu-icon.svg', __FILE__ ); ?>" alt="Canivete Suíço" />
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <button id="cs-dark-toggle" class="cs-dark-toggle" title="Alternar modo escuro" type="button">🌙</button>
    </div>
    <form action="options.php" method="post">
    <?php settings_fields( 'canivete_suico_options_group' ); ?>
    <div class="cs-admin-main">
        <div class="cs-admin-col">
            <div class="cs-admin-card">
                <?php do_settings_sections('canivete-suico'); ?>
            </div>
        </div>
    </div>
    <div style="text-align:center; color:#888; margin-top:32px; font-size:0.95em;">
        <hr class="cs-section-divider" />
        <span>Canivete Suíço &copy; <?php echo date('Y'); ?> — por <a href="https://eduvetter.com" target="_blank">Eduardo Vetter</a></span>
    </div>
    <?php submit_button( __( 'Salvar Alterações', 'canivete-suico' ), 'button-primary' ); ?>
    </form>
    <?php
}

/**
 * Função para carregar o Text Domain para traduções.
 */
function cs_load_textdomain() {
    load_plugin_textdomain( 'canivete-suico', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action( 'plugins_loaded', 'cs_load_textdomain' );