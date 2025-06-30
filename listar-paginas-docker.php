<?php
/**
 * Script para listar todas las páginas existentes en WordPress
 */

// Definir constantes necesarias para WordPress
define('ABSPATH', '/var/www/html/');

// Incluir WordPress
require_once(ABSPATH . 'wp-load.php');

echo "Listado de páginas en WordPress:\n";
echo "---------------------------------\n";

$pages = get_pages();

if (empty($pages)) {
    echo "No se encontraron páginas.\n";
} else {
    foreach ($pages as $page) {
        echo $page->post_title . "\n";
        echo "URL: " . get_permalink($page->ID) . "\n";
        echo "ID: " . $page->ID . "\n";
        echo "Slug: " . $page->post_name . "\n";
        echo "Status: " . $page->post_status . "\n";
        echo "---------------------------------\n";
    }
}

// Verificar la página de inicio
$front_page_id = get_option('page_on_front');
$show_on_front = get_option('show_on_front');

echo "\nConfiguración de la página de inicio:\n";
echo "Show on front: " . $show_on_front . "\n";
echo "Front page ID: " . $front_page_id . "\n";

if ($front_page_id) {
    $front_page = get_post($front_page_id);
    if ($front_page) {
        echo "Front page title: " . $front_page->post_title . "\n";
        echo "Front page slug: " . $front_page->post_name . "\n";
    }
}

// Verificar los menús
echo "\nMenús disponibles:\n";
$menus = wp_get_nav_menus();
if (empty($menus)) {
    echo "No se encontraron menús.\n";
} else {
    foreach ($menus as $menu) {
        echo "Menu: " . $menu->name . " (ID: " . $menu->term_id . ")\n";
        $menu_items = wp_get_nav_menu_items($menu->term_id);
        if ($menu_items) {
            echo "  Items:\n";
            foreach ($menu_items as $item) {
                echo "  - " . $item->title . " -> " . $item->url . "\n";
            }
        } else {
            echo "  (Sin elementos)\n";
        }
    }
}

// Verificar la asignación de menús
$locations = get_nav_menu_locations();
echo "\nAsignación de menús:\n";
if (empty($locations)) {
    echo "No hay menús asignados a ubicaciones.\n";
} else {
    foreach ($locations as $location => $menu_id) {
        echo "Ubicación: " . $location . " -> Menu ID: " . $menu_id . "\n";
        $menu = wp_get_nav_menu_object($menu_id);
        if ($menu) {
            echo "  Nombre del menú asignado: " . $menu->name . "\n";
        }
    }
}

echo "\nTema actual: " . wp_get_theme()->get('Name') . "\n";
echo "URL del tema: " . get_template_directory_uri() . "\n";
echo "Directorio del tema: " . get_template_directory() . "\n"; 