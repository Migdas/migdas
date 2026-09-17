#!/usr/bin/env bash

set -Eeuo pipefail

SHOP_DIR="/opt/shop"
BACKUP_DIR="/opt/backups/migdas"
DATE="$(date +'%Y-%m-%d_%H-%M-%S')"

cd "$SHOP_DIR"

source "$SHOP_DIR/.env"

mkdir -p "$BACKUP_DIR"
chmod 700 "$BACKUP_DIR"

# Baza MySQL
docker compose exec -T \
    -e MYSQL_PWD="$DB_ROOT_PASSWORD" \
    db mysqldump \
    -uroot \
    --single-transaction \
    --quick \
    --routines \
    --triggers \
    --events \
    shop \
    | gzip -9 > "$BACKUP_DIR/database_$DATE.sql.gz"

# Dane, których nie trzymamy w Git
tar -czf "$BACKUP_DIR/files_$DATE.tar.gz" \
    app/.env \
    .env \
    app/storage/app \
    certbot/conf

chmod 600 "$BACKUP_DIR"/*

# Usuń backupy starsze niż 14 dni
find "$BACKUP_DIR" -type f -mtime +14 -delete

echo "Backup zakończony: $DATE"
