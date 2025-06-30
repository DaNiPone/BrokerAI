<?php
/**
 * Script para actualizar URLs del sitio en WordPress
 */

// Definir constantes necesarias para WordPress
define('ABSPATH', '/var/www/html/');

// Incluir WordPress
require_once(ABSPATH . 'wp-load.php');

// URLs actuales
$old_site_url = get_option('siteurl');
$old_home_url = get_option('home');

echo "URLs actuales:\n";
echo "Site URL: " . $old_site_url . "\n";
echo "Home URL: " . $old_home_url . "\n";

// Nuevas URLs
$new_site_url = 'http://localhost:8002';
$new_home_url = 'http://localhost:8002';

// Actualizar opciones
update_option('siteurl', $new_site_url);
update_option('home', $new_home_url);

echo "\nURLs actualizadas:\n";
echo "Site URL: " . get_option('siteurl') . "\n";
echo "Home URL: " . get_option('home') . "\n";

// Actualizar URLs en posts y páginas
global $wpdb;

echo "\nActualizando URLs en contenido...\n";

// Actualizar URLs en posts
$wpdb->query(
    $wpdb->prepare(
        "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, %s, %s)",
        $old_site_url,
        $new_site_url
    )
);

// Actualizar URLs en menús
$wpdb->query(
    $wpdb->prepare(
        "UPDATE {$wpdb->postmeta} SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_key = '_menu_item_url'",
        $old_site_url,
        $new_site_url
    )
);

echo "Proceso completado.\n"; 