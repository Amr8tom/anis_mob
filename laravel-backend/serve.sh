#!/usr/bin/env bash
# ──────────────────────────────────────────────────────────────
#  Anis Laravel Dev Server — with increased PHP upload limits
#
#  Usage:
#    chmod +x serve.sh
#    ./serve.sh
#
#  Or with a custom port:
#    ./serve.sh 8080
# ──────────────────────────────────────────────────────────────

PORT="${1:-8000}"
HOST="127.0.0.1"

echo ""
echo "  🚀  Starting Anis dev server on http://${HOST}:${PORT}"
echo "  📦  PHP upload limits: 50MB per file, 100MB per request"
echo ""

php \
  -d upload_max_filesize=50M \
  -d post_max_size=100M \
  -d memory_limit=256M \
  -d max_execution_time=120 \
  artisan serve --host="${HOST}" --port="${PORT}"
