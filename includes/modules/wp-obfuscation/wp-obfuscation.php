<?php
/**
 * Módulo para Ocultar WordPress (Segurança por Obscuridade)
 * - Remove a tag de versão do WordPress
 */
if ( ! defined( 'ABSPATH' ) ) exit;

// Remove a tag <meta name="generator" content="WordPress X.X.X" /> do <head>
// Esta é uma das "pistas" mais óbvias que um site usa WordPress.
remove_action('wp_head', 'wp_generator');

// NO FUTURO: Poderemos adicionar mais funções aqui para remover outras "pistas",
// como a versão de scripts e estilos, links RSD, etc.
// Por enquanto, esta é a mais importante e segura de se remover.