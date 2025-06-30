<?php
/**
 * Script para probar la conexión a la base de datos de WordPress
 */

// Variables de conexión de Docker
$db_host = 'db';
$db_user = 'broker_user';
$db_password = 'broker_password';
$db_name = 'broker_clients';

echo "Probando conexión a la base de datos MySQL...\n";
echo "Host: $db_host\n";
echo "Usuario: $db_user\n";
echo "Base de datos: $db_name\n";

// Intentar conectar
$link = mysqli_connect($db_host, $db_user, $db_password, $db_name);

if (!$link) {
    echo "ERROR: No se pudo conectar a MySQL: " . mysqli_connect_error() . "\n";
    
    // Intentar conectar sólo al servidor sin especificar la base de datos
    echo "\nIntentando conexión sin base de datos específica...\n";
    $link_server = mysqli_connect($db_host, $db_user, $db_password);
    
    if (!$link_server) {
        echo "ERROR: No se pudo conectar al servidor MySQL: " . mysqli_connect_error() . "\n";
    } else {
        echo "✅ Conexión exitosa al servidor MySQL.\n";
        
        // Listar bases de datos disponibles
        echo "\nBases de datos disponibles:\n";
        $result = mysqli_query($link_server, "SHOW DATABASES;");
        
        if ($result) {
            while ($row = mysqli_fetch_row($result)) {
                echo "- " . $row[0] . "\n";
            }
            mysqli_free_result($result);
        } else {
            echo "ERROR: No se pudieron listar las bases de datos: " . mysqli_error($link_server) . "\n";
        }
        
        mysqli_close($link_server);
    }
    
    exit(1);
}

echo "✅ Conexión exitosa a la base de datos.\n";

// Verificar tablas en la base de datos
echo "\nTablas en la base de datos '$db_name':\n";
$result = mysqli_query($link, "SHOW TABLES;");

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_row($result)) {
            echo "- " . $row[0] . "\n";
        }
    } else {
        echo "No hay tablas en la base de datos.\n";
    }
    mysqli_free_result($result);
} else {
    echo "ERROR: No se pudieron listar las tablas: " . mysqli_error($link) . "\n";
}

mysqli_close($link); 