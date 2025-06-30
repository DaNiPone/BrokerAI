<?php
/**
 * Script para crear automáticamente las páginas para clientes
 */

// Incluir WordPress
require_once(dirname(__FILE__) . '/n8n-project/wordpress/broker-integration/../../../wp-load.php');

// Verificar permisos
if (!current_user_can('administrator')) {
    die('Acceso denegado. Debes ser administrador para ejecutar este script.');
}

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
echo "<h1>Creación de Páginas para Clientes</h1>";

if (!empty($created_pages)) {
    echo "<h2>Páginas creadas:</h2>";
    echo "<ul>";
    foreach ($created_pages as $page) {
        echo "<li>$page</li>";
    }
    echo "</ul>";
}

if (!empty($updated_pages)) {
    echo "<h2>Páginas actualizadas:</h2>";
    echo "<ul>";
    foreach ($updated_pages as $page) {
        echo "<li>$page</li>";
    }
    echo "</ul>";
}

echo "<p>¡Proceso completado! <a href='". admin_url('edit.php?post_type=page') ."'>Ver todas las páginas</a></p>"; 