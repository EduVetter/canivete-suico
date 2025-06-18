<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

if ( class_exists( 'Elementor\Widget_Base' ) && class_exists( 'Elementor\Controls_Manager' ) ) {
    class CS_Elementor_Visit_Counter_Widget extends Widget_Base {
        public function get_name() {
            return 'cs_visit_counter';
        }
        public function get_title() {
            return __( 'Contador de Visitas', 'canivete-suico' );
        }
        public function get_icon() {
            return 'eicon-counter-circle';
        }
        public function get_categories() {
            return [ 'general' ];
        }
        protected function register_controls() {
            // Aba Conteúdo
            $this->start_controls_section(
                'section_content',
                [ 'label' => __( 'Conteúdo', 'canivete-suico' ) ]
            );
            $this->add_control(
                'title', [
                    'label' => __( 'Título', 'canivete-suico' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Contador de Visitas', 'canivete-suico' ),
                ]
            );
            $this->add_control(
                'visits_label', [
                    'label' => __( 'Texto antes do número de visitas', 'canivete-suico' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Número total de visitas:', 'canivete-suico' ),
                ]
            );
            $this->add_control(
                'active_label', [
                    'label' => __( 'Texto antes dos usuários ativos', 'canivete-suico' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => __( 'Usuários ativos:', 'canivete-suico' ),
                ]
            );
            $this->add_control(
                'visits_count', [
                    'label' => __( 'Número total de visitas (editar aqui atualiza no banco)', 'canivete-suico' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => get_option('cs_visit_counter', 0),
                    'min' => 0,
                    'step' => 1,
                ]
            );
            $this->end_controls_section();

            // Aba Estilo - Título
            $this->start_controls_section(
                'section_style_title',
                [ 'label' => __( 'Título', 'canivete-suico' ), 'tab' => Controls_Manager::TAB_STYLE ]
            );
            $this->add_control(
                'title_color', [
                    'label' => __( 'Cor do Título', 'canivete-suico' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [ '{{WRAPPER}} .cs-visit-counter-title' => 'color: {{VALUE}};' ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(), [
                    'name' => 'title_typography',
                    'selector' => '{{WRAPPER}} .cs-visit-counter-title',
                ]
            );
            $this->end_controls_section();

            // Aba Estilo - Texto Visitas
            $this->start_controls_section(
                'section_style_visits_label',
                [ 'label' => __( 'Texto de Visitas', 'canivete-suico' ), 'tab' => Controls_Manager::TAB_STYLE ]
            );
            $this->add_control(
                'visits_label_color', [
                    'label' => __( 'Cor do Texto', 'canivete-suico' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [ '{{WRAPPER}} .cs-visit-counter-visits-label' => 'color: {{VALUE}};' ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(), [
                    'name' => 'visits_label_typography',
                    'selector' => '{{WRAPPER}} .cs-visit-counter-visits-label',
                ]
            );
            $this->end_controls_section();

            // Aba Estilo - Número
            $this->start_controls_section(
                'section_style_number',
                [ 'label' => __( 'Número de Visitas', 'canivete-suico' ), 'tab' => Controls_Manager::TAB_STYLE ]
            );
            $this->add_control(
                'number_color', [
                    'label' => __( 'Cor do Número', 'canivete-suico' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [ '{{WRAPPER}} .cs-visit-counter' => 'color: {{VALUE}};' ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(), [
                    'name' => 'number_typography',
                    'selector' => '{{WRAPPER}} .cs-visit-counter',
                ]
            );
            $this->end_controls_section();

            // Aba Estilo - Texto Usuários Ativos
            $this->start_controls_section(
                'section_style_active_label',
                [ 'label' => __( 'Texto de Usuários Ativos', 'canivete-suico' ), 'tab' => Controls_Manager::TAB_STYLE ]
            );
            $this->add_control(
                'active_label_color', [
                    'label' => __( 'Cor do Texto', 'canivete-suico' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [ '{{WRAPPER}} .cs-visit-counter-active-label' => 'color: {{VALUE}};' ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(), [
                    'name' => 'active_label_typography',
                    'selector' => '{{WRAPPER}} .cs-visit-counter-active-label',
                ]
            );
            $this->end_controls_section();

            // Aba Estilo - Número Usuários Ativos
            $this->start_controls_section(
                'section_style_active_number',
                [ 'label' => __( 'Número de Usuários Ativos', 'canivete-suico' ), 'tab' => Controls_Manager::TAB_STYLE ]
            );
            $this->add_control(
                'active_number_color', [
                    'label' => __( 'Cor do Número', 'canivete-suico' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [ '{{WRAPPER}} .cs-visit-counter-active-number' => 'color: {{VALUE}};' ],
                ]
            );
            $this->add_group_control(
                \Elementor\Group_Control_Typography::get_type(), [
                    'name' => 'active_number_typography',
                    'selector' => '{{WRAPPER}} .cs-visit-counter-active-number',
                ]
            );
            $this->end_controls_section();

            // Aba Estilo Geral
            $this->start_controls_section(
                'section_style_box',
                [ 'label' => __( 'Box Geral', 'canivete-suico' ), 'tab' => Controls_Manager::TAB_STYLE ]
            );
            $this->add_responsive_control(
                'alignment', [
                    'label' => __( 'Alinhamento', 'canivete-suico' ),
                    'type' => Controls_Manager::CHOOSE,
                    'options' => [
                        'left' => [ 'title' => __( 'Esquerda', 'canivete-suico' ), 'icon' => 'eicon-text-align-left' ],
                        'center' => [ 'title' => __( 'Centro', 'canivete-suico' ), 'icon' => 'eicon-text-align-center' ],
                        'right' => [ 'title' => __( 'Direita', 'canivete-suico' ), 'icon' => 'eicon-text-align-right' ],
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .cs-visit-counter-box' => 'text-align: {{VALUE}};',
                    ],
                    'default' => 'center',
                ]
            );
            $this->add_responsive_control(
                'margin', [
                    'label' => __( 'Margem', 'canivete-suico' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .cs-visit-counter-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->add_responsive_control(
                'padding', [
                    'label' => __( 'Preenchimento', 'canivete-suico' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'selectors' => [
                        '{{WRAPPER}} .cs-visit-counter-box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
            $this->end_controls_section();
        }
        protected function render() {
            $settings = $this->get_settings_for_display();
            // Se o valor foi alterado no Elementor, salva no banco
            if ( isset($settings['visits_count']) && is_numeric($settings['visits_count']) ) {
                update_option('cs_visit_counter', (int)$settings['visits_count']);
            }
            $count = (int) get_option( 'cs_visit_counter', 0 );
            $user_query = new \WP_User_Query( array( 'who' => 'authors', 'fields' => 'ID' ) );
            $active = is_array($user_query->get_results()) ? count($user_query->get_results()) : 0;
            echo '<div class="cs-visit-counter-box">';
            if ( !empty($settings['title']) ) {
                echo '<div class="cs-visit-counter-title">' . esc_html($settings['title']) . '</div>';
            }
            echo '<div><span class="cs-visit-counter-visits-label">' . esc_html($settings['visits_label']) . '</span> <span class="cs-visit-counter">' . esc_html($count) . '</span></div>';
            echo '<div><span class="cs-visit-counter-active-label">' . esc_html($settings['active_label']) . '</span> <span class="cs-visit-counter-active-number"><strong>' . esc_html($active) . '</strong></span></div>';
            echo '</div>';
        }
    }
}
