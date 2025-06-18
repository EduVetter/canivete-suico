<?php
// Ficheiro: includes/core/class-settings-manager.php

if ( ! defined( 'ABSPATH' ) ) exit;

class CS_Settings_Manager {

    private $options;

    public function __construct() {
        $this->options = get_option( 'canivete_suico_options' );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }

    public function register_settings() {
        register_setting('canivete_suico_options_group', 'canivete_suico_options', array( $this, 'sanitize_options' ));
        
        // SECÇÕES
        add_settings_section('canivete_suico_modules_section', __( 'Módulos Gerais', 'canivete-suico' ), null, 'canivete-suico');
        add_settings_section('canivete_suico_admin_tweaks_section', __( 'Ajustes do Painel de Admin', 'canivete-suico' ), null, 'canivete-suico');
        add_settings_section('canivete_suico_content_tweaks_section', __( 'Ajustes de Conteúdo', 'canivete-suico' ), null, 'canivete-suico');
        add_settings_section('canivete_suico_whitelabel_section', __( 'Personalização (White Label)', 'canivete-suico' ), null, 'canivete-suico');
        add_settings_section('canivete_suico_protection_section', __( 'Proteção de Conteúdo', 'canivete-suico' ), null, 'canivete-suico');
        add_settings_section('canivete_suico_security_section', __( 'Segurança e Otimização', 'canivete-suico' ), null, 'canivete-suico');

        // CAMPOS
        add_settings_field('enable_svg_upload', __( 'Suporte a SVG', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_modules_section', ['id' => 'enable_svg_upload', 'label' => __( 'Permite o upload seguro de ficheiros SVG na Biblioteca de Mídia.', 'canivete-suico' )]);
        add_settings_field('maintenance_mode_module', __( 'Modo Manutenção', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_modules_section', ['id' => 'maintenance_mode_module', 'label' => __( 'Ativar modo manutenção.', 'canivete-suico' )]);
        add_settings_field('disable_gutenberg', __( 'Editor Clássico', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_admin_tweaks_section', ['id' => 'disable_gutenberg', 'label' => __( 'Desativa o editor de blocos (Gutenberg) e restaura o editor clássico.', 'canivete-suico' )]);
        add_settings_field('enable_classic_widgets', __( 'Widgets Clássicos', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_admin_tweaks_section', ['id' => 'enable_classic_widgets', 'label' => __( 'Desativa a interface de blocos e restaura a gestão clássica de widgets.', 'canivete-suico' )]);
        add_settings_field('disable_update_emails', __( 'Desativar E-mails de Atualização', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_admin_tweaks_section', ['id' => 'disable_update_emails', 'label' => __( 'Impede o envio de e-mails de notificação após atualizações automáticas.', 'canivete-suico' )]);
        add_settings_field('enable_duplicate_post', __( 'Duplicar Posts/Páginas', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_admin_tweaks_section', ['id' => 'enable_duplicate_post', 'label' => __( 'Adiciona um link "Duplicar" na lista de posts e páginas.', 'canivete-suico' )]);
        add_settings_field('show_last_updated_date', __( 'Exibir Data de Atualização', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_content_tweaks_section', ['id' => 'show_last_updated_date', 'label' => __( 'Mostra a data da última modificação no início dos posts.', 'canivete-suico' )]);
        add_settings_field('use_site_logo_for_login', __( 'Logotipo na Tela de Login', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_whitelabel_section', ['id' => 'use_site_logo_for_login', 'label' => __( 'Usa o "Logotipo do Site" definido no Personalizador.', 'canivete-suico' )]);
        add_settings_field('use_site_icon_for_admin', __( 'Ícone na Barra de Admin', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_whitelabel_section', ['id' => 'use_site_icon_for_admin', 'label' => __( 'Usa o "Ícone do Site" (Favicon) definido no Personalizador como logo na barra de admin.', 'canivete-suico' )]);
        add_settings_field('custom_admin_footer', __( 'Texto do Rodapé do Painel', 'canivete-suico' ), array( $this, 'render_textarea_field' ), 'canivete-suico', 'canivete_suico_whitelabel_section', ['id' => 'custom_admin_footer', 'label' => __( 'Personalize o texto "Obrigado por criar com o WordPress". Pode usar HTML.', 'canivete-suico' )]);
        add_settings_field('disable_login_lang_switcher', __( 'Desativar Seletor de Idioma', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_whitelabel_section', ['id' => 'disable_login_lang_switcher', 'label' => __( 'Oculta o seletor de idioma na tela de login.', 'canivete-suico' )]);
        add_settings_field('disable_right_click', __( 'Desativar Clique Direito', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_protection_section', ['id' => 'disable_right_click', 'label' => __( 'Impede o uso do menu de contexto do botão direito.', 'canivete-suico' )]);
        add_settings_field('disable_right_click_message', '', array( $this, 'render_text_field' ), 'canivete-suico', 'canivete_suico_protection_section', ['id' => 'disable_right_click_message', 'placeholder' => __( 'Mensagem de alerta (opcional)', 'canivete-suico' )]);
        add_settings_field('disable_text_selection', __( 'Desativar Seleção de Texto', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_protection_section', ['id' => 'disable_text_selection', 'label' => __( 'Impede que o conteúdo do site seja selecionado e copiado.', 'canivete-suico' )]);
        add_settings_field('disable_copy_message', '', array( $this, 'render_text_field' ), 'canivete-suico', 'canivete_suico_protection_section', ['id' => 'disable_copy_message', 'placeholder' => __( 'Mensagem de alerta (opcional)', 'canivete-suico' )]);
        add_settings_field('hide_wp_footprints', __( 'Ocultar WordPress', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_security_section', ['id' => 'hide_wp_footprints', 'label' => __( 'Remove "pistas" comuns de que o site usa WordPress.', 'canivete-suico' )]);
        add_settings_field('disable_rss_feeds', __( 'Desativar Feeds RSS', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_security_section', ['id' => 'disable_rss_feeds', 'label' => __( 'Impede o acesso aos feeds de conteúdo do site (ex: /feed).', 'canivete-suico' )]);
        add_settings_field('disable_rest_api', __( 'Desativar REST API', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_security_section', ['id' => 'disable_rest_api', 'label' => __( 'Bloqueia o acesso público à API de dados do WordPress.', 'canivete-suico' )]);
        add_settings_field('disable_comments', __( 'Desativar Comentários', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_security_section', ['id' => 'disable_comments', 'label' => __( 'Desativa globalmente os comentários, remove o menu e os formulários.', 'canivete-suico' )]);
        // Novos módulos expandidos
        add_settings_field('meta_description_limit', __( 'Limitar Meta Descrição', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_content_tweaks_section', ['id' => 'meta_description_limit', 'label' => __( 'Limita a meta descrição (resumo) a 160 caracteres.', 'canivete-suico' )]);
        add_settings_field('external_links_newtab', __( 'Links Externos em Nova Guia', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_content_tweaks_section', ['id' => 'external_links_newtab', 'label' => __( 'Abre automaticamente todos os links externos em nova guia.', 'canivete-suico' )]);
        add_settings_field('reading_time', __( 'Tempo de Leitura', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_content_tweaks_section', ['id' => 'reading_time', 'label' => __( 'Exibe o tempo estimado de leitura antes do conteúdo.', 'canivete-suico' )]);
        add_settings_field('title_limit', __( 'Limitar Títulos', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_content_tweaks_section', ['id' => 'title_limit', 'label' => __( 'Limita títulos a 60 caracteres.', 'canivete-suico' )]);
        add_settings_field('last_login_column', __( 'Coluna Último Login', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_admin_tweaks_section', ['id' => 'last_login_column', 'label' => __( 'Adiciona uma coluna com o último login dos usuários.', 'canivete-suico' )]);
        add_settings_field('admin_menu_order', __( 'Reordenar Menu Admin', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_admin_tweaks_section', ['id' => 'admin_menu_order', 'label' => __( 'Permite reordenar os itens do menu de administração.', 'canivete-suico' )]);
        add_settings_field('custom_login_logo', __( 'Logotipo Personalizado por Página', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_whitelabel_section', ['id' => 'custom_login_logo', 'label' => __( 'Permite definir um logotipo diferente para cada página/post.', 'canivete-suico' )]);
        add_settings_field('custom_wp_admin_url', __( 'Alterar link wp-admin', 'canivete-suico' ), array( $this, 'render_text_field' ), 'canivete-suico', 'canivete_suico_security_section', ['id' => 'custom_wp_admin_url', 'label' => __( 'Defina uma nova URL para acessar o painel administrativo (ex: "meupainel").', 'canivete-suico' )]);
        add_settings_field('visit_counter', __( 'Contador de Visitas', 'canivete-suico' ), array( $this, 'render_checkbox_field' ), 'canivete-suico', 'canivete_suico_content_tweaks_section', ['id' => 'visit_counter', 'label' => __( 'Ativa o contador de visitas para exibição no site.', 'canivete-suico' )]);
        add_settings_field('cs_visit_counter', __( 'Valor do Contador de Visitas', 'canivete-suico' ), array( $this, 'render_text_field' ), 'canivete-suico', 'canivete_suico_content_tweaks_section', ['id' => 'cs_visit_counter', 'label' => __( 'Edite manualmente o número de visitas exibido no site.', 'canivete-suico' )]);
        add_settings_field('cs_visit_counter_shortcode', __( 'Como exibir o contador', 'canivete-suico' ), function() {
            echo '<strong>Dica:</strong> Para exibir o contador de visitas no seu site, utilize o <b>widget do Elementor</b> chamado <b>Contador de Visitas</b> <u>ou</u> insira o shortcode <code>[cs_visit_counter]</code> onde desejar.';
        }, 'canivete-suico', 'canivete_suico_content_tweaks_section');
    }
    
    public function sanitize_options( $input ) {
        $new_input = array();
        $checkboxes = [
            'maintenance_mode_module', 'enable_svg_upload', 'disable_gutenberg', 'enable_classic_widgets', 'disable_update_emails', 'enable_duplicate_post', 'show_last_updated_date', 'use_site_logo_for_login', 'use_site_icon_for_admin', 'disable_login_lang_switcher', 'disable_right_click', 'disable_text_selection', 'hide_wp_footprints', 'disable_rss_feeds', 'disable_rest_api', 'disable_comments',
            // Novos módulos expandidos
            'meta_description_limit', 'external_links_newtab', 'reading_time', 'title_limit', 'last_login_column', 'admin_menu_order', 'custom_login_logo', 'visit_counter'
        ];
        $text_fields = ['disable_right_click_message', 'disable_copy_message', 'custom_wp_admin_url', 'cs_visit_counter'];
        
        if ( isset( $input['custom_admin_footer'] ) ) { $new_input['custom_admin_footer'] = wp_kses_post( $input['custom_admin_footer'] ); }
        foreach ( $checkboxes as $checkbox ) { if ( isset( $input[$checkbox] ) ) { $new_input[$checkbox] = 1; } }
        foreach ( $text_fields as $field ) { if ( ! empty( $input[$field] ) ) { $new_input[$field] = sanitize_text_field( $input[$field] ); } }
        return $new_input;
    }

    public function render_checkbox_field( $args ) {
        $id = $args['id'];
        $label = $args['label'];
        $checked = isset( $this->options[$id] ) ? 'checked="checked"' : '';
        echo "<label><input type='checkbox' name='canivete_suico_options[{$id}]' value='1' {$checked} /> {$label}</label>";
    }
    
    public function render_text_field( $args ) {
        $id = $args['id'];
        $placeholder = $args['placeholder'] ?? '';
        $label = $args['label'] ?? '';
        // Corrige para buscar o valor atualizado do banco para o campo cs_visit_counter
        if ($id === 'cs_visit_counter') {
            $value = get_option('cs_visit_counter', 0);
        } else {
            $value = isset( $this->options[$id] ) ? esc_attr( $this->options[$id] ) : '';
        }
        echo "<input type='text' name='canivete_suico_options[{$id}]' value='{$value}' placeholder='{$placeholder}' class='regular-text' />";
        if ($label) { echo "<p class='description'>{$label}</p>"; }
    }
    
    public function render_textarea_field( $args ) {
        $id = $args['id'];
        $label = $args['label'] ?? '';
        $value = isset( $this->options[$id] ) ? esc_textarea( $this->options[$id] ) : '';
        echo "<textarea name='canivete_suico_options[{$id}]' rows='5' class='large-text code'>{$value}</textarea>";
        if ($label) { echo "<p class='description'>{$label}</p>"; }
    }
}