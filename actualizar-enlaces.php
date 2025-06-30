<?php
/**
 * Script para actualizar los enlaces permanentes en WordPress
 */

// Definir constantes necesarias para WordPress
define('ABSPATH', '/var/www/html/');
define('WP_DEBUG', true);

// Incluir WordPress directamente
require_once('/var/www/html/wp-load.php');

// Actualizar las reglas de reescritura
flush_rewrite_rules(true);

echo "Enlaces permanentes actualizados correctamente."; 