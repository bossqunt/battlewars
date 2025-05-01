#!/bin/bash

# Configuration
CONTAINER_NAME="battlewarz-db"
BACKUP_FILE=$1

if [ -z "$BACKUP_FILE" ]; then
  echo "❌ Please provide a backup file path."
  echo "Usage: ./mysql_restore.sh ./db_backups/mysql-backup-2024-09-05_12-00.sql"
  exit 1
fi

echo "⚠️ Restoring backup from $BACKUP_FILE to container: $CONTAINER_NAME"

docker exec -i $CONTAINER_NAME mysql -u root -p"${DB_PASS}" < "$BACKUP_FILE"

if [ $? -eq 0 ]; then
  echo "✅ Restore successful"
else
  echo "❌ Restore failed"
fi
