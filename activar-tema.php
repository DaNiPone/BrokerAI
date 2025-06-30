<?php
/**
 * Script para activar el tema hijo y los plugins necesarios
 */

// Definir constantes necesarias para WordPress
define('ABSPATH', '/var/www/html/');
define('WP_DEBUG', true);

// Incluir WordPress directamente
require_once('/var/www/html/wp-load.php');

// Activar Font Awesome
wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');

// Activar el tema hijo
$theme = 'broker-theme-child';
switch_theme($theme);
echo "<p>✅ Tema 'BrokerAI Child Theme' activado.</p>";

// Activar el plugin de shortcodes
$plugin_path = 'broker-shortcodes.php';
$current = get_option('active_plugins', array());
$plugin = plugin_basename(trim($plugin_path));

if (!in_array($plugin, $current)) {
    $current[] = $plugin;
    sort($current);
    update_option('active_plugins', $current);
    echo "<p>✅ Plugin 'BrokerAI Shortcodes' activado.</p>";
}

// Configurar la estructura de permalinks a /%postname%/
update_option('permalink_structure', '/%postname%/');

// Actualizar las reglas de reescritura
flush_rewrite_rules(true);
echo "<p>✅ Estructura de permalinks actualizada.</p>";

echo "<h2>Configuración completada</h2>";
echo "<p>El tema hijo y los plugins han sido activados correctamente.</p>";
echo "<p><a href='http://localhost:8888/' class='button button-primary'>Ver sitio</a></p>"; 