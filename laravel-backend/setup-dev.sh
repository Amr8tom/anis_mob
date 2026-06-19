#!/usr/bin/env bash
# ──────────────────────────────────────────────────────────────
#  Anis — First-time dev setup
#  Run once after cloning or when images don't show up
# ──────────────────────────────────────────────────────────────

set -e
cd "$(dirname "$0")"

echo ""
echo "  ⚙️   Running Anis dev setup..."
echo ""

# 1. Create the public/storage symlink so uploaded files are served
if [ -L "public/storage" ]; then
    echo "  ✅  storage:link already exists"
else
    php artisan storage:link
    echo "  ✅  storage:link created"
fi

# 2. Make sure storage directories are writable
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
echo "  ✅  Storage permissions set"

# 3. Clear caches
php artisan config:clear
php artisan view:clear
echo "  ✅  Caches cleared"

echo ""
echo "  🚀  Setup done! Now start the server:"
echo "      ./serve.sh"
echo ""
