#!/bin/bash
set -e

cd "$(dirname "$0")/.."

echo "Starting TravelWorld WordPress..."
docker compose up -d

echo "Waiting for database..."
sleep 15

echo "Running setup script..."
docker compose exec -T wpcli bash /scripts/setup.sh

echo ""
echo "Done! Open http://localhost:8080 in your browser."
