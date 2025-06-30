<?php
/**
 * Script para crear automáticamente el menú de navegación para clientes
 */

// Incluir WordPress
require_once(dirname(__FILE__) . '/n8n-project/wordpress/broker-integration/../../../wp-load.php');

// Verificar permisos
if (!current_user_can('administrator')) {
    die('Acceso denegado. Debes ser administrador para ejecutar este script.');
}

// Nombre del menú
$menu_name = 'Menú de Cliente';
$menu_location = 'primary';

// Verificar si el menú ya existe
$menu_exists = wp_get_nav_menu_object($menu_name);

// Si no existe, crearlo
if (!$menu_exists) {
    $menu_id = wp_create_nav_menu($menu_name);
} else {
    $menu_id = $menu_exists->term_id;
    
    // Borrar elementos existentes
    $items = wp_get_nav_menu_items($menu_id);
    foreach ($items as $item) {
        wp_delete_post($item->ID, true);
    }
}

// Páginas para añadir al menú
$pages = array(
    'Portal de Cliente' => 'portal-cliente',
    'Subir Documentos' => 'subir-documentos',
    'Seguimiento de Estado' => 'seguimiento-estado',
    'Mensajes' => 'mensajes',
    'Formularios' => 'formularios'
);

// Añadir las páginas al menú
foreach ($pages as $title => $slug) {
    $page = get_page_by_path($slug);
    
    if ($page) {
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => $title,
            'menu-item-object' => 'page',
            'menu-item-object-id' => $page->ID,
            'menu-item-type' => 'post_type',
            'menu-item-status' => 'publish'
        ));
    }
}

// Asignar el menú a la ubicación primary
$locations = get_theme_mod('nav_menu_locations');
$locations[$menu_location] = $menu_id;
set_theme_mod('nav_menu_locations', $locations);

echo "<h1>Creación de Menú para Clientes</h1>";
echo "<p>El menú '$menu_name' ha sido creado y configurado.</p>";
echo "<p><a href='". admin_url('nav-menus.php') ."'>Ver menús</a></p>"; 