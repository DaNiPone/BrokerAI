<?php
/**
 * Funciones del tema hijo BrokerAI
 */

// Cargar estilos del tema padre y del tema hijo
function broker_child_enqueue_styles() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
    
    // Cargar Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');
    
    // Cargar Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
    
    // Cargar scripts del tema hijo
    wp_enqueue_script('broker-scripts', get_stylesheet_directory_uri() . '/js/broker-scripts.js', array('jquery'), '1.0', true);
    
    // Pasar variables al script
    wp_localize_script('broker-scripts', 'brokerVars', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('broker-ajax-nonce')
    ));
}
add_action('wp_enqueue_scripts', 'broker_child_enqueue_styles');

// Crear carpeta para los scripts
function broker_create_js_directory() {
    $upload_dir = wp_upload_dir();
    $js_dir = get_stylesheet_directory() . '/js';
    
    if (!file_exists($js_dir)) {
        wp_mkdir_p($js_dir);
    }
}
add_action('after_switch_theme', 'broker_create_js_directory'); 