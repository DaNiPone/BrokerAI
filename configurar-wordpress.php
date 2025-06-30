<?php
/**
 * Script para instalar WordPress y configurar BrokerAI
 * 
 * Este script debe ejecutarse desde la línea de comandos dentro del contenedor.
 * Ejemplo: docker exec -it brokerai-wordpress-clients-1 php /var/www/html/wp-content/plugins/broker-integration/configurar-wordpress.php
 */

// Comprobar si se ejecuta desde CLI
if (php_sapi_name() !== 'cli') {
    die('Este script debe ejecutarse desde la línea de comandos.');
}

echo "Iniciando configuración de WordPress para BrokerAI...\n";

// Definir constantes
define('ABSPATH', '/var/www/html/');
define('WP_INSTALLING', true);

// Verificar si WordPress ya está instalado
if (file_exists(ABSPATH . 'wp-config.php')) {
    echo "WordPress ya está instalado.\n";
} else {
    echo "ERROR: wp-config.php no encontrado. Asegúrate de que el contenedor está configurado correctamente.\n";
    exit(1);
}

// Incluir funciones de WordPress
require_once(ABSPATH . 'wp-load.php');
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
require_once(ABSPATH . 'wp-admin/includes/plugin.php');

// Verificar si WordPress ya está instalado
if (is_blog_installed()) {
    echo "WordPress ya está instalado y configurado.\n";
} else {
    echo "Instalando WordPress...\n";
    
    $site_title = 'Portal Cliente BrokerAI';
    $admin_user = 'admin';
    $admin_password = 'broker_admin_password';
    $admin_email = 'admin@example.com';
    
    // Instalar WordPress
    wp_install($site_title, $admin_user, $admin_email, 1, '', $admin_password);
    
    echo "WordPress instalado con éxito.\n";
}

// Activar el plugin de integración
echo "Activando plugins necesarios...\n";

// Verificar si el plugin ya está activo
if (!is_plugin_active('broker-integration/broker-n8n-integration.php')) {
    // Activar el plugin
    activate_plugin(ABSPATH . 'wp-content/plugins/broker-integration/broker-n8n-integration.php');
    echo "Plugin de integración activado.\n";
} else {
    echo "El plugin de integración ya está activo.\n";
}

// Verificar si ACF está instalado y activarlo si es necesario
if (file_exists(ABSPATH . 'wp-content/plugins/advanced-custom-fields/acf.php')) {
    if (!is_plugin_active('advanced-custom-fields/acf.php')) {
        activate_plugin('advanced-custom-fields/acf.php');
        echo "Plugin ACF activado.\n";
    } else {
        echo "Plugin ACF ya está activo.\n";
    }
} else {
    echo "ADVERTENCIA: Plugin ACF no encontrado. Algunas funciones pueden no estar disponibles.\n";
}

// Ejecutar scripts de configuración
echo "Ejecutando scripts de configuración...\n";

// Configurar permalinks
update_option('permalink_structure', '/%postname%/');
echo "Permalinks configurados.\n";

// Ejecutar script de instalación del portal
$script_path = ABSPATH . 'wp-content/plugins/broker-integration/instalar-portal-cliente.php';
if (file_exists($script_path)) {
    include_once($script_path);
    echo "Portal de cliente configurado.\n";
} else {
    echo "ERROR: No se encontró el script de instalación del portal.\n";
}

echo "Configuración completada.\n"; 