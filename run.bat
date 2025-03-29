@echo off
echo 🔄 Cerrando contenedores antiguos...
docker rm -f laravel_app >nul 2>&1

echo 🚀 Iniciando entorno Laravel...

REM Cambiar al directorio del proyecto (si el .bat está fuera)
cd /d %~dp0

REM Levantar Docker en segundo plano
docker-compose up -d --build

REM Esperar un poco a que se levanten los servicios
timeout /t 5 /nobreak >nul

REM Ejecutar composer install dentro del contenedor
docker exec laravel_app composer install

REM Ejecutar npm install dentro del contenedor
docker exec laravel_app npm install

REM Generar la clave de la app si no existe
docker exec laravel_app sh -c "if [ ! -f .env ]; then cp .env.example .env && php artisan key:generate; fi"

echo ✅ Todo listo. Abre tu navegador en http://localhost:8000
pause
