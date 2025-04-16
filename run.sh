#!/bin/bash
export PATH="/Applications/Docker.app/Contents/Resources/bin:$PATH"

echo "🔄 Cerrando contenedores antiguos..."
docker rm -f laravel_app > /dev/null 2>&1

echo "🚀 Iniciando entorno Laravel..."

# Cambiar al directorio donde está el script
cd "$(dirname "$0")"

# Levantar Docker en segundo plano
docker compose up -d --build

echo "⏳ Esperando a que MySQL esté disponible..."
until docker exec laravel_mysql mysqladmin ping -h db --silent; do
    sleep 2
done

# Instalar dependencias PHP y JS
docker exec laravel_app composer install
docker exec laravel_app npm install

# Crear .env y generar APP_KEY si no existe
docker exec laravel_app sh -c "if [ ! -f .env ]; then cp .env.example .env && php artisan key:generate; fi"

# Limpiar cachés de Laravel
docker exec laravel_app php artisan config:clear
docker exec laravel_app php artisan route:clear
docker exec laravel_app php artisan view:clear

# Ejecutar migraciones
echo "🧱 Ejecutando migraciones..."
docker exec laravel_app php artisan migrate --force

# (Opcional) Ejecutar seeders
# echo "🌱 Ejecutando seeders..."
# docker exec laravel_app php artisan db:seed --force

echo "✅ Todo listo. Abre tu navegador en http://localhost:8000"
