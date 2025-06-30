<?php
/**
 * Script para configurar la estructura de permalinks en WordPress
 */

// Definir constantes necesarias para WordPress
define('ABSPATH', '/var/www/html/');
define('WP_DEBUG', true);

// Incluir WordPress directamente
require_once('/var/www/html/wp-load.php');

// Configurar la estructura de permalinks a /%postname%/
update_option('permalink_structure', '/%postname%/');

// Actualizar las reglas de reescritura
flush_rewrite_rules(true);

echo "<h1>Configuración de permalinks</h1>";
echo "<p>Se ha configurado la estructura de permalinks a: /%postname%/</p>";
echo "<p>Las reglas de reescritura se han actualizado.</p>";

// Mostrar la nueva configuración
$permalink_structure = get_option('permalink_structure');
echo "<p>Estructura actual de permalinks: " . $permalink_structure . "</p>"; 