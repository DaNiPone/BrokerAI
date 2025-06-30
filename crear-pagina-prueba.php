<?php
/**
 * Script para crear una página de prueba con el shortcode
 */

// Definir constantes necesarias para WordPress
define('ABSPATH', '/var/www/html/');
define('WP_DEBUG', true);

// Incluir WordPress directamente
require_once('/var/www/html/wp-load.php');

// Crear una página de prueba
$page_id = wp_insert_post(array(
    'post_title' => 'Panel de Cliente',
    'post_content' => '[broker_client_dashboard]',
    'post_status' => 'publish',
    'post_type' => 'page',
    'comment_status' => 'closed'
));

if ($page_id) {
    echo "<p>✅ Página 'Panel de Cliente' creada con ID: $page_id</p>";
    echo "<p>Puedes verla en: <a href='" . get_permalink($page_id) . "'>" . get_permalink($page_id) . "</a></p>";
    
    // Configurar como página de inicio
    update_option('page_on_front', $page_id);
    update_option('show_on_front', 'page');
    echo "<p>✅ Página configurada como página de inicio.</p>";
} else {
    echo "<p>❌ Error al crear la página.</p>";
}

// Actualizar las reglas de reescritura
flush_rewrite_rules(true);
echo "<p>✅ Reglas de reescritura actualizadas.</p>"; 