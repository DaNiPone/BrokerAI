<?php
/**
 * Plugin Name: BrokerAI Shortcodes
 * Description: Implementación de shortcodes para BrokerAI
 * Version: 1.0.0
 * Author: BrokerAI Team
 */

// Evitar acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Shortcode para el dashboard de cliente
function broker_client_dashboard_shortcode() {
    ob_start();
    ?>
    <div class="broker-dashboard">
        <div class="broker-card">
            <h2 class="broker-card-title">Panel de Control</h2>
            
            <div class="broker-summary">
                <div class="broker-stat">
                    <div class="broker-stat-title">Documentos Pendientes</div>
                    <div class="broker-stat-value">3</div>
                </div>
                
                <div class="broker-stat">
                    <div class="broker-stat-title">Operaciones Activas</div>
                    <div class="broker-stat-value">1</div>
                </div>
                
                <div class="broker-stat">
                    <div class="broker-stat-title">Mensajes Nuevos</div>
                    <div class="broker-stat-value">2</div>
                </div>
                
                <div class="broker-stat">
                    <div class="broker-stat-title">Estado</div>
                    <div class="broker-stat-value">
                        <i class="fas fa-check-circle" style="color: var(--broker-success);"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="broker-card">
            <h2 class="broker-card-title">Actividad Reciente</h2>
            
            <ul class="broker-document-list">
                <li class="broker-document-item">
                    <i class="fas fa-file-pdf broker-document-icon"></i>
                    <div class="broker-document-name">Contrato de hipoteca revisado</div>
                    <div class="broker-document-status broker-status-success">Aprobado</div>
                </li>
                
                <li class="broker-document-item">
                    <i class="fas fa-file-alt broker-document-icon"></i>
                    <div class="broker-document-name">Solicitud de préstamo</div>
                    <div class="broker-document-status broker-status-warning">En revisión</div>
                </li>
                
                <li class="broker-document-item">
                    <i class="fas fa-file-invoice broker-document-icon"></i>
                    <div class="broker-document-name">Declaración de impuestos</div>
                    <div class="broker-document-status broker-status-danger">Pendiente</div>
                </li>
            </ul>
            
            <p style="text-align: center; margin-top: 20px;">
                <a href="<?php echo site_url('/subir-documentos/'); ?>" class="broker-btn broker-btn-primary">
                    <i class="fas fa-upload"></i> Subir Documentos
                </a>
            </p>
        </div>
    </div>
    
    <style>
    /* Estilos básicos para cuando no se usa el tema hijo */
    .broker-dashboard {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 15px;
        font-family: Arial, sans-serif;
    }
    
    .broker-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .broker-card-title {
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #3498db;
    }
    
    .broker-summary {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .broker-stat {
        background-color: #f8f9fa;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        padding: 20px;
        text-align: center;
    }
    
    .broker-stat-title {
        font-size: 0.9rem;
        color: #7f8c8d;
        margin-bottom: 10px;
    }
    
    .broker-stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #3498db;
    }
    
    .broker-document-list {
        list-style-type: none;
        padding: 0;
    }
    
    .broker-document-item {
        padding: 15px;
        border-bottom: 1px solid #eee;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .broker-document-icon {
        font-size: 24px;
        margin-right: 15px;
        color: #3498db;
    }
    
    .broker-document-name {
        flex-grow: 1;
    }
    
    .broker-document-status {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 14px;
    }
    
    .broker-status-success {
        background-color: #e8f8f5;
        color: #27ae60;
    }
    
    .broker-status-warning {
        background-color: #fef9e7;
        color: #f39c12;
    }
    
    .broker-status-danger {
        background-color: #fdedeb;
        color: #e74c3c;
    }
    
    .broker-btn {
        display: inline-block;
        padding: 10px 20px;
        background-color: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 500;
    }
    
    .broker-btn:hover {
        background-color: #2980b9;
    }
    
    @media (max-width: 768px) {
        .broker-summary {
            grid-template-columns: 1fr;
        }
    }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('broker_client_dashboard', 'broker_client_dashboard_shortcode');

// Shortcode para subir documentos
function broker_document_upload_shortcode() {
    ob_start();
    ?>
    <div class="broker-dashboard">
        <div class="broker-card">
            <h2 class="broker-card-title">Subir Documentos</h2>
            
            <form class="broker-form" id="document-upload-form" enctype="multipart/form-data">
                <div class="broker-form-group">
                    <label class="broker-form-label" for="document-type">Tipo de documento</label>
                    <select class="broker-form-control" id="document-type" name="document-type" required>
                        <option value="">Seleccionar tipo...</option>
                        <option value="id">Documento de identidad</option>
                        <option value="income">Comprobante de ingresos</option>
                        <option value="tax">Declaración de impuestos</option>
                        <option value="bank">Extracto bancario</option>
                        <option value="property">Documentación de propiedad</option>
                        <option value="other">Otro documento</option>
                    </select>
                </div>
                
                <div class="broker-form-group">
                    <label class="broker-form-label" for="document-description">Descripción</label>
                    <input type="text" class="broker-form-control" id="document-description" name="document-description" placeholder="Breve descripción del documento">
                </div>
                
                <div class="broker-form-group">
                    <label class="broker-form-label" for="document-file">Archivo</label>
                    <input type="file" class="broker-form-control" id="document-file" name="document-file" required>
                    <p class="broker-form-help">Formatos aceptados: PDF, JPG, PNG. Tamaño máximo: 10MB</p>
                </div>
                
                <div style="text-align: center; margin-top: 20px;">
                    <button type="submit" class="broker-btn broker-btn-primary">
                        <i class="fas fa-upload"></i> Subir Documento
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <style>
    /* Estilos básicos para cuando no se usa el tema hijo */
    .broker-dashboard {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 15px;
        font-family: Arial, sans-serif;
    }
    
    .broker-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .broker-card-title {
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #3498db;
    }
    
    .broker-form {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .broker-form-group {
        margin-bottom: 20px;
    }
    
    .broker-form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #2c3e50;
    }
    
    .broker-form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }
    
    .broker-form-help {
        margin-top: 5px;
        font-size: 14px;
        color: #7f8c8d;
    }
    
    .broker-btn {
        display: inline-block;
        padding: 12px 24px;
        background-color: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }
    
    .broker-btn:hover {
        background-color: #2980b9;
    }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('broker_document_upload', 'broker_document_upload_shortcode');

// Shortcode para seguimiento de estado
function client_status_tracker_shortcode() {
    ob_start();
    ?>
    <div class="broker-dashboard">
        <div class="broker-card">
            <h2 class="broker-card-title">Seguimiento de Estado</h2>
            
            <div class="broker-timeline">
                <div class="broker-timeline-item broker-timeline-completed">
                    <div class="broker-timeline-marker">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="broker-timeline-content">
                        <h3>Solicitud recibida</h3>
                        <p>Su solicitud ha sido recibida y está siendo procesada.</p>
                        <div class="broker-timeline-date">15/04/2025</div>
                    </div>
                </div>
                
                <div class="broker-timeline-item broker-timeline-completed">
                    <div class="broker-timeline-marker">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="broker-timeline-content">
                        <h3>Verificación de documentos</h3>
                        <p>Sus documentos han sido verificados y aprobados.</p>
                        <div class="broker-timeline-date">20/04/2025</div>
                    </div>
                </div>
                
                <div class="broker-timeline-item broker-timeline-active">
                    <div class="broker-timeline-marker">
                        <i class="fas fa-sync-alt fa-spin"></i>
                    </div>
                    <div class="broker-timeline-content">
                        <h3>Análisis financiero</h3>
                        <p>Estamos realizando el análisis financiero de su caso.</p>
                        <div class="broker-timeline-date">Ahora</div>
                    </div>
                </div>
                
                <div class="broker-timeline-item">
                    <div class="broker-timeline-marker">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="broker-timeline-content">
                        <h3>Propuesta de préstamo</h3>
                        <p>Pendiente</p>
                    </div>
                </div>
                
                <div class="broker-timeline-item">
                    <div class="broker-timeline-marker">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="broker-timeline-content">
                        <h3>Aprobación final</h3>
                        <p>Pendiente</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    /* Estilos básicos para cuando no se usa el tema hijo */
    .broker-dashboard {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 15px;
        font-family: Arial, sans-serif;
    }
    
    .broker-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .broker-card-title {
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #3498db;
    }
    
    .broker-timeline {
        position: relative;
        padding-left: 40px;
    }
    
    .broker-timeline:before {
        content: '';
        position: absolute;
        left: 15px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e0e0e0;
    }
    
    .broker-timeline-item {
        position: relative;
        margin-bottom: 30px;
    }
    
    .broker-timeline-marker {
        position: absolute;
        left: -40px;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid #e0e0e0;
        z-index: 1;
    }
    
    .broker-timeline-completed .broker-timeline-marker {
        background: #27ae60;
        border-color: #27ae60;
        color: white;
    }
    
    .broker-timeline-active .broker-timeline-marker {
        background: #3498db;
        border-color: #3498db;
        color: white;
    }
    
    .broker-timeline-content {
        background: #fff;
        padding: 15px;
        border-radius: 4px;
        box-shadow: 0 1px 5px rgba(0,0,0,0.05);
    }
    
    .broker-timeline-content h3 {
        margin-top: 0;
        font-size: 18px;
        color: #2c3e50;
    }
    
    .broker-timeline-date {
        font-size: 14px;
        color: #777;
        margin-top: 10px;
    }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('client_status_tracker', 'client_status_tracker_shortcode');

// Shortcode para centro de mensajes
function client_message_center_shortcode() {
    ob_start();
    ?>
    <div class="broker-dashboard">
        <div class="broker-card">
            <h2 class="broker-card-title">Centro de Mensajes</h2>
            
            <div class="broker-messages">
                <div class="broker-message-list">
                    <div class="broker-message-item broker-message-unread">
                        <div class="broker-message-sender">Ana García, Agente hipotecario</div>
                        <div class="broker-message-subject">Actualización de su solicitud</div>
                        <div class="broker-message-date">Hoy, 10:30</div>
                    </div>
                    
                    <div class="broker-message-item">
                        <div class="broker-message-sender">Carlos López, Analista financiero</div>
                        <div class="broker-message-subject">Documentación adicional requerida</div>
                        <div class="broker-message-date">Ayer, 15:45</div>
                    </div>
                    
                    <div class="broker-message-item">
                        <div class="broker-message-sender">Soporte BrokerAI</div>
                        <div class="broker-message-subject">Bienvenido a BrokerAI</div>
                        <div class="broker-message-date">28/04/2025</div>
                    </div>
                </div>
                
                <div class="broker-message-view">
                    <div class="broker-message-header">
                        <h3>Actualización de su solicitud</h3>
                        <div class="broker-message-info">
                            <div>De: Ana García, Agente hipotecario</div>
                            <div>Recibido: Hoy, 10:30</div>
                        </div>
                    </div>
                    
                    <div class="broker-message-body">
                        <p>Estimado cliente,</p>
                        
                        <p>Me complace informarle que su solicitud avanza favorablemente. Hemos completado la verificación de sus documentos y ahora estamos en la fase de análisis financiero.</p>
                        
                        <p>Este proceso suele durar entre 3 a 5 días hábiles, después de los cuales le presentaremos una propuesta de préstamo que se ajuste a sus necesidades.</p>
                        
                        <p>Si tiene cualquier pregunta o requiere más información, no dude en contactarme.</p>
                        
                        <p>Saludos cordiales,<br>
                        Ana García<br>
                        Agente Hipotecario<br>
                        BrokerAI</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    /* Estilos básicos para cuando no se usa el tema hijo */
    .broker-dashboard {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 15px;
        font-family: Arial, sans-serif;
    }
    
    .broker-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .broker-card-title {
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #3498db;
    }
    
    .broker-messages {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 20px;
    }
    
    .broker-message-list {
        border-right: 1px solid #eee;
    }
    
    .broker-message-item {
        padding: 15px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
    }
    
    .broker-message-item:hover {
        background-color: #f5f5f5;
    }
    
    .broker-message-unread {
        background-color: #ebf5fb;
        font-weight: 500;
    }
    
    .broker-message-sender {
        font-weight: 500;
        margin-bottom: 5px;
    }
    
    .broker-message-subject {
        color: #555;
        margin-bottom: 5px;
    }
    
    .broker-message-date {
        font-size: 12px;
        color: #777;
    }
    
    .broker-message-header {
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
        margin-bottom: 15px;
    }
    
    .broker-message-info {
        color: #777;
        font-size: 14px;
    }
    
    .broker-message-body {
        line-height: 1.6;
    }
    
    @media (max-width: 768px) {
        .broker-messages {
            grid-template-columns: 1fr;
        }
        
        .broker-message-list {
            border-right: none;
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
    }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('client_message_center', 'client_message_center_shortcode');

// Shortcode para formularios
function client_forms_shortcode() {
    ob_start();
    ?>
    <div class="broker-dashboard">
        <div class="broker-card">
            <h2 class="broker-card-title">Formularios</h2>
            
            <div class="broker-forms-list">
                <div class="broker-form-item">
                    <div class="broker-form-icon">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div class="broker-form-info">
                        <h3>Solicitud de préstamo hipotecario</h3>
                        <p>Formulario principal para solicitar su hipoteca.</p>
                    </div>
                    <div class="broker-form-actions">
                        <a href="#" class="broker-btn broker-btn-primary">Completar</a>
                    </div>
                </div>
                
                <div class="broker-form-item">
                    <div class="broker-form-icon">
                        <i class="fas fa-file-signature"></i>
                    </div>
                    <div class="broker-form-info">
                        <h3>Autorización de consulta de crédito</h3>
                        <p>Autorización para verificar su historial crediticio.</p>
                    </div>
                    <div class="broker-form-actions">
                        <a href="#" class="broker-btn broker-btn-primary">Completar</a>
                    </div>
                </div>
                
                <div class="broker-form-item">
                    <div class="broker-form-icon">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div class="broker-form-info">
                        <h3>Declaración de ingresos y gastos</h3>
                        <p>Información detallada sobre su situación financiera.</p>
                    </div>
                    <div class="broker-form-actions">
                        <a href="#" class="broker-btn broker-btn-primary">Completar</a>
                    </div>
                </div>
                
                <div class="broker-form-item">
                    <div class="broker-form-icon">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div class="broker-form-info">
                        <h3>Acuerdo de servicios</h3>
                        <p>Términos y condiciones de nuestros servicios.</p>
                    </div>
                    <div class="broker-form-actions">
                        <span class="broker-status-success" style="padding: 8px 12px; display: inline-block;">Completado</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <style>
    /* Estilos básicos para cuando no se usa el tema hijo */
    .broker-dashboard {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 15px;
        font-family: Arial, sans-serif;
    }
    
    .broker-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .broker-card-title {
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #3498db;
    }
    
    .broker-forms-list {
        margin-top: 20px;
    }
    
    .broker-form-item {
        display: flex;
        align-items: center;
        padding: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .broker-form-item:last-child {
        border-bottom: none;
    }
    
    .broker-form-icon {
        font-size: 24px;
        color: #3498db;
        margin-right: 20px;
    }
    
    .broker-form-info {
        flex-grow: 1;
    }
    
    .broker-form-info h3 {
        margin-top: 0;
        margin-bottom: 5px;
        font-size: 18px;
    }
    
    .broker-form-info p {
        color: #666;
        margin-bottom: 0;
    }
    
    .broker-form-actions {
        margin-left: 20px;
    }
    
    .broker-btn {
        display: inline-block;
        padding: 8px 16px;
        background-color: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 500;
    }
    
    .broker-btn:hover {
        background-color: #2980b9;
    }
    
    .broker-status-success {
        background-color: #e8f8f5;
        color: #27ae60;
        border-radius: 20px;
    }
    
    @media (max-width: 768px) {
        .broker-form-item {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .broker-form-icon {
            margin-bottom: 10px;
        }
        
        .broker-form-actions {
            margin-left: 0;
            margin-top: 15px;
        }
    }
    </style>
    <?php
    return ob_get_clean();
}
add_shortcode('client_forms', 'client_forms_shortcode');

// Shortcode para formulario de registro
function broker_register_form_shortcode() {
    ob_start();
    ?>
    <div class="broker-dashboard">
        <div class="broker-card">
            <h2 class="broker-card-title">Registro de Cliente</h2>
            
            <form class="broker-form" id="register-form">
                <div class="broker-form-section">
                    <h3>Información Personal</h3>
                    
                    <div class="broker-form-row">
                        <div class="broker-form-group">
                            <label class="broker-form-label" for="first-name">Nombre</label>
                            <input type="text" class="broker-form-control" id="first-name" name="first-name" required>
                        </div>
                        
                        <div class="broker-form-group">
                            <label class="broker-form-label" for="last-name">Apellidos</label>
                            <input type="text" class="broker-form-control" id="last-name" name="last-name" required>
                        </div>
                    </div>
                    
                    <div class="broker-form-row">
                        <div class="broker-form-group">
                            <label class="broker-form-label" for="email">Email</label>
                            <input type="email" class="broker-form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="broker-form-group">
                            <label class="broker-form-label" for="phone">Teléfono</label>
                            <input type="tel" class="broker-form-control" id="phone" name="phone" required>
                        </div>
                    </div>
                </div>
                
                <div class="broker-form-section">
                    <h3>Datos de Acceso</h3>
                    
                    <div class="broker-form-row">
                        <div class="broker-form-group">
                            <label class="broker-form-label" for="username">Nombre de Usuario</label>
                            <input type="text" class="broker-form-control" id="username" name="username" required>
                        </div>
                    </div>
                    
                    <div class="broker-form-row">
                        <div class="broker-form-group">
                            <label class="broker-form-label" for="password">Contraseña</label>
                            <input type="password" class="broker-form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="broker-form-group">
                            <label class="broker-form-label" for="confirm-password">Confirmar Contraseña</label>
                            <input type="password" class="broker-form-control" id="confirm-password" name="confirm-password" required>
                        </div>
                    </div>
                </div>
                
                <div style="text-align: center; margin-top: 30px;">
                    <button type="submit" class="broker-btn broker-btn-primary">
                        <i class="fas fa-user-plus"></i> Registrarse
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <style>
    /* Estilos básicos para cuando no se usa el tema hijo */
    .broker-dashboard {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px 15px;
        font-family: Arial, sans-serif;
    }
    
    .broker-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        padding: 25px;
        margin-bottom: 30px;
    }
    
    .broker-card-title {
        color: #2c3e50;
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #3498db;
    }
    
    .broker-form {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .broker-form-section {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }
    
    .broker-form-section h3 {
        margin-bottom: 20px;
        color: #2c3e50;
    }
    
    .broker-form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 15px;
    }
    
    .broker-form-group {
        margin-bottom: 15px;
    }
    
    .broker-form-label {
        display: block;
        margin-bottom: 8px;
        font-weight: 500;
        color: #2c3e50;
    }
    
    .broker-form-control {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 16px;
    }
    
    .broker-btn {
        display: inline-block;
        padding: 12px 24px;
        background-color: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-weight: 500;
        border: none;
        cursor: pointer;
    }
    
    .broker-btn:hover {
        background-color: #2980b9;
    }
    
    @media (max-width: 768px) {
        .broker-form-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }
    }
    </style>
    
    <script>
    // Script básico para validar el formulario
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('register-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var password = document.getElementById('password').value;
                var confirmPassword = document.getElementById('confirm-password').value;
                
                if (password !== confirmPassword) {
                    alert('Las contraseñas no coinciden');
                    return false;
                }
                
                alert('Formulario enviado correctamente. En un entorno real, este formulario registraría un nuevo usuario.');
            });
        }
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('broker_register_form', 'broker_register_form_shortcode');

// Incluir Font Awesome
function broker_enqueue_font_awesome() {
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'broker_enqueue_font_awesome'); 