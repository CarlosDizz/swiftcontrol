#!/bin/bash

echo "🔄 Cerrando contenedores antiguos..."
docker rm -f laravel_app > /dev/null 2>&1

echo "🚀 Iniciando entorno Laravel..."

# Cambiar al directorio donde está el script
cd "$(dirname "$0")"

# Levantar Docker en segundo plano con la nueva sintaxis
docker compose up -d --build

# Esperar un poco a que se levanten los servicios
sleep 5

# Ejecutar composer install dentro del contenedor
docker exec laravel_app composer install

# Ejecutar npm install dentro del contenedor
docker exec laravel_app npm install

# Generar la clave de la app si no existe
docker exec laravel_app sh -c "if [ ! -f .env ]; then cp .env.example .env && php artisan key:generate; fi"

echo "✅ Todo listo. Abre tu navegador en http://localhost:8000"
