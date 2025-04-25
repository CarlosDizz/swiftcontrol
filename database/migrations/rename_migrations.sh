#!/bin/bash

# Ruta relativa segura al directorio de migraciones
MIGRATIONS_DIR="./database/migrations"

# Mapeo de renombrado de migraciones en orden correcto
declare -A renames=(
  ["2014_10_12_000000_create_users_table.php"]="2025_04_01_000000_create_users_table.php"
  ["2025_04_16_154613_create_roles_table.php"]="2025_04_01_000100_create_roles_table.php"
  ["2025_04_16_154854_add_role_id_to_users_table.php"]="2025_04_01_000200_add_role_id_to_users_table.php"
  ["2025_04_17_132804_create_events_table.php"]="2025_04_01_000300_create_events_table.php"
  ["2025_04_17_133438_create_price_ranges_table.php"]="2025_04_01_000400_create_price_ranges_table.php"
  ["2025_04_17_203056_create_media_files_table.php"]="2025_04_01_000500_create_media_files_table.php"
  ["2025_04_17_210655_add_poster_file_id_to_events_table.php"]="2025_04_01_000600_add_poster_file_id_to_events_table.php"
  ["2025_04_18_184326_create_tickets_table.php"]="2025_04_01_000700_create_tickets_table.php"
  ["2025_04_19_213946_add_data_tickets.php"]="2025_04_01_000800_add_data_tickets.php"
  ["2014_10_12_100000_create_password_reset_tokens_table.php"]="2025_04_01_000900_create_password_reset_tokens_table.php"
  ["2019_08_19_000000_create_failed_jobs_table.php"]="2025_04_01_001000_create_failed_jobs_table.php"
  ["2019_12_14_000001_create_personal_access_tokens_table.php"]="2025_04_01_001100_create_personal_access_tokens_table.php"
)

# Ejecutar renombrado
for old_name in "${!renames[@]}"; do
  OLD="$MIGRATIONS_DIR/$old_name"
  NEW="$MIGRATIONS_DIR/${renames[$old_name]}"
  if [[ -f "$OLD" ]]; then
    echo "✅ Renombrando $old_name → ${renames[$old_name]}"
    mv "$OLD" "$NEW"
  else
    echo "⚠️ No encontrado: $old_name"
  fi
done

echo -e "\n🎉 Listo. Ejecuta ahora: php artisan migrate:fresh"
