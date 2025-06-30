# BrokerAI - Sistema de Integración para Agentes Inmobiliarios

Sistema de automatización y gestión para agentes inmobiliarios que integra WordPress con flujos de trabajo automatizados mediante n8n.

## Requisitos Previos

- Docker Desktop
- Windows 10/11 (para startup.bat) o equivalente en Linux/Mac
- Navegador web moderno (Chrome, Firefox, Edge)
- Mínimo 4GB de RAM disponible para Docker

## Instalación Rápida

1. Clona este repositorio:
   ```
   git clone https://github.com/tu-usuario/BrokerAI.git
   cd BrokerAI
   ```

2. Ejecuta el script de inicio:
   ```
   startup.bat
   ```
   
3. Espera a que todos los servicios estén disponibles (aproximadamente 30 segundos).

4. Los navegadores se abrirán automáticamente con acceso a:
   - WordPress: http://localhost:8888
   - n8n: http://localhost:5678

## Configuración Manual

Si prefieres realizar una configuración manual o si el script de inicio no funciona correctamente:

1. Inicia los contenedores:
   ```
   docker-compose -f docker-compose.simple.yml up -d
   ```

2. Accede a las interfaces web:
   - WordPress: http://localhost:8888
   - n8n: http://localhost:5678

3. Configura WordPress:
   - Instala y activa el plugin "BrokerAI Integration"
   - Configura las credenciales de webhook en Ajustes > BrokerAI
   - Verifica que las páginas de portal de agentes y clientes estén creadas

4. Configura n8n:
   - Importa el flujo de trabajo orquestador `orchestrator.workflow.json`
   - Activa el flujo de trabajo
   - Verifica que los webhooks estén correctamente configurados

## Estructura del Sistema

El sistema BrokerAI consta de los siguientes componentes:

### WordPress (Frontend y Backend Principal)
- **Portal de Agentes**: Interfaz para que los agentes gestionen clientes y documentos
- **Portal de Clientes**: Interfaz para que los clientes suban documentos y completen formularios
- **API REST**: Proporciona endpoints para la integración con n8n

### n8n (Automatización de Flujos de Trabajo)
- **Orquestador**: Gestiona todas las solicitudes entrantes y las dirige al proceso correcto
- **Procesamiento de Documentos**: Analiza y extrae información de documentos
- **Notificaciones**: Envía alertas por email cuando hay actualizaciones importantes
- **Integraciones**: Conecta con sistemas externos cuando es necesario

## Endpoints de API

El plugin de WordPress expone los siguientes endpoints:

| Endpoint | Método | Descripción |
|----------|--------|-------------|
| `/wp-json/broker-api/v1/webhook` | POST | Endpoint principal para webhooks |
| `/wp-json/broker-api/v1/register-client` | POST | Registra un nuevo cliente |
| `/wp-json/broker-api/v1/status-update` | POST | Actualiza el estado de una operación |
| `/wp-json/broker-api/v1/upload-document` | POST | Sube un nuevo documento |
| `/wp-json/broker-api/v1/submit-form` | POST | Procesa un formulario completado |

## Flujos de Trabajo en n8n

### Orquestador (orchestrator.workflow.json)
Este flujo de trabajo principal:
1. Recibe solicitudes webhook
2. Identifica el tipo de operación
3. Enruta la solicitud al proceso correspondiente
4. Devuelve una respuesta al remitente

## Solución de Problemas

### Docker no inicia correctamente
- Verifica que Docker Desktop esté en ejecución
- Comprueba que no haya conflictos de puertos (8888, 5678, etc.)
- Revisa los logs: `docker-compose -f docker-compose.simple.yml logs`

### Problemas con WordPress
- Verifica que la base de datos se inicialice correctamente
- Comprueba que el plugin BrokerAI esté activado
- Revisa los logs de WordPress: `docker logs brokerai-wordpress-1`

### Problemas con n8n
- Verifica que las variables de entorno estén configuradas correctamente
- Comprueba que el flujo de trabajo esté activado
- Revisa los logs de n8n: `docker logs brokerai-n8n-1`

## Variables de Entorno

Las siguientes variables de entorno deben estar configuradas en n8n:

| Variable | Descripción |
|----------|-------------|
| `WORDPRESS_URL` | URL del servidor WordPress (por defecto: http://wordpress-main) |
| `WEBHOOK_SECRET` | Clave secreta para autenticar webhooks |

## Detener el Sistema

Para detener todos los servicios:
```
docker-compose -f docker-compose.simple.yml down
```

## Contribuir

Si deseas contribuir a este proyecto, por favor:
1. Haz un fork del repositorio
2. Crea una rama para tu funcionalidad (`git checkout -b feature/nueva-funcionalidad`)
3. Haz commit de tus cambios (`git commit -am 'Añadir nueva funcionalidad'`)
4. Haz push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crea un Pull Request

## Licencia

Este proyecto está licenciado bajo [Licencia MIT](LICENSE). 