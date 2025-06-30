#!/bin/bash

echo "Iniciando entorno BrokerAI con Docker..."
echo

# Verificar si Docker está instalado
if ! command -v docker &> /dev/null; then
  echo "Error: Docker no está instalado."
  echo "Por favor, instala Docker desde https://www.docker.com/products/docker-desktop"
  exit 1
fi

# Verificar si Docker está en ejecución
if ! docker info &> /dev/null; then
  echo "Error: Docker no está en ejecución."
  echo "Por favor, inicia Docker e intenta nuevamente."
  exit 1
fi

# Verificar si docker-compose está instalado
if ! command -v docker-compose &> /dev/null; then
  echo "Error: docker-compose no está instalado."
  echo "Por favor, instala docker-compose primero."
  exit 1
fi

echo "Iniciando contenedores..."
docker-compose up -d

echo
echo "¡Entorno BrokerAI iniciado!"
echo
echo "Accede a tus sitios a través de:"
echo "  - Web principal: http://krediagil.com:8000"
echo "  - Portal de agentes: http://agentes.krediagil.com:8001"
echo "  - Portal de clientes: http://clientes.krediagil.com:8002"
echo "  - n8n Workflows: http://localhost:5678"
echo "  - phpMyAdmin: http://localhost:8080"
echo
echo "IMPORTANTE: Recuerda configurar tu archivo hosts según las instrucciones en hosts-config.txt"
echo
read -p "Presiona Enter para continuar..." 