<?php
/**
 * Plugin Name: BrokerAI n8n Integration
 * Description: Integración de formularios con n8n para automatizar flujos de trabajo en corretaje hipotecario
 * Version: 1.0.0
 * Author: BrokerAI Team
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

class BrokerAI_n8n_Integration {
    
    // URL base de n8n
    private $n8n_url;
    
    // Configuración del plugin
    private $options;
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->options = get_option('broker_n8n_integration_options', [
            'n8n_url' => 'http://localhost:5678',
            'webhook_paths' => [
                'loan_application' => 'webhook/loan-application',
                'document_upload' => 'webhook/document-upload',
                'credit_check' => 'webhook/credit-check',
                'income_statement' => 'webhook/income-statement',
                'message' => 'webhook/message'
            ]
        ]);
        
        $this->n8n_url = $this->options['n8n_url'];
        
        // Inicializar hooks
        $this->init_hooks();
    }
    
    /**
     * Inicializar hooks
     */
    public function init_hooks() {
        // Agregar menú de admin
        add_action('admin_menu', [$this, 'add_admin_menu']);
        
        // Registrar ajustes
        add_action('admin_init', [$this, 'register_settings']);
        
        // Agregar scripts y estilos
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        
        // Ajax handlers para los formularios
        add_action('wp_ajax_broker_loan_application', [$this, 'handle_loan_application']);
        add_action('wp_ajax_nopriv_broker_loan_application', [$this, 'handle_loan_application']);
        
        add_action('wp_ajax_broker_document_upload', [$this, 'handle_document_upload']);
        add_action('wp_ajax_nopriv_broker_document_upload', [$this, 'handle_document_upload']);
        
        add_action('wp_ajax_broker_credit_check', [$this, 'handle_credit_check']);
        add_action('wp_ajax_nopriv_broker_credit_check', [$this, 'handle_credit_check']);
        
        add_action('wp_ajax_broker_income_statement', [$this, 'handle_income_statement']);
        add_action('wp_ajax_nopriv_broker_income_statement', [$this, 'handle_income_statement']);
        
        add_action('wp_ajax_broker_send_message', [$this, 'handle_send_message']);
        add_action('wp_ajax_nopriv_broker_send_message', [$this, 'handle_send_message']);
    }
    
    /**
     * Agregar scripts y estilos
     */
    public function enqueue_scripts() {
        // Registrar y encolar scripts
        wp_register_script(
            'broker-n8n-integration', 
            plugin_dir_url(__FILE__) . 'assets/js/broker-n8n-integration.js', 
            ['jquery'], 
            '1.0.0', 
            true
        );
        
        // Localizar script con datos necesarios
        wp_localize_script('broker-n8n-integration', 'brokerN8nVars', [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('broker-n8n-integration-nonce')
        ]);
        
        wp_enqueue_script('broker-n8n-integration');
    }
    
    /**
     * Agregar menú de administración
     */
    public function add_admin_menu() {
        add_submenu_page(
            'options-general.php',
            'BrokerAI n8n Integration',
            'BrokerAI n8n',
            'manage_options',
            'broker-n8n-integration',
            [$this, 'admin_page']
        );
    }
    
    /**
     * Registrar ajustes
     */
    public function register_settings() {
        register_setting('broker_n8n_integration', 'broker_n8n_integration_options');
        
        add_settings_section(
            'broker_n8n_integration_section',
            'Configuración de n8n',
            [$this, 'settings_section_callback'],
            'broker-n8n-integration'
        );
        
        add_settings_field(
            'n8n_url',
            'URL de n8n',
            [$this, 'n8n_url_render'],
            'broker-n8n-integration',
            'broker_n8n_integration_section'
        );
        
        add_settings_field(
            'webhook_paths',
            'Rutas de Webhooks',
            [$this, 'webhook_paths_render'],
            'broker-n8n-integration',
            'broker_n8n_integration_section'
        );
    }
    
    /**
     * Callback de la sección de ajustes
     */
    public function settings_section_callback() {
        echo '<p>Configure la integración con n8n para la automatización de flujos de trabajo.</p>';
    }
    
    /**
     * Renderizar campo de URL de n8n
     */
    public function n8n_url_render() {
        $options = get_option('broker_n8n_integration_options');
        ?>
        <input type='text' class="regular-text" name='broker_n8n_integration_options[n8n_url]' value='<?php echo $options['n8n_url']; ?>'>
        <p class="description">URL base de n8n, por ejemplo: http://localhost:5678</p>
        <?php
    }
    
    /**
     * Renderizar campos de rutas de webhooks
     */
    public function webhook_paths_render() {
        $options = get_option('broker_n8n_integration_options');
        $webhook_paths = $options['webhook_paths'];
        
        $webhooks = [
            'loan_application' => 'Solicitud de préstamo',
            'document_upload' => 'Subida de documentos',
            'credit_check' => 'Verificación de crédito',
            'income_statement' => 'Declaración de ingresos',
            'message' => 'Mensajes'
        ];
        
        foreach ($webhooks as $key => $label) {
            $value = isset($webhook_paths[$key]) ? $webhook_paths[$key] : '';
            ?>
            <div style="margin-bottom: 10px;">
                <label style="display: inline-block; width: 180px;"><?php echo $label; ?>:</label>
                <input type='text' class="regular-text" name='broker_n8n_integration_options[webhook_paths][<?php echo $key; ?>]' value='<?php echo $value; ?>'>
            </div>
            <?php
        }
        
        echo '<p class="description">Rutas de webhooks en n8n para cada tipo de formulario</p>';
    }
    
    /**
     * Renderizar página de administración
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1>BrokerAI n8n Integration</h1>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('broker_n8n_integration');
                do_settings_sections('broker-n8n-integration');
                submit_button();
                ?>
            </form>
            
            <div class="card" style="max-width: 600px; margin-top: 20px;">
                <h2>Prueba de Conexión</h2>
                <p>Haga clic en el botón de abajo para probar la conexión con n8n.</p>
                <button id="test-n8n-connection" class="button button-secondary">Probar Conexión</button>
                <div id="test-result" style="margin-top: 10px;"></div>
            </div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $('#test-n8n-connection').on('click', function(e) {
                e.preventDefault();
                
                var $result = $('#test-result');
                $result.html('<span style="color: #666;">Probando conexión...</span>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'broker_test_n8n_connection',
                        nonce: '<?php echo wp_create_nonce('broker-test-n8n-connection'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            $result.html('<span style="color: green;">✓ Conexión exitosa</span>');
                        } else {
                            $result.html('<span style="color: red;">✗ Error de conexión: ' + response.data + '</span>');
                        }
                    },
                    error: function() {
                        $result.html('<span style="color: red;">✗ Error al realizar la prueba</span>');
                    }
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * Enviar datos a n8n
     */
    private function send_to_n8n($webhook_key, $data) {
        $webhook_path = $this->options['webhook_paths'][$webhook_key];
        $webhook_url = trailingslashit($this->n8n_url) . $webhook_path;
        
        $response = wp_remote_post($webhook_url, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode($data),
            'timeout' => 30
        ]);
        
        if (is_wp_error($response)) {
            return [
                'success' => false,
                'message' => $response->get_error_message()
            ];
        }
        
        $body = wp_remote_retrieve_body($response);
        $result = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'success' => false,
                'message' => 'Error al decodificar la respuesta'
            ];
        }
        
        return [
            'success' => true,
            'data' => $result
        ];
    }
    
    /**
     * Handler para solicitud de préstamo
     */
    public function handle_loan_application() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'broker-n8n-integration-nonce')) {
            wp_send_json_error('Nonce inválido');
        }
        
        // Recoger datos del formulario
        $form_data = $_POST['form_data'] ?? [];
        
        // Datos del usuario actual si está autenticado
        $user_data = [];
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $user_data = [
                'id' => $user->ID,
                'email' => $user->user_email,
                'name' => $user->display_name
            ];
        }
        
        // Preparar datos para enviar a n8n
        $data = [
            'form_data' => $form_data,
            'user_data' => $user_data,
            'timestamp' => current_time('mysql'),
            'source' => 'web',
            'form_id' => 'loan_application'
        ];
        
        // Enviar a n8n
        $result = $this->send_to_n8n('loan_application', $data);
        
        if ($result['success']) {
            wp_send_json_success($result['data']);
        } else {
            wp_send_json_error($result['message']);
        }
    }
    
    /**
     * Handler para subida de documentos
     */
    public function handle_document_upload() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'broker-n8n-integration-nonce')) {
            wp_send_json_error('Nonce inválido');
        }
        
        // Asegurarse de que hay un archivo subido
        if (!isset($_FILES['document']) || empty($_FILES['document']['name'])) {
            wp_send_json_error('No se ha subido ningún archivo');
        }
        
        // Obtener información del tipo de documento
        $document_type = sanitize_text_field($_POST['document_type'] ?? '');
        $document_description = sanitize_text_field($_POST['document_description'] ?? '');
        
        // Subir el archivo a WordPress
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        $upload = wp_handle_upload($_FILES['document'], ['test_form' => false]);
        
        if (isset($upload['error'])) {
            wp_send_json_error('Error al subir el archivo: ' . $upload['error']);
        }
        
        // Datos del usuario actual si está autenticado
        $user_data = [];
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $user_data = [
                'id' => $user->ID,
                'email' => $user->user_email,
                'name' => $user->display_name
            ];
        }
        
        // Preparar datos para enviar a n8n
        $data = [
            'document' => [
                'url' => $upload['url'],
                'file' => $upload['file'],
                'type' => $upload['type'],
                'document_type' => $document_type,
                'description' => $document_description
            ],
            'user_data' => $user_data,
            'timestamp' => current_time('mysql'),
            'source' => 'web',
            'form_id' => 'document_upload'
        ];
        
        // Enviar a n8n
        $result = $this->send_to_n8n('document_upload', $data);
        
        if ($result['success']) {
            wp_send_json_success($result['data']);
        } else {
            wp_send_json_error($result['message']);
        }
    }
    
    /**
     * Handler para verificación de crédito
     */
    public function handle_credit_check() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'broker-n8n-integration-nonce')) {
            wp_send_json_error('Nonce inválido');
        }
        
        // Recoger datos del formulario
        $form_data = $_POST['form_data'] ?? [];
        
        // Datos del usuario actual si está autenticado
        $user_data = [];
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $user_data = [
                'id' => $user->ID,
                'email' => $user->user_email,
                'name' => $user->display_name
            ];
        }
        
        // Preparar datos para enviar a n8n
        $data = [
            'form_data' => $form_data,
            'user_data' => $user_data,
            'timestamp' => current_time('mysql'),
            'source' => 'web',
            'form_id' => 'credit_check'
        ];
        
        // Enviar a n8n
        $result = $this->send_to_n8n('credit_check', $data);
        
        if ($result['success']) {
            wp_send_json_success($result['data']);
        } else {
            wp_send_json_error($result['message']);
        }
    }
    
    /**
     * Handler para declaración de ingresos
     */
    public function handle_income_statement() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'broker-n8n-integration-nonce')) {
            wp_send_json_error('Nonce inválido');
        }
        
        // Recoger datos del formulario
        $form_data = $_POST['form_data'] ?? [];
        
        // Datos del usuario actual si está autenticado
        $user_data = [];
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $user_data = [
                'id' => $user->ID,
                'email' => $user->user_email,
                'name' => $user->display_name
            ];
        }
        
        // Preparar datos para enviar a n8n
        $data = [
            'form_data' => $form_data,
            'user_data' => $user_data,
            'timestamp' => current_time('mysql'),
            'source' => 'web',
            'form_id' => 'income_statement'
        ];
        
        // Enviar a n8n
        $result = $this->send_to_n8n('income_statement', $data);
        
        if ($result['success']) {
            wp_send_json_success($result['data']);
        } else {
            wp_send_json_error($result['message']);
        }
    }
    
    /**
     * Handler para envío de mensajes
     */
    public function handle_send_message() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'broker-n8n-integration-nonce')) {
            wp_send_json_error('Nonce inválido');
        }
        
        // Recoger datos del mensaje
        $message = sanitize_textarea_field($_POST['message'] ?? '');
        $subject = sanitize_text_field($_POST['subject'] ?? 'Nuevo mensaje');
        $recipient_id = intval($_POST['recipient_id'] ?? 0);
        
        if (empty($message)) {
            wp_send_json_error('El mensaje no puede estar vacío');
        }
        
        // Datos del usuario actual si está autenticado
        $user_data = [];
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $user_data = [
                'id' => $user->ID,
                'email' => $user->user_email,
                'name' => $user->display_name
            ];
        } else {
            wp_send_json_error('Debe iniciar sesión para enviar mensajes');
        }
        
        // Preparar datos para enviar a n8n
        $data = [
            'message' => [
                'content' => $message,
                'subject' => $subject,
                'recipient_id' => $recipient_id
            ],
            'user_data' => $user_data,
            'timestamp' => current_time('mysql'),
            'source' => 'web',
            'form_id' => 'message'
        ];
        
        // Enviar a n8n
        $result = $this->send_to_n8n('message', $data);
        
        if ($result['success']) {
            wp_send_json_success($result['data']);
        } else {
            wp_send_json_error($result['message']);
        }
    }
    
    /**
     * Probar conexión con n8n
     */
    public function test_n8n_connection() {
        // Verificar nonce
        if (!wp_verify_nonce($_POST['nonce'], 'broker-test-n8n-connection')) {
            wp_send_json_error('Nonce inválido');
        }
        
        $test_url = trailingslashit($this->n8n_url) . 'webhook/test';
        
        $response = wp_remote_post($test_url, [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'body' => json_encode([
                'test' => true,
                'source' => 'wordpress',
                'timestamp' => current_time('mysql')
            ]),
            'timeout' => 5
        ]);
        
        if (is_wp_error($response)) {
            wp_send_json_error($response->get_error_message());
        }
        
        $http_code = wp_remote_retrieve_response_code($response);
        
        if ($http_code >= 200 && $http_code < 300) {
            wp_send_json_success();
        } else {
            wp_send_json_error('Error HTTP: ' . $http_code);
        }
    }
}

// Inicializar el plugin
$broker_n8n_integration = new BrokerAI_n8n_Integration();

// Agregar acción para prueba de conexión
add_action('wp_ajax_broker_test_n8n_connection', [$broker_n8n_integration, 'test_n8n_connection']); 