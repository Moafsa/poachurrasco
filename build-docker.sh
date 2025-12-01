#!/bin/bash

echo "========================================"
echo "Building Docker containers for PoaChurras"
echo "========================================"
echo ""

echo "Step 1: Stopping existing containers..."
docker-compose down

echo ""
echo "Step 2: Building Docker image (this may take a few minutes)..."
docker-compose build --no-cache app

echo ""
echo "Step 3: Starting containers..."
docker-compose up -d

echo ""
echo "Step 4: Waiting for database to be ready..."
sleep 10

echo ""
echo "Step 5: Running migrations..."
docker-compose exec -T app php artisan migrate --force

echo ""
echo "Step 6: Generating slugs for existing establishments..."
docker-compose exec -T app php artisan tinker --execute="App\Models\Establishment::whereNull('slug')->get()->each(function(\$e) { \$e->slug = Str::slug(\$e->name); \$e->save(); });"

echo ""
echo "========================================"
echo "Build completed!"
echo "========================================"
echo ""
echo "Containers are running. Access the application at:"
echo "http://localhost:8000"
echo ""



