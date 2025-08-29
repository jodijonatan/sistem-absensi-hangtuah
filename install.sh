#!/bin/bash

# 🏫 Sistem Absensi RFID - Installation Script
# This script automates the installation process

echo "🏫 Installing Sistem Absensi RFID..."
echo "======================================"

# Check if composer is installed
if ! command -v composer &> /dev/null; then
    echo "❌ Composer is not installed. Please install Composer first."
    exit 1
fi

# Check if npm is installed
if ! command -v npm &> /dev/null; then
    echo "❌ NPM is not installed. Please install Node.js and NPM first."
    exit 1
fi

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies
echo "📦 Installing Node.js dependencies..."
npm install

# Copy environment file
if [ ! -f .env ]; then
    echo "⚙️ Creating environment file..."
    cp .env.example .env
    echo "✅ Environment file created. Please configure your database settings in .env"
else
    echo "⚠️ Environment file already exists."
fi

# Generate application key
echo "🔐 Generating application key..."
php artisan key:generate

# Create storage symlink
echo "🔗 Creating storage symlink..."
php artisan storage:link

# Build frontend assets
echo "🏗️ Building frontend assets..."
npm run build

echo ""
echo "🎉 Installation completed!"
echo ""
echo "Next steps:"
echo "1. Configure your database settings in .env file"
echo "2. Create database: sistem_absensi_rfid"
echo "3. Run migrations: php artisan migrate"
echo "4. Seed initial data: php artisan db:seed"
echo "5. Start development server: php artisan serve"
echo ""
echo "Demo accounts:"
echo "Admin: admin@sekolah.com / admin123"
echo "Guru:  budi@sekolah.com / guru123"
echo ""
echo "🚀 Happy coding!"