<?php
/**
 * Logotipo personalizado por página/post.
 */
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'login_head', function() {
    if ( is_user_logged_in() ) return;
    $logo_id = get_post_meta( url_to_postid( $_SERVER['HTTP_REFERER'] ?? '' ), '_cs_custom_login_logo', true );
    if ( $logo_id ) {
        $logo_url = wp_get_attachment_image_url( $logo_id, 'full' );
        if ( $logo_url ) {
            echo '<style>#login h1 a, .login h1 a { background-image: url(' . esc_url( $logo_url ) . ') !important; background-size: contain; width: 100%; height: 100px; }</style>';
        }
    }
});

// Adiciona meta box para selecionar logotipo personalizado
add_action( 'add_meta_boxes', function() {
    add_meta_box( 'cs_custom_login_logo', __( 'Logotipo Personalizado de Login', 'canivete-suico' ), function( $post ) {
        $logo_id = get_post_meta( $post->ID, '_cs_custom_login_logo', true );
        echo wp_get_attachment_image( $logo_id, 'medium' );
        echo '<input type="hidden" id="cs_custom_login_logo" name="cs_custom_login_logo" value="' . esc_attr( $logo_id ) . '" />';
        echo '<button type="button" class="button" id="cs_select_logo">' . __( 'Selecionar Logotipo', 'canivete-suico' ) . '</button>';
        ?>
        <script>
        jQuery(document).ready(function($){
            var frame;
            $('#cs_select_logo').on('click', function(e){
                e.preventDefault();
                if (frame) { frame.open(); return; }
                frame = wp.media({ title: 'Escolher Logotipo', button: { text: 'Usar este logotipo' }, multiple: false });
                frame.on('select', function(){
                    var attachment = frame.state().get('selection').first().toJSON();
                    $('#cs_custom_login_logo').val(attachment.id);
                    $('.inside').find('img').attr('src', attachment.url);
                });
                frame.open();
            });
        });
        </script>
        <?php
    }, ['post', 'page'], 'side' );
});

add_action( 'save_post', function( $post_id ) {
    if ( isset( $_POST['cs_custom_login_logo'] ) ) {
        update_post_meta( $post_id, '_cs_custom_login_logo', intval( $_POST['cs_custom_login_logo'] ) );
    }
});
