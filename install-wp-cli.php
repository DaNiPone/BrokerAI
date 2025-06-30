<?php
/**
 * Script para instalar WordPress usando WP-CLI
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

echo "Instalando WordPress usando WP-CLI...\n";

// Probar conexión a la base de datos antes de proceder
$link = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$link) {
    die("ERROR: No se pudo conectar a MySQL: " . mysqli_connect_error() . "\n");
}

echo "✅ Conexión exitosa a la base de datos.\n";

// Verificar si WordPress ya está instalado
$result = mysqli_query($link, "SHOW TABLES LIKE 'wp_%'");

if ($result && mysqli_num_rows($result) > 0) {
    echo "WordPress ya parece estar instalado (se encontraron tablas con el prefijo 'wp_').\n";
    mysqli_free_result($result);
    mysqli_close($link);
    exit(0);
}

mysqli_close($link);

// Preparar comandos WP-CLI
$commands = [
    // Descargar WP-CLI si no existe
    "if [ ! -f /usr/local/bin/wp ]; then
        curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
        chmod +x wp-cli.phar
        mv wp-cli.phar /usr/local/bin/wp
    fi",
    
    // Ir al directorio de WordPress
    "cd /var/www/html",
    
    // Verificar si wp-config.php existe, si no, crearlo
    "if [ ! -f wp-config.php ]; then
        wp config create --dbname=$db_name --dbuser=$db_user --dbpass=$db_password --dbhost=$db_host --force
    fi",
    
    // Instalar WordPress si no está instalado
    "wp core install --url=http://localhost:8002 --title=\"$site_title\" --admin_user=$admin_user --admin_password=$admin_password --admin_email=$admin_email",
    
    // Configurar permalinks
    "wp rewrite structure '/%postname%/'",
    
    // Activar tema
    "wp theme activate twentytwentyfour",
    
    // Activar plugin de integración broker
    "wp plugin activate broker-integration"
];

// Ejecutar comandos
foreach ($commands as $command) {
    echo "Ejecutando: $command\n";
    $output = [];
    $return_var = 0;
    exec($command, $output, $return_var);
    
    echo implode("\n", $output) . "\n";
    
    if ($return_var !== 0) {
        echo "ERROR: El comando falló con código de salida $return_var\n";
    }
}

echo "\nInstalación completada. WordPress ha sido instalado correctamente.\n";
echo "URL: http://localhost:8002\n";
echo "Usuario: $admin_user\n";
echo "Contraseña: $admin_password\n"; 