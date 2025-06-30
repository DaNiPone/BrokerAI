@echo off
echo ========================================
echo   Prueba de Conexion BrokerAI System
echo ========================================
echo.

REM Verificar si Docker esta en ejecucion
docker info > nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Docker no esta en ejecucion. Por favor, inicie Docker Desktop.
    goto :error
)

echo [INFO] Verificando que los contenedores esten en ejecucion...
docker ps | findstr "brokerai-wordpress-1" > nul
if %errorlevel% neq 0 (
    echo [ADVERTENCIA] Los contenedores no estan en ejecucion. Iniciando el sistema...
    call startup.bat
    goto :end
)

echo [INFO] Verificando servicio WordPress...
curl -s -o temp1.txt -w "%%{http_code}" http://localhost:8888/wp-json/wp/v2/posts
set /p wp_status=<temp1.txt
del temp1.txt
if not "%wp_status:~0,1%"=="2" (
    echo [ADVERTENCIA] WordPress API no responde correctamente (codigo: %wp_status%).
) else (
    echo [OK] WordPress API esta funcionando correctamente.
)

echo [INFO] Verificando servicio n8n...
curl -s -o temp2.txt -w "%%{http_code}" http://localhost:5678/healthz
set /p n8n_status=<temp2.txt
del temp2.txt
if not "%n8n_status:~0,1%"=="2" (
    echo [ADVERTENCIA] n8n no responde correctamente (codigo: %n8n_status%).
) else (
    echo [OK] n8n esta funcionando correctamente.
)

echo.
echo [INFO] Probando webhook de orquestador...
curl -s -o response.json -w "%%{http_code}" -X POST -H "Content-Type: application/json" -d "{\"event\":\"test-connection\",\"timestamp\":\"%DATE% %TIME%\"}" http://localhost:5678/webhook/broker-ai/webhook
set /p webhook_status=<temp.txt
del temp.txt

if not "%webhook_status:~0,1%"=="2" (
    echo [ADVERTENCIA] Webhook no responde correctamente (codigo: %webhook_status%).
    echo [INFO] Verificando si el flujo de trabajo esta activado en n8n...
    echo [INFO] Puede que necesite importar y activar el flujo de trabajo orchestrator.workflow.json en n8n.
) else (
    echo [OK] Webhook esta funcionando correctamente.
    echo [INFO] Contenido de la respuesta:
    type response.json
    del response.json
)

echo.
echo ========================================
echo      Prueba de conexion finalizada
echo ========================================
echo.
goto :end

:error
echo.
echo [ERROR] Se produjo un error durante la prueba de conexion.
echo.

:end 