<?php
/**
 * Template Name: Formulario de Solicitud de Préstamo
 * 
 * Plantilla para el formulario de solicitud de préstamo hipotecario
 * que se integra con n8n para el procesamiento automatizado
 */

get_header(); 
?>

<div class="broker-dashboard">
    <div class="broker-card">
        <h2 class="broker-card-title">Solicitud de Préstamo Hipotecario</h2>
        
        <div class="broker-form-messages"></div>
        
        <form class="broker-form" id="loan-application-form">
            <div class="broker-form-section">
                <h3>Datos Personales</h3>
                
                <div class="broker-form-row">
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="nombre">Nombre <span class="required">*</span></label>
                        <input type="text" class="broker-form-control" id="nombre" name="nombre" required>
                    </div>
                    
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="apellidos">Apellidos <span class="required">*</span></label>
                        <input type="text" class="broker-form-control" id="apellidos" name="apellidos" required>
                    </div>
                </div>
                
                <div class="broker-form-row">
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="email">Email <span class="required">*</span></label>
                        <input type="email" class="broker-form-control" id="email" name="email" required>
                    </div>
                    
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="telefono">Teléfono <span class="required">*</span></label>
                        <input type="tel" class="broker-form-control" id="telefono" name="telefono" required>
                    </div>
                </div>
                
                <div class="broker-form-row">
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="fecha_nacimiento">Fecha de Nacimiento</label>
                        <input type="date" class="broker-form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                    </div>
                    
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="documento_identidad">Tipo de Documento</label>
                        <select class="broker-form-control" id="documento_identidad" name="documento_identidad">
                            <option value="dni">DNI</option>
                            <option value="nie">NIE</option>
                            <option value="pasaporte">Pasaporte</option>
                        </select>
                    </div>
                </div>
                
                <div class="broker-form-row">
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="numero_documento">Número de Documento</label>
                        <input type="text" class="broker-form-control" id="numero_documento" name="numero_documento">
                    </div>
                    
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="nacionalidad">Nacionalidad</label>
                        <input type="text" class="broker-form-control" id="nacionalidad" name="nacionalidad">
                    </div>
                </div>
            </div>
            
            <div class="broker-form-section">
                <h3>Datos del Préstamo</h3>
                
                <div class="broker-form-row">
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="importe">Importe Solicitado (€) <span class="required">*</span></label>
                        <input type="number" class="broker-form-control" id="importe" name="importe" min="10000" required>
                    </div>
                    
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="plazo">Plazo (meses) <span class="required">*</span></label>
                        <input type="number" class="broker-form-control" id="plazo" name="plazo" min="60" max="480" required>
                    </div>
                </div>
                
                <div class="broker-form-row">
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="tipo_interes">Tipo de Interés Preferido</label>
                        <select class="broker-form-control" id="tipo_interes" name="tipo_interes">
                            <option value="">Seleccionar...</option>
                            <option value="fijo">Fijo</option>
                            <option value="variable">Variable</option>
                            <option value="mixto">Mixto</option>
                            <option value="indiferente">Indiferente</option>
                        </select>
                    </div>
                    
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="finalidad">Finalidad del Préstamo</label>
                        <select class="broker-form-control" id="finalidad" name="finalidad">
                            <option value="">Seleccionar...</option>
                            <option value="primera_vivienda">Compra primera vivienda</option>
                            <option value="segunda_vivienda">Compra segunda vivienda</option>
                            <option value="inversion">Inversión</option>
                            <option value="cambio_vivienda">Cambio de vivienda</option>
                            <option value="reunificacion">Reunificación de deudas</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="broker-form-section">
                <h3>Información Financiera</h3>
                
                <div class="broker-form-row">
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="ingresos_mensuales">Ingresos Mensuales Netos (€)</label>
                        <input type="number" class="broker-form-control" id="ingresos_mensuales" name="ingresos_mensuales">
                    </div>
                    
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="gastos_mensuales">Gastos Mensuales Fijos (€)</label>
                        <input type="number" class="broker-form-control" id="gastos_mensuales" name="gastos_mensuales">
                    </div>
                </div>
                
                <div class="broker-form-row">
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="situacion_laboral">Situación Laboral</label>
                        <select class="broker-form-control" id="situacion_laboral" name="situacion_laboral">
                            <option value="">Seleccionar...</option>
                            <option value="contrato_indefinido">Contrato indefinido</option>
                            <option value="contrato_temporal">Contrato temporal</option>
                            <option value="autonomo">Autónomo</option>
                            <option value="empresario">Empresario</option>
                            <option value="funcionario">Funcionario</option>
                            <option value="otros">Otros</option>
                        </select>
                    </div>
                    
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="antiguedad_laboral">Antigüedad Laboral (años)</label>
                        <input type="number" class="broker-form-control" id="antiguedad_laboral" name="antiguedad_laboral" min="0">
                    </div>
                </div>
                
                <div class="broker-form-row">
                    <div class="broker-form-group broker-form-group-full">
                        <label class="broker-form-label" for="prestamos_actuales">¿Tiene otros préstamos o deudas actualmente?</label>
                        <div class="broker-form-radio-group">
                            <label class="broker-form-radio">
                                <input type="radio" name="prestamos_actuales" value="si"> Sí
                            </label>
                            <label class="broker-form-radio">
                                <input type="radio" name="prestamos_actuales" value="no" checked> No
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="broker-form-row" id="prestamos-detalle" style="display: none;">
                    <div class="broker-form-group broker-form-group-full">
                        <label class="broker-form-label" for="importe_prestamos">Importe total de cuotas mensuales (€)</label>
                        <input type="number" class="broker-form-control" id="importe_prestamos" name="importe_prestamos">
                    </div>
                </div>
            </div>
            
            <div class="broker-form-section">
                <h3>Propiedad</h3>
                
                <div class="broker-form-row">
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="valor_propiedad">Valor de la Propiedad (€)</label>
                        <input type="number" class="broker-form-control" id="valor_propiedad" name="valor_propiedad">
                    </div>
                    
                    <div class="broker-form-group">
                        <label class="broker-form-label" for="ubicacion_propiedad">Ubicación de la Propiedad</label>
                        <input type="text" class="broker-form-control" id="ubicacion_propiedad" name="ubicacion_propiedad" placeholder="Ciudad, Provincia">
                    </div>
                </div>
                
                <div class="broker-form-row">
                    <div class="broker-form-group broker-form-group-full">
                        <label class="broker-form-label" for="comentarios">Comentarios Adicionales</label>
                        <textarea class="broker-form-control" id="comentarios" name="comentarios" rows="4"></textarea>
                    </div>
                </div>
            </div>
            
            <div class="broker-form-section">
                <h3>Términos y Condiciones</h3>
                
                <div class="broker-form-group">
                    <label class="broker-form-checkbox">
                        <input type="checkbox" name="acepto_condiciones" required>
                        He leído y acepto la <a href="/politica-privacidad" target="_blank">política de privacidad</a> y los <a href="/terminos-condiciones" target="_blank">términos y condiciones</a>
                        <span class="required">*</span>
                    </label>
                </div>
                
                <div class="broker-form-group">
                    <label class="broker-form-checkbox">
                        <input type="checkbox" name="acepto_comunicaciones">
                        Acepto recibir comunicaciones comerciales relacionadas con mis intereses
                    </label>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <button type="submit" class="broker-btn broker-btn-primary">
                    <i class="fas fa-paper-plane"></i> Enviar Solicitud
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Estilos adicionales específicos para el formulario de préstamo */
.broker-form-section {
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.broker-form-section h3 {
    margin-bottom: 20px;
    color: var(--broker-dark, #2c3e50);
    font-size: 1.2rem;
}

.broker-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 15px;
}

.broker-form-group-full {
    grid-column: span 2;
}

.broker-form-radio-group {
    display: flex;
    gap: 20px;
}

.broker-form-radio {
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
}

.required {
    color: #e74c3c;
}

.broker-form-messages {
    margin-bottom: 20px;
}

.broker-message {
    padding: 12px 15px;
    border-radius: 4px;
    margin-bottom: 15px;
}

.broker-message-success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.broker-message-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.broker-message-loading {
    background-color: #e9ecef;
    color: #495057;
    border: 1px solid #dee2e6;
}

@media (max-width: 768px) {
    .broker-form-row {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .broker-form-group-full {
        grid-column: span 1;
    }
    
    .broker-form-radio-group {
        flex-direction: column;
        gap: 10px;
    }
}
</style>

<script>
jQuery(document).ready(function($) {
    // Mostrar/ocultar detalles de préstamos según selección
    $('input[name="prestamos_actuales"]').on('change', function() {
        if ($(this).val() === 'si') {
            $('#prestamos-detalle').slideDown();
        } else {
            $('#prestamos-detalle').slideUp();
        }
    });
    
    // Validación del formulario antes de enviar
    $('#loan-application-form').on('submit', function(e) {
        e.preventDefault();
        
        // Validación básica de campos requeridos
        var isValid = true;
        $(this).find('[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('broker-form-error');
            } else {
                $(this).removeClass('broker-form-error');
            }
        });
        
        // Validar email
        var email = $('#email').val();
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            isValid = false;
            $('#email').addClass('broker-form-error');
        }
        
        // Validar teléfono (simplificado)
        var telefono = $('#telefono').val();
        var telefonoRegex = /^[0-9\s+]+$/;
        if (telefono && !telefonoRegex.test(telefono)) {
            isValid = false;
            $('#telefono').addClass('broker-form-error');
        }
        
        if (!isValid) {
            $('.broker-form-messages').html('<div class="broker-message broker-message-error"><i class="fas fa-exclamation-circle"></i> Por favor, complete todos los campos requeridos correctamente.</div>');
            $('html, body').animate({ scrollTop: $('.broker-form-messages').offset().top - 100 }, 200);
            return;
        }
        
        // Recoger datos del formulario
        var formData = $(this).serializeArray();
        
        // Datos para enviar a n8n
        var data = {
            action: 'broker_loan_application',
            nonce: brokerN8nVars.nonce,
            form_data: {}
        };
        
        // Convertir array de formulario a objeto
        $.each(formData, function() {
            data.form_data[this.name] = this.value;
        });
        
        // Mostrar indicador de carga
        $('.broker-form-messages').html('<div class="broker-message broker-message-loading"><i class="fas fa-spinner fa-spin"></i> Procesando su solicitud...</div>');
        
        // Enviar datos mediante AJAX
        $.ajax({
            url: brokerN8nVars.ajaxUrl,
            type: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    $('.broker-form-messages').html('<div class="broker-message broker-message-success"><i class="fas fa-check-circle"></i> Su solicitud de préstamo se ha enviado correctamente. Nos pondremos en contacto con usted lo antes posible.</div>');
                    $('#loan-application-form')[0].reset();
                } else {
                    $('.broker-form-messages').html('<div class="broker-message broker-message-error"><i class="fas fa-exclamation-circle"></i> Error al enviar la solicitud: ' + response.data + '</div>');
                }
                
                $('html, body').animate({ scrollTop: $('.broker-form-messages').offset().top - 100 }, 200);
            },
            error: function() {
                $('.broker-form-messages').html('<div class="broker-message broker-message-error"><i class="fas fa-exclamation-circle"></i> Ha ocurrido un error al procesar su solicitud. Por favor, inténtelo de nuevo más tarde.</div>');
                $('html, body').animate({ scrollTop: $('.broker-form-messages').offset().top - 100 }, 200);
            }
        });
    });
    
    // Validación de campos en tiempo real
    $('.broker-form-control').on('blur', function() {
        if ($(this).prop('required') && !$(this).val()) {
            $(this).addClass('broker-form-error');
        } else {
            $(this).removeClass('broker-form-error');
        }
    });
});
</script>

<?php get_footer(); ?> 