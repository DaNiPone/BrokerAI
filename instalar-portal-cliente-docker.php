<?php
/**
 * Script para configurar automáticamente todo el portal de clientes en Docker
 */

// Definir constantes necesarias para WordPress
define('ABSPATH', '/var/www/html/');
define('WP_DEBUG', true);

// Incluir WordPress directamente
require_once('/var/www/html/wp-load.php');

// Verificar si ACF está activo
if (!class_exists('ACF')) {
    die('<h1>Error: Advanced Custom Fields no está activado</h1><p>Por favor, activa el plugin ACF antes de continuar.</p>');
}

echo "<h1>Configuración del Portal de Clientes</h1>";

// 1. Insertar estilos CSS como CSS adicional en el tema
$css_content = file_get_contents(__DIR__ . '/estilos-cliente.css');
if ($css_content) {
    $current_css = get_option('theme_mods_twentytwentythree');
    if (!is_array($current_css)) {
        $current_css = array();
    }
    $current_css['custom_css_post_id'] = $css_content;
    update_option('theme_mods_twentytwentythree', $current_css);
    echo "<p>✅ Estilos CSS registrados correctamente.</p>";
} else {
    echo "<p>❌ No se pudo leer el archivo CSS.</p>";
}

// 2. Crear páginas
echo "<h2>Creando páginas...</h2>";

// Páginas a crear con sus shortcodes
$pages = array(
    'Portal de Cliente' => array(
        'content' => '[broker_client_dashboard]',
        'slug' => 'portal-cliente'
    ),
    'Subir Documentos' => array(
        'content' => '[broker_document_upload]',
        'slug' => 'subir-documentos'
    ),
    'Seguimiento de Estado' => array(
        'content' => '[client_status_tracker]',
        'slug' => 'seguimiento-estado'
    ),
    'Mensajes' => array(
        'content' => '[client_message_center]',
        'slug' => 'mensajes'
    ),
    'Formularios' => array(
        'content' => '[client_forms]',
        'slug' => 'formularios'
    ),
    'Registro de Cliente' => array(
        'content' => '[broker_register_form]',
        'slug' => 'registro-cliente'
    )
);

// Crear las páginas
$created_pages = array();
$updated_pages = array();

foreach ($pages as $title => $data) {
    // Verificar si la página ya existe
    $existing_page = get_page_by_path($data['slug']);

    if ($existing_page) {
        // Actualizar la página existente
        $page_data = array(
            'ID' => $existing_page->ID,
            'post_content' => $data['content'],
        );
        wp_update_post($page_data);
        $updated_pages[] = $title;
    } else {
        // Crear una nueva página
        $page_data = array(
            'post_title' => $title,
            'post_content' => $data['content'],
            'post_status' => 'publish',
            'post_type' => 'page',
            'post_name' => $data['slug']
        );
        $page_id = wp_insert_post($page_data);
        
        if ($page_id) {
            $created_pages[] = $title;
        }
    }
}

// Mostrar resultados
if (!empty($created_pages)) {
    echo "<h3>Páginas creadas:</h3>";
    echo "<ul>";
    foreach ($created_pages as $page) {
        echo "<li>$page</li>";
    }
    echo "</ul>";
}

if (!empty($updated_pages)) {
    echo "<h3>Páginas actualizadas:</h3>";
    echo "<ul>";
    foreach ($updated_pages as $page) {
        echo "<li>$page</li>";
    }
    echo "</ul>";
}

// 3. Crear menú
echo "<h2>Configurando menú...</h2>";

// Nombre del menú
$menu_name = 'Menú de Cliente';
$menu_location = 'primary';

// Verificar si el menú ya existe
$menu_exists = wp_get_nav_menu_object($menu_name);

// Si no existe, crearlo
if (!$menu_exists) {
    $menu_id = wp_create_nav_menu($menu_name);
    echo "<p>✅ Menú '$menu_name' creado.</p>";
} else {
    $menu_id = $menu_exists->term_id;
    
    // Borrar elementos existentes
    $items = wp_get_nav_menu_items($menu_id);
    foreach ($items as $item) {
        wp_delete_post($item->ID, true);
    }
    echo "<p>✅ Menú '$menu_name' existente limpiado.</p>";
}

// Páginas para añadir al menú
$menu_pages = array(
    'Portal de Cliente' => 'portal-cliente',
    'Subir Documentos' => 'subir-documentos',
    'Seguimiento de Estado' => 'seguimiento-estado',
    'Mensajes' => 'mensajes',
    'Formularios' => 'formularios'
);

// Añadir las páginas al menú
foreach ($menu_pages as $title => $slug) {
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
if (!is_array($locations)) {
    $locations = array();
}
$locations[$menu_location] = $menu_id;
set_theme_mod('nav_menu_locations', $locations);
echo "<p>✅ Menú asignado a la ubicación principal.</p>";

// 4. Configurar la página de inicio para clientes
$home_page = get_page_by_path('portal-cliente');
if ($home_page) {
    update_option('page_on_front', $home_page->ID);
    update_option('show_on_front', 'page');
    echo "<p>✅ Página de inicio configurada como 'Portal de Cliente'.</p>";
} else {
    echo "<p>❌ No se pudo encontrar la página 'Portal de Cliente' para configurarla como página de inicio.</p>";
}

// 5. Crear roles y permisos para clientes
if (!get_role('broker_client')) {
    add_role(
        'broker_client',
        'Cliente BrokerAI',
        array(
            'read' => true,
            'upload_files' => true,
        )
    );
    echo "<p>✅ Rol de 'Cliente BrokerAI' creado correctamente.</p>";
} else {
    echo "<p>ℹ️ El rol 'Cliente BrokerAI' ya existe.</p>";
}

// 6. Crear páginas de redirección para login/registro
$login_redirect = get_option('broker_login_redirect');
if (empty($login_redirect)) {
    $client_dashboard = get_page_by_path('portal-cliente');
    if ($client_dashboard) {
        update_option('broker_login_redirect', $client_dashboard->ID);
        echo "<p>✅ Redirección después del login configurada.</p>";
    }
}

echo "<h2>Configuración completada</h2>";
echo "<p>El portal de clientes ha sido configurado correctamente.</p>";
echo "<p><a href='http://localhost:8888/portal-cliente/' class='button button-primary'>Ver Portal de Cliente</a></p>";
?> 