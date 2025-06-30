<?php
/**
 * Script para actualizar wp-config.php con nuevas URLs
 */

// Definir la ruta a wp-config.php
$wpconfig_path = '/var/www/html/wp-config.php';

// Leer el contenido actual
$config_content = file_get_contents($wpconfig_path);

echo "Actualizando wp-config.php...\n";

// Buscar y reemplazar las URLs
$old_site_define = "define('WP_HOME', 'http://clientes.krediagil.com:8002');";
$old_siteurl_define = "define('WP_SITEURL', 'http://clientes.krediagil.com:8002');";

$new_site_define = "define('WP_HOME', 'http://localhost:8002');";
$new_siteurl_define = "define('WP_SITEURL', 'http://localhost:8002');";

$updated_content = str_replace($old_site_define, $new_site_define, $config_content);
$updated_content = str_replace($old_siteurl_define, $new_siteurl_define, $updated_content);

// Verificar si hubo cambios
if ($updated_content === $config_content) {
    echo "No se encontraron las definiciones de WP_HOME y WP_SITEURL.\n";
    
    // Intentar agregarlas antes de la línea "That's all, stop editing!"
    $marker = "/* That's all, stop editing! Happy publishing. */";
    if (strpos($config_content, $marker) !== false) {
        $updated_content = str_replace(
            $marker,
            "define('WP_HOME', 'http://localhost:8002');" . PHP_EOL .
            "define('WP_SITEURL', 'http://localhost:8002');" . PHP_EOL . PHP_EOL .
            $marker,
            $config_content
        );
        echo "Se agregaron las definiciones de WP_HOME y WP_SITEURL.\n";
    } else {
        echo "No se pudo encontrar el marcador para agregar las definiciones.\n";
        exit(1);
    }
} else {
    echo "Las definiciones existentes fueron actualizadas.\n";
}

// Guardar el contenido actualizado
if (file_put_contents($wpconfig_path, $updated_content)) {
    echo "wp-config.php actualizado correctamente.\n";
} else {
    echo "Error al actualizar wp-config.php.\n";
}

echo "Proceso completado.\n"; 