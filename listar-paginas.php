<?php
/**
 * Script para listar todas las páginas en WordPress
 */

// Definir constantes necesarias para WordPress
define('ABSPATH', '/var/www/html/');
define('WP_DEBUG', true);

// Incluir WordPress directamente
require_once('/var/www/html/wp-load.php');

// Obtener todas las páginas
$pages = get_pages();

echo "<h1>Listado de páginas en WordPress</h1>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>ID</th><th>Título</th><th>Slug</th><th>Estado</th><th>URL</th></tr>";

foreach ($pages as $page) {
    echo "<tr>";
    echo "<td>" . $page->ID . "</td>";
    echo "<td>" . $page->post_title . "</td>";
    echo "<td>" . $page->post_name . "</td>";
    echo "<td>" . $page->post_status . "</td>";
    echo "<td>" . get_permalink($page->ID) . "</td>";
    echo "</tr>";
}

echo "</table>";

// Mostrar la configuración de la página de inicio
echo "<h2>Configuración de página de inicio</h2>";
echo "<p>ID de página de inicio: " . get_option('page_on_front') . "</p>";
echo "<p>Mostrar en portada: " . get_option('show_on_front') . "</p>";

// Mostrar información sobre los permalinks
echo "<h2>Configuración de permalinks</h2>";
$permalink_structure = get_option('permalink_structure');
echo "<p>Estructura de permalinks: " . ($permalink_structure ? $permalink_structure : "(ninguna)") . "</p>"; 