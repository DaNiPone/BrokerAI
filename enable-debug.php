<?php
/**
 * Script para activar el modo de depuración en WordPress
 */

// Definir la ruta a wp-config.php
$wpconfig_path = '/var/www/html/wp-config.php';

// Leer el contenido actual
$config_content = file_get_contents($wpconfig_path);

echo "Activando modo de depuración en WordPress...\n";

// Buscar la línea de WP_DEBUG
if (strpos($config_content, "define( 'WP_DEBUG', false )") !== false) {
    // Reemplazar false por true
    $updated_content = str_replace(
        "define( 'WP_DEBUG', false )",
        "define( 'WP_DEBUG', true )",
        $config_content
    );
    
    echo "Se actualizó la configuración de WP_DEBUG de false a true.\n";
} elseif (strpos($config_content, "define('WP_DEBUG', false)") !== false) {
    // Reemplazar false por true (sin espacios)
    $updated_content = str_replace(
        "define('WP_DEBUG', false)",
        "define('WP_DEBUG', true)",
        $config_content
    );
    
    echo "Se actualizó la configuración de WP_DEBUG de false a true.\n";
} elseif (strpos($config_content, "define( 'WP_DEBUG', true )") !== false || 
          strpos($config_content, "define('WP_DEBUG', true)") !== false) {
    echo "WP_DEBUG ya está configurado como true.\n";
    $updated_content = $config_content;
} else {
    // No encontramos la definición, agregarla antes del marcador de fin de edición
    $marker = "/* That's all, stop editing! Happy publishing. */";
    if (strpos($config_content, $marker) !== false) {
        $debug_code = "define('WP_DEBUG', true);" . PHP_EOL;
        $debug_code .= "define('WP_DEBUG_LOG', true);" . PHP_EOL;
        $debug_code .= "define('WP_DEBUG_DISPLAY', true);" . PHP_EOL;
        $debug_code .= "ini_set('display_errors', 1);" . PHP_EOL . PHP_EOL;
        
        $updated_content = str_replace(
            $marker,
            $debug_code . $marker,
            $config_content
        );
        
        echo "Se agregaron las definiciones de depuración al archivo.\n";
    } else {
        echo "No se pudo encontrar un lugar adecuado para agregar la configuración de depuración.\n";
        exit(1);
    }
}

// Guardar el contenido actualizado
if (file_put_contents($wpconfig_path, $updated_content)) {
    echo "wp-config.php actualizado correctamente.\n";
} else {
    echo "Error al actualizar wp-config.php.\n";
}

echo "Proceso completado. Reinicie el servidor web para aplicar los cambios.\n"; 