@echo off
echo ========================================
echo      Iniciando BrokerAI System
echo ========================================
echo.

REM Verificar si Docker esta en ejecucion
docker info > nul 2>&1
if %errorlevel% neq 0 (
    echo [ERROR] Docker no esta en ejecucion. Por favor, inicie Docker Desktop.
    goto :error
)

echo [INFO] Deteniendo contenedores existentes...
docker-compose -f docker-compose.simple.yml down

echo [INFO] Limpiando el entorno si es necesario...
set /p clean="Desea eliminar todos los datos y comenzar desde cero? (s/n): "
if /i "%clean%"=="s" (
    echo [INFO] Eliminando volumenes de datos...
    docker volume rm brokerai_db_data brokerai_wp_data brokerai_n8n_data 2>nul
    echo [INFO] Volumenes eliminados.
)

echo [INFO] Iniciando contenedores BrokerAI...
docker-compose -f docker-compose.simple.yml up -d
if %errorlevel% neq 0 (
    echo [ERROR] Error al iniciar los contenedores.
    goto :error
)

echo [INFO] Esperando a que los servicios esten disponibles (30 segundos)...
timeout /t 30 /nobreak > nul

echo [INFO] Abriendo interfaz de n8n...
start http://localhost:5678

echo [INFO] Abriendo WordPress...
start http://localhost:8888

echo.
echo ========================================
echo      BrokerAI iniciado correctamente
echo ========================================
echo.
echo Portal de agentes: http://localhost:8888/agent-portal
echo Portal de clientes: http://localhost:8888/client-portal
echo Administracion de n8n: http://localhost:5678
echo Administracion de BD: http://localhost:8080
echo.
echo Para detener el sistema, ejecute: docker-compose -f docker-compose.simple.yml down
echo.
goto :end

:error
echo.
echo [ERROR] Se produjo un error al iniciar BrokerAI.
echo.

:end 