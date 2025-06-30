/**
 * Scripts para el tema hijo BrokerAI
 */
jQuery(document).ready(function($) {
    // Mensaje upload form
    $('#document-upload-form').on('submit', function(e) {
        e.preventDefault();
        alert('Función de subida de documentos activada. En un entorno real, este archivo se subiría al servidor.');
        // Aquí iría el código para la subida real del archivo mediante AJAX
    });
    
    // Mensajes - selección de mensaje
    $('.broker-message-item').on('click', function() {
        $('.broker-message-item').removeClass('broker-message-active');
        $(this).addClass('broker-message-active');
        $(this).removeClass('broker-message-unread');
    });
    
    // Formulario de registro
    $('#register-form').on('submit', function(e) {
        e.preventDefault();
        var isValid = true;
        
        // Validación simple de campos
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('broker-form-error');
            } else {
                $(this).removeClass('broker-form-error');
            }
        });
        
        // Validación de contraseñas
        var password = $('#password').val();
        var confirmPassword = $('#confirm-password').val();
        
        if (password !== confirmPassword) {
            isValid = false;
            $('#password, #confirm-password').addClass('broker-form-error');
            alert('Las contraseñas no coinciden');
        }
        
        if (isValid) {
            alert('Formulario enviado correctamente. En un entorno real, este formulario registraría un nuevo usuario.');
            // Aquí iría el código para el registro real del usuario mediante AJAX
        }
    });
    
    // Transiciones suaves
    $('.broker-card, .broker-stat, .broker-timeline-item, .broker-message-item, .broker-form-item')
        .css('opacity', 0)
        .waypoint(function() {
            $(this.element).animate({ opacity: 1 }, 500);
        }, {
            offset: '90%'
        });
    
    // Validación visual de formularios
    $('.broker-form-control').on('blur', function() {
        if ($(this).prop('required') && !$(this).val()) {
            $(this).addClass('broker-form-error');
        } else {
            $(this).removeClass('broker-form-error');
        }
    });
    
    // Estilos adicionales CSS
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .broker-form-error {
                border-color: var(--broker-accent) !important;
                box-shadow: 0 0 0 3px rgba(231, 76, 60, 0.2) !important;
            }
            .broker-message-active {
                background-color: #f0f7ff;
                border-left: 3px solid var(--broker-primary);
            }
        `)
        .appendTo('head');
}); 