<?php
/**
 * Script para instalar WordPress manualmente
 */

// Variables de la instalación
$site_title = 'Portal Cliente BrokerAI';
$admin_user = 'admin';
$admin_password = 'broker_admin_password';
$admin_email = 'admin@example.com';

// Variables de conexión de Docker
$db_host = 'db';
$db_user = 'broker_user';
$db_password = 'broker_password';
$db_name = 'broker_clients';
$table_prefix = 'wp_';

echo "Instalando WordPress manualmente...\n";

// Conectar a la base de datos
$link = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$link) {
    die("ERROR: No se pudo conectar a MySQL: " . mysqli_connect_error() . "\n");
}

echo "✅ Conexión exitosa a la base de datos.\n";

// Verificar si WordPress ya está instalado
$result = mysqli_query($link, "SHOW TABLES LIKE '{$table_prefix}%'");

if ($result && mysqli_num_rows($result) > 0) {
    echo "WordPress ya parece estar instalado (se encontraron tablas con el prefijo '$table_prefix').\n";
    mysqli_free_result($result);
    mysqli_close($link);
    exit(0);
}

// Definir constantes necesarias para WordPress
define('ABSPATH', '/var/www/html/');
define('WP_DEBUG', true);

// Incluir wp-config.php
if (file_exists(ABSPATH . 'wp-config.php')) {
    require_once(ABSPATH . 'wp-config.php');
} else {
    die("ERROR: No se encontró el archivo wp-config.php\n");
}

// Incluir funciones de WordPress para la instalación de tablas
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

echo "Creando tablas de WordPress...\n";

// Crear tablas de WordPress
wp_install($site_title, $admin_user, $admin_email, 1, '', $admin_password);

echo "✅ Tablas creadas correctamente e instalación básica completada.\n";

// Configuración adicional
echo "Configurando opciones adicionales...\n";

// Activar tema
echo "Activando tema...\n";
switch_theme('twentytwentyfour');
echo "✅ Tema activado.\n";

// Permalinks
echo "Configurando permalinks...\n";
update_option('permalink_structure', '/%postname%/');
echo "✅ Permalinks configurados.\n";

// Activar plugins
echo "Activando plugins necesarios...\n";
activate_plugin('broker-integration/broker-n8n-integration.php');
echo "✅ Plugins activados.\n";

echo "\nInstalación completada. WordPress ha sido instalado correctamente.\n";
echo "URL: http://localhost:8002\n";
echo "Usuario: $admin_user\n";
echo "Contraseña: $admin_password\n"; 