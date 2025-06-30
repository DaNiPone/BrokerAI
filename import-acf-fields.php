<?php
/**
 * Script para importar campos de ACF automáticamente
 * Coloca este archivo en la raíz del plugin
 */

// Verificar si es una solicitud administrativa
if (!is_admin()) {
    die('Este script solo debe ejecutarse en el panel de administración');
}

// Incluir WordPress
require_once(dirname(__FILE__) . '/../../../wp-load.php');

// Verificar si ACF está activo
if (!class_exists('ACF')) {
    die('Advanced Custom Fields debe estar activado para ejecutar este script');
}

// Ruta al archivo JSON de exportación
$json_file = dirname(__FILE__) . '/acf-export.json';

// Verificar que el archivo existe
if (!file_exists($json_file)) {
    die('Archivo de exportación no encontrado: ' . $json_file);
}

// Leer el archivo JSON
$json = file_get_contents($json_file);
$field_groups = json_decode($json, true);

// Comprobar que el JSON es válido
if (empty($field_groups) || !is_array($field_groups)) {
    die('El archivo JSON no contiene datos válidos');
}

// Importar cada grupo de campos
$imported = 0;
foreach ($field_groups as $field_group) {
    // Comprobar si el grupo ya existe
    $existing_groups = acf_get_field_groups(array(
        'key' => $field_group['key']
    ));

    if (!empty($existing_groups)) {
        echo "<p>Actualizando grupo: " . $field_group['title'] . "</p>";
        acf_update_field_group($field_group);
    } else {
        echo "<p>Importando grupo: " . $field_group['title'] . "</p>";
        acf_add_local_field_group($field_group);
    }
    
    $imported++;
}

echo "<h2>Importación completada</h2>";
echo "<p>Se han importado/actualizado " . $imported . " grupos de campos.</p>";
echo "<p><a href='" . admin_url('edit.php?post_type=acf-field-group') . "'>Ver grupos de campos</a></p>"; 