<?php
/**
 * Módulo para adicionar suporte seguro a SVG
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class CS_SVG_Support {

    public function __construct() {
        add_filter( 'upload_mimes', array( $this, 'add_svg_mime_type' ) );
        add_filter( 'wp_check_filetype_and_ext', array( $this, 'check_svg_filetype' ), 10, 4 );
        add_filter( 'wp_prepare_attachment_for_js', array( $this, 'show_svg_in_media_library' ), 10, 3 );
        add_action( 'admin_head', array( $this, 'svg_media_library_css' ) );
        add_filter( 'wp_handle_upload_prefilter', array( $this, 'sanitize_svg' ) );
    }

    // 1. Habilita o upload
    public function add_svg_mime_type( $mimes ) {
        $mimes['svg'] = 'image/svg+xml';
        return $mimes;
    }

    // Garante que o WordPress identifique corretamente o SVG
    public function check_svg_filetype( $data, $file, $filename, $mimes ) {
        if ( pathinfo( $filename, PATHINFO_EXTENSION ) === 'svg' ) {
            $data['ext']  = 'svg';
            $data['type'] = 'image/svg+xml';
        }
        return $data;
    }

    // 2. Sanitiza o ficheiro no momento do upload
    public function sanitize_svg( $file ) {
        if ( $file['type'] === 'image/svg+xml' ) {
            $content = file_get_contents( $file['tmp_name'] );

            // Remove tags <script> e o seu conteúdo
            $content = preg_replace( '/<script\b[^>]*>(.*?)<\/script>/is', '', $content );

            // Remove atributos que começam com "on" (ex: onclick, onmouseover)
            $content = preg_replace( '/\s+on\w+\s*=\s*".*?"/i', '', $content );
            $content = preg_replace( '/\s+on\w+\s*=\s*\'.*?\'/i', '', $content );
            
            // Salva o conteúdo limpo de volta no ficheiro temporário
            file_put_contents( $file['tmp_name'], $content );
        }
        return $file;
    }

    // 3. Mostra a pré-visualização na Biblioteca de Mídia
    public function show_svg_in_media_library( $response, $attachment, $meta ) {
        if ( $response['type'] === 'image' && $response['subtype'] === 'svg+xml' ) {
            $response['sizes'] = array(
                'full' => array( 'url' => $response['url'] ),
                'thumbnail' => array( 'url' => $response['url'] ),
            );
        }
        return $response;
    }
    
    // Adiciona um CSS para garantir que a pré-visualização não fique desproporcional
    public function svg_media_library_css() {
        echo '<style>
            .media-modal-content .attachment-details .thumbnail img[src$=".svg"],
            .media-library-grid .attachment-preview img[src$=".svg"] {
                width: 100% !important;
                height: auto !important;
            }
        </style>';
    }
}
new CS_SVG_Support();