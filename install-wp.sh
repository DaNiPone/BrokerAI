#!/bin/bash

# Variables de configuración
SITE_TITLE="Portal Cliente BrokerAI"
ADMIN_USER="admin"
ADMIN_PASSWORD="broker_admin_password"
ADMIN_EMAIL="admin@example.com"
DB_NAME="broker_clients"
DB_USER="broker_user"
DB_PASSWORD="broker_password"
DB_HOST="db"
SITE_URL="http://localhost:8002"

echo "Instalando WordPress con WP-CLI..."

# Descargar WP-CLI si no existe
if [ ! -f /usr/local/bin/wp ]; then
    echo "Descargando WP-CLI..."
    curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
    chmod +x wp-cli.phar
    mv wp-cli.phar /usr/local/bin/wp
fi

# Ir al directorio de WordPress
cd /var/www/html

# Comprobar si WordPress ya está instalado
if wp core is-installed --allow-root; then
    echo "WordPress ya está instalado."
else
    # Verificar si wp-config.php existe, si no, crearlo
    if [ ! -f wp-config.php ]; then
        echo "Creando archivo wp-config.php..."
        wp config create --dbname=$DB_NAME --dbuser=$DB_USER --dbpass=$DB_PASSWORD --dbhost=$DB_HOST --force --allow-root
    fi

    # Instalar WordPress core
    echo "Instalando WordPress..."
    wp core install --url=$SITE_URL --title="$SITE_TITLE" --admin_user=$ADMIN_USER --admin_password=$ADMIN_PASSWORD --admin_email=$ADMIN_EMAIL --allow-root

    # Configurar permalinks
    echo "Configurando permalinks..."
    wp rewrite structure '/%postname%/' --allow-root

    # Activar tema
    echo "Activando tema..."
    wp theme activate twentytwentyfour --allow-root

    # Activar plugin de integración broker
    echo "Activando plugins..."
    wp plugin activate broker-integration --allow-root

    echo "Instalación completada correctamente."
    echo "URL: $SITE_URL"
    echo "Usuario: $ADMIN_USER"
    echo "Contraseña: $ADMIN_PASSWORD"
fi 