#!/usr/bin/env bash
# Deploy dogo-corporation theme to a Vultr (or any Ubuntu) VPS.
#
# First-time:  bash deploy/deploy.sh           (provisions + deploys + activates theme)
# Updates  :   bash deploy/deploy.sh --files-only
# Uploads  :   bash deploy/deploy.sh --uploads-only
#
# Reads PROD_* vars from `.env`. See `.env.example`.
set -euo pipefail

cd "$(dirname "$0")/.."

# Load env
if [[ -f .env ]]; then
  set -a; source .env; set +a
else
  echo "  Missing .env — copy .env.example to .env and fill in PROD_* values." >&2
  exit 1
fi

: "${PROD_HOST:?Set PROD_HOST in .env}"
: "${PROD_DOMAIN:?Set PROD_DOMAIN in .env (use the IP for IP-only deploys)}"
: "${PROD_DB_NAME:?Set PROD_DB_NAME in .env}"
: "${PROD_DB_USER:?Set PROD_DB_USER in .env}"
: "${PROD_DB_PASSWORD:?Set PROD_DB_PASSWORD in .env}"
PROD_LE_EMAIL="${PROD_LE_EMAIL:-admin@example.com}"

PROD_SSH_USER="${PROD_SSH_USER:-root}"
PROD_SSH_PORT="${PROD_SSH_PORT:-22}"
SSH="ssh -p ${PROD_SSH_PORT} ${PROD_SSH_USER}@${PROD_HOST}"
RSYNC="rsync -az --delete -e 'ssh -p ${PROD_SSH_PORT}'"

REMOTE_WEB_ROOT="/var/www/${PROD_DOMAIN}"
REMOTE_THEME_DIR="${REMOTE_WEB_ROOT}/wp-content/themes/dogo-corporation"
REMOTE_UPLOADS_DIR="${REMOTE_WEB_ROOT}/wp-content/uploads"
LOCAL_THEME_DIR="wp-content/themes/dogo-corporation"

MODE="full"
case "${1:-}" in
  --files-only)   MODE="files" ;;
  --uploads-only) MODE="uploads" ;;
  "")             MODE="full" ;;
  *) echo "Unknown flag: $1"; exit 1 ;;
esac

log() { printf "\033[1;32m[deploy]\033[0m %s\n" "$*"; }

if [[ "$MODE" == "full" ]]; then
  log "Uploading provisioning script to ${PROD_HOST}…"
  scp -P "${PROD_SSH_PORT}" deploy/provision-vultr.sh "${PROD_SSH_USER}@${PROD_HOST}:/tmp/provision-vultr.sh"

  log "Running provisioner on ${PROD_HOST}…"
  $SSH "bash /tmp/provision-vultr.sh '${PROD_DOMAIN}' '${PROD_LE_EMAIL}' '${PROD_DB_NAME}' '${PROD_DB_USER}' '${PROD_DB_PASSWORD}'"
fi

if [[ "$MODE" == "full" || "$MODE" == "files" ]]; then
  log "Syncing theme -> ${PROD_HOST}:${REMOTE_THEME_DIR}…"
  $SSH "mkdir -p '${REMOTE_THEME_DIR}'"
  eval $RSYNC \
    --exclude='.git' --exclude='.DS_Store' --exclude='node_modules' \
    "${LOCAL_THEME_DIR}/" "${PROD_SSH_USER}@${PROD_HOST}:${REMOTE_THEME_DIR}/"

  log "Fixing ownership and activating theme…"
  $SSH "chown -R www-data:www-data '${REMOTE_THEME_DIR}' && cd '${REMOTE_WEB_ROOT}' && sudo -u www-data wp theme activate dogo-corporation && sudo -u www-data wp cache flush || true"
fi

if [[ "$MODE" == "full" || "$MODE" == "uploads" ]]; then
  if [[ -d uploads && -n "$(ls -A uploads 2>/dev/null)" ]]; then
    log "Syncing local uploads -> ${PROD_HOST}…"
    $SSH "mkdir -p '${REMOTE_UPLOADS_DIR}'"
    eval $RSYNC --exclude='.DS_Store' \
      "uploads/" "${PROD_SSH_USER}@${PROD_HOST}:${REMOTE_UPLOADS_DIR}/"
    $SSH "chown -R www-data:www-data '${REMOTE_UPLOADS_DIR}'"
  else
    log "No local uploads/ directory to sync."
  fi
fi

log "Done."
echo ""
echo "  Site:   https://${PROD_DOMAIN}"
echo "  Admin:  https://${PROD_DOMAIN}/wp-admin"
