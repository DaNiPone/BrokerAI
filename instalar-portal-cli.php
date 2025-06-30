<?php
/**
 * Script para configurar automáticamente todo el portal de clientes desde CLI
 */

// Verificar si es una ejecución desde CLI
if (php_sapi_name() !== 'cli') {
    die('Este script debe ejecutarse desde la línea de comandos.');
}

// Definir constantes necesarias para WordPress
define('ABSPATH', '/var/www/html/');

// Incluir WordPress directamente
require_once(ABSPATH . 'wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/plugin.php');
require_once(ABSPATH . 'wp-admin/includes/post.php');

echo "Configuración del Portal de Clientes\n";

// 1. Copiar el archivo CSS al tema
$css_source = dirname(__FILE__) . '/estilos-cliente.css';
$theme_dir = get_template_directory();
$css_destination = $theme_dir . '/broker-client.css';

if (file_exists($css_source)) {
    if (copy($css_source, $css_destination)) {
        echo "✅ Archivo CSS copiado al tema correctamente.\n";
        
        // Registrar el archivo CSS
        function broker_enqueue_styles() {
            wp_enqueue_style('broker-client-styles', get_template_directory_uri() . '/broker-client.css');
        }
        add_action('wp_enqueue_scripts', 'broker_enqueue_styles');
        
        echo "✅ Estilos registrados correctamente.\n";
    } else {
        echo "❌ Error al copiar el archivo CSS. Verifica los permisos.\n";
    }
} else {
    echo "❌ Archivo CSS no encontrado en la ruta: $css_source\n";
}

// 2. Crear páginas
echo "Creando páginas...\n";

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
        echo "✅ Página actualizada: $title\n";
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
            echo "✅ Página creada: $title\n";
        } else {
            echo "❌ Error al crear la página: $title\n";
        }
    }
}

// 3. Crear menú
echo "Configurando menú...\n";

// Nombre del menú
$menu_name = 'Menú de Cliente';
$menu_location = 'primary';

// Verificar si el menú ya existe
$menu_exists = wp_get_nav_menu_object($menu_name);

// Si no existe, crearlo
if (!$menu_exists) {
    $menu_id = wp_create_nav_menu($menu_name);
    echo "✅ Menú '$menu_name' creado.\n";
} else {
    $menu_id = $menu_exists->term_id;
    
    // Borrar elementos existentes
    $items = wp_get_nav_menu_items($menu_id);
    if ($items) {
        foreach ($items as $item) {
            wp_delete_post($item->ID, true);
        }
    }
    echo "✅ Menú '$menu_name' existente limpiado.\n";
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
        echo "✅ Añadido al menú: $title\n";
    } else {
        echo "❌ No se pudo encontrar la página '$slug' para añadir al menú.\n";
    }
}

// Asignar el menú a la ubicación primary
$locations = get_theme_mod('nav_menu_locations');
if (!is_array($locations)) {
    $locations = array();
}
$locations[$menu_location] = $menu_id;
set_theme_mod('nav_menu_locations', $locations);
echo "✅ Menú asignado a la ubicación principal.\n";

// 4. Configurar la página de inicio para clientes
$home_page = get_page_by_path('portal-cliente');
if ($home_page) {
    update_option('page_on_front', $home_page->ID);
    update_option('show_on_front', 'page');
    echo "✅ Página de inicio configurada como 'Portal de Cliente'.\n";
} else {
    echo "❌ No se pudo encontrar la página 'Portal de Cliente' para configurarla como página de inicio.\n";
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
    echo "✅ Rol de 'Cliente BrokerAI' creado correctamente.\n";
} else {
    echo "ℹ️ El rol 'Cliente BrokerAI' ya existe.\n";
}

// 6. Crear páginas de redirección para login/registro
$login_redirect = get_option('broker_login_redirect');
if (empty($login_redirect)) {
    $client_dashboard = get_page_by_path('portal-cliente');
    if ($client_dashboard) {
        update_option('broker_login_redirect', $client_dashboard->ID);
        echo "✅ Redirección después del login configurada.\n";
    }
}

echo "Configuración completada.\n"; 