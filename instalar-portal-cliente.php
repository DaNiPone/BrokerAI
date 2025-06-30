<?php
/**
 * Script para configurar automáticamente todo el portal de clientes
 */

// Verificar si es una solicitud administrativa
if (!defined('ABSPATH')) {
    require_once(dirname(__FILE__) . '/n8n-project/wordpress/broker-integration/../../../wp-load.php');
}

if (!current_user_can('administrator')) {
    die('Acceso denegado. Debes ser administrador para ejecutar este script.');
}

echo "<h1>Configuración del Portal de Clientes</h1>";

// 1. Copiar el archivo CSS al tema
$css_source = dirname(__FILE__) . '/estilos-cliente.css';
$css_destination = get_template_directory() . '/broker-client.css';

if (file_exists($css_source)) {
    if (copy($css_source, $css_destination)) {
        echo "<p>✅ Archivo CSS copiado al tema correctamente.</p>";
        
        // Registrar el archivo CSS
        function broker_enqueue_styles() {
            wp_enqueue_style('broker-client-styles', get_template_directory_uri() . '/broker-client.css');
        }
        add_action('wp_enqueue_scripts', 'broker_enqueue_styles');
        
        echo "<p>✅ Estilos registrados correctamente.</p>";
    } else {
        echo "<p>❌ Error al copiar el archivo CSS. Verifica los permisos.</p>";
    }
} else {
    echo "<p>❌ Archivo CSS no encontrado en la ruta: $css_source</p>";
}

// 2. Crear páginas
echo "<h2>Creando páginas...</h2>";
include_once(dirname(__FILE__) . '/crear-paginas-cliente.php');

// 3. Crear menú
echo "<h2>Configurando menú...</h2>";
include_once(dirname(__FILE__) . '/crear-menu-cliente.php');

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
echo "<p><a href='" . home_url('/portal-cliente/') . "' class='button button-primary'>Ver Portal de Cliente</a></p>";
?> 