/**
 * BrokerAI n8n Integration - Scripts del cliente
 */
(function($) {
    'use strict';
    
    // Objeto principal
    var BrokerN8n = {
        // Inicializar
        init: function() {
            // Inicializar manejadores de eventos
            this.setupFormHandlers();
            this.setupDocumentUpload();
            this.setupMessageCenter();
        },
        
        // Configurar manejadores para los formularios
        setupFormHandlers: function() {
            // Formulario de solicitud de préstamo
            $('#loan-application-form').on('submit', function(e) {
                e.preventDefault();
                
                // Mostrar indicador de carga
                BrokerN8n.showLoading($(this));
                
                // Recoger datos del formulario
                var formData = $(this).serializeArray();
                var data = {
                    action: 'broker_loan_application',
                    nonce: brokerN8nVars.nonce,
                    form_data: BrokerN8n.formArrayToObject(formData)
                };
                
                // Enviar datos mediante AJAX
                $.ajax({
                    url: brokerN8nVars.ajaxUrl,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        BrokerN8n.hideLoading();
                        
                        if (response.success) {
                            BrokerN8n.showSuccess('Su solicitud de préstamo se ha enviado correctamente.');
                            $('#loan-application-form')[0].reset();
                        } else {
                            BrokerN8n.showError('Error al enviar la solicitud: ' + response.data);
                        }
                    },
                    error: function() {
                        BrokerN8n.hideLoading();
                        BrokerN8n.showError('Ha ocurrido un error al procesar su solicitud.');
                    }
                });
            });
            
            // Formulario de verificación de crédito
            $('#credit-check-form').on('submit', function(e) {
                e.preventDefault();
                
                // Mostrar indicador de carga
                BrokerN8n.showLoading($(this));
                
                // Recoger datos del formulario
                var formData = $(this).serializeArray();
                var data = {
                    action: 'broker_credit_check',
                    nonce: brokerN8nVars.nonce,
                    form_data: BrokerN8n.formArrayToObject(formData)
                };
                
                // Enviar datos mediante AJAX
                $.ajax({
                    url: brokerN8nVars.ajaxUrl,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        BrokerN8n.hideLoading();
                        
                        if (response.success) {
                            BrokerN8n.showSuccess('Su solicitud de verificación de crédito se ha enviado correctamente.');
                            $('#credit-check-form')[0].reset();
                        } else {
                            BrokerN8n.showError('Error al enviar la solicitud: ' + response.data);
                        }
                    },
                    error: function() {
                        BrokerN8n.hideLoading();
                        BrokerN8n.showError('Ha ocurrido un error al procesar su solicitud.');
                    }
                });
            });
            
            // Formulario de declaración de ingresos
            $('#income-statement-form').on('submit', function(e) {
                e.preventDefault();
                
                // Mostrar indicador de carga
                BrokerN8n.showLoading($(this));
                
                // Recoger datos del formulario
                var formData = $(this).serializeArray();
                var data = {
                    action: 'broker_income_statement',
                    nonce: brokerN8nVars.nonce,
                    form_data: BrokerN8n.formArrayToObject(formData)
                };
                
                // Enviar datos mediante AJAX
                $.ajax({
                    url: brokerN8nVars.ajaxUrl,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        BrokerN8n.hideLoading();
                        
                        if (response.success) {
                            BrokerN8n.showSuccess('Su declaración de ingresos se ha enviado correctamente.');
                            $('#income-statement-form')[0].reset();
                        } else {
                            BrokerN8n.showError('Error al enviar la declaración: ' + response.data);
                        }
                    },
                    error: function() {
                        BrokerN8n.hideLoading();
                        BrokerN8n.showError('Ha ocurrido un error al procesar su declaración.');
                    }
                });
            });
        },
        
        // Configurar carga de documentos
        setupDocumentUpload: function() {
            $('#document-upload-form').on('submit', function(e) {
                e.preventDefault();
                
                // Verificar que se ha seleccionado un archivo
                var fileInput = $('#document-file')[0];
                if (fileInput.files.length === 0) {
                    BrokerN8n.showError('Por favor, seleccione un archivo para subir.');
                    return;
                }
                
                // Verificar que se ha seleccionado un tipo de documento
                var documentType = $('#document-type').val();
                if (!documentType) {
                    BrokerN8n.showError('Por favor, seleccione el tipo de documento.');
                    return;
                }
                
                // Mostrar indicador de carga
                BrokerN8n.showLoading($(this));
                
                // Crear objeto FormData para enviar el archivo
                var formData = new FormData();
                formData.append('action', 'broker_document_upload');
                formData.append('nonce', brokerN8nVars.nonce);
                formData.append('document', fileInput.files[0]);
                formData.append('document_type', documentType);
                formData.append('document_description', $('#document-description').val());
                
                // Enviar datos mediante AJAX
                $.ajax({
                    url: brokerN8nVars.ajaxUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        BrokerN8n.hideLoading();
                        
                        if (response.success) {
                            BrokerN8n.showSuccess('El documento se ha subido correctamente.');
                            $('#document-upload-form')[0].reset();
                            
                            // Si hay una función de callback para actualizar la lista de documentos
                            if (typeof BrokerN8n.updateDocumentList === 'function') {
                                BrokerN8n.updateDocumentList();
                            }
                        } else {
                            BrokerN8n.showError('Error al subir el documento: ' + response.data);
                        }
                    },
                    error: function() {
                        BrokerN8n.hideLoading();
                        BrokerN8n.showError('Ha ocurrido un error al subir el documento.');
                    }
                });
            });
        },
        
        // Configurar centro de mensajes
        setupMessageCenter: function() {
            // Envío de mensajes
            $('.broker-message-reply form, #message-form').on('submit', function(e) {
                e.preventDefault();
                
                var messageContent = $(this).find('textarea').val();
                var recipientId = $(this).find('[name="recipient_id"]').val() || 0;
                var subject = $(this).find('[name="subject"]').val() || 'Nuevo mensaje';
                
                if (!messageContent) {
                    BrokerN8n.showError('Por favor, escriba un mensaje.');
                    return;
                }
                
                // Mostrar indicador de carga
                BrokerN8n.showLoading($(this));
                
                // Preparar datos
                var data = {
                    action: 'broker_send_message',
                    nonce: brokerN8nVars.nonce,
                    message: messageContent,
                    recipient_id: recipientId,
                    subject: subject
                };
                
                // Enviar datos mediante AJAX
                $.ajax({
                    url: brokerN8nVars.ajaxUrl,
                    type: 'POST',
                    data: data,
                    success: function(response) {
                        BrokerN8n.hideLoading();
                        
                        if (response.success) {
                            BrokerN8n.showSuccess('Su mensaje se ha enviado correctamente.');
                            $(e.target).find('textarea').val('');
                            
                            // Si hay una función de callback para actualizar la lista de mensajes
                            if (typeof BrokerN8n.updateMessageList === 'function') {
                                BrokerN8n.updateMessageList();
                            }
                        } else {
                            BrokerN8n.showError('Error al enviar el mensaje: ' + response.data);
                        }
                    },
                    error: function() {
                        BrokerN8n.hideLoading();
                        BrokerN8n.showError('Ha ocurrido un error al enviar el mensaje.');
                    }
                });
            });
        },
        
        // Convertir array de formulario a objeto
        formArrayToObject: function(formArray) {
            var result = {};
            
            $.each(formArray, function() {
                // Si la clave ya existe, convertirla en array
                if (result[this.name]) {
                    if (!result[this.name].push) {
                        result[this.name] = [result[this.name]];
                    }
                    result[this.name].push(this.value || '');
                } else {
                    result[this.name] = this.value || '';
                }
            });
            
            return result;
        },
        
        // Mostrar indicador de carga
        showLoading: function($form) {
            // Si existe un contenedor de mensaje en el formulario, usarlo
            var $messageContainer = $form.find('.broker-form-messages');
            
            if ($messageContainer.length === 0) {
                // Si no existe, crear uno
                $messageContainer = $('<div class="broker-form-messages"></div>');
                $form.prepend($messageContainer);
            }
            
            // Mostrar mensaje de carga
            $messageContainer.html('<div class="broker-message broker-message-loading"><i class="fas fa-spinner fa-spin"></i> Procesando su solicitud...</div>');
            
            // Deshabilitar botones del formulario
            $form.find('button, input[type="submit"]').prop('disabled', true);
        },
        
        // Ocultar indicador de carga
        hideLoading: function() {
            $('.broker-message-loading').remove();
            $('button, input[type="submit"]').prop('disabled', false);
        },
        
        // Mostrar mensaje de éxito
        showSuccess: function(message) {
            // Crear elemento de mensaje
            var $message = $('<div class="broker-message broker-message-success"><i class="fas fa-check-circle"></i> ' + message + '</div>');
            
            // Agregar al contenedor de mensajes o al cuerpo
            var $messageContainer = $('.broker-form-messages');
            
            if ($messageContainer.length === 0) {
                $messageContainer = $('<div class="broker-form-messages"></div>');
                $('body').prepend($messageContainer);
            }
            
            $messageContainer.html($message);
            
            // Ocultar después de 5 segundos
            setTimeout(function() {
                $message.fadeOut(500, function() {
                    $(this).remove();
                });
            }, 5000);
        },
        
        // Mostrar mensaje de error
        showError: function(message) {
            // Crear elemento de mensaje
            var $message = $('<div class="broker-message broker-message-error"><i class="fas fa-exclamation-circle"></i> ' + message + '</div>');
            
            // Agregar al contenedor de mensajes o al cuerpo
            var $messageContainer = $('.broker-form-messages');
            
            if ($messageContainer.length === 0) {
                $messageContainer = $('<div class="broker-form-messages"></div>');
                $('body').prepend($messageContainer);
            }
            
            $messageContainer.html($message);
            
            // Ocultar después de 5 segundos
            setTimeout(function() {
                $message.fadeOut(500, function() {
                    $(this).remove();
                });
            }, 5000);
        }
    };
    
    // Inicializar cuando el DOM esté listo
    $(document).ready(function() {
        BrokerN8n.init();
    });
    
})(jQuery); 