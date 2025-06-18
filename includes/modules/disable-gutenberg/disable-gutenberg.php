<?php
/**
 * Módulo para Desativar o Editor de Blocos (Gutenberg)
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// O filtro 'use_block_editor_for_post' é o principal para desativar o Gutenberg.
// Retornar __return_false é um atalho do WordPress para 'return false;'.
add_filter('use_block_editor_for_post', '__return_false', 10);

// Para garantir compatibilidade com versões mais antigas e diferentes contextos,
// também desativamos para tipos de post específicos.
add_filter('use_block_editor_for_post_type', '__return_false', 10);