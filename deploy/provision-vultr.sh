#!/usr/bin/env bash
# Provision an Ubuntu 22.04 / 24.04 Vultr VPS for WordPress.
#
# Run *on the server* as root (the local deploy.sh will do this for you):
#   bash provision-vultr.sh <domain-or-IP> <letsencrypt-email> <db-name> <db-user> <db-pass>
#
# If the first arg looks like an IP address, certbot is skipped (no SSL until you point a domain).
# Idempotent — safe to re-run.
set -euo pipefail

DOMAIN="${1:?domain required}"
LE_EMAIL="${2:-admin@example.com}"
DB_NAME="${3:?db name required}"
DB_USER="${4:?db user required}"
DB_PASS="${5:?db password required}"

WP_DIR="/var/www/${DOMAIN}"
NGINX_AVAIL="/etc/nginx/sites-available/${DOMAIN}"
NGINX_ENABLED="/etc/nginx/sites-enabled/${DOMAIN}"

# Detect Ubuntu version → pick PHP package family (22.04→8.1 default; 24.04→8.3)
. /etc/os-release
case "${VERSION_ID:-}" in
  24.04) PHP_VER=8.3 ;;
  22.04) PHP_VER=8.1 ;;
  *)     PHP_VER=8.3 ;;  # fallback
esac
PHP_FPM_SOCK="/var/run/php/php${PHP_VER}-fpm.sock"
PHP_INI="/etc/php/${PHP_VER}/fpm/php.ini"

# Detect IP vs domain. IPs skip Let's Encrypt and serve plain HTTP.
IS_IP=0
if [[ "$DOMAIN" =~ ^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$ ]]; then
  IS_IP=1
fi

log() { printf "\033[1;36m[provision]\033[0m %s\n" "$*"; }

if [[ $EUID -ne 0 ]]; then
  echo "Run as root." >&2
  exit 1
fi

log "Ubuntu ${VERSION_ID:-?} · PHP ${PHP_VER} · target ${DOMAIN}$( ((IS_IP)) && echo ' (IP mode — no SSL)')"

log "Updating apt and installing system packages…"
export DEBIAN_FRONTEND=noninteractive
apt-get update -qq
apt-get install -y -qq \
  nginx \
  mariadb-server mariadb-client \
  php${PHP_VER}-fpm php${PHP_VER}-mysql php${PHP_VER}-curl php${PHP_VER}-gd php${PHP_VER}-mbstring \
  php${PHP_VER}-xml php${PHP_VER}-zip php${PHP_VER}-imagick php${PHP_VER}-intl php${PHP_VER}-bcmath php${PHP_VER}-soap \
  curl unzip rsync ufw fail2ban

if (( ! IS_IP )); then
  apt-get install -y -qq certbot python3-certbot-nginx
fi

log "Configuring firewall…"
ufw allow OpenSSH >/dev/null
ufw allow 'Nginx Full' >/dev/null
echo "y" | ufw enable >/dev/null

log "Tuning PHP for WordPress…"
sed -i 's/^upload_max_filesize.*/upload_max_filesize = 64M/' "$PHP_INI"
sed -i 's/^post_max_size.*/post_max_size = 64M/' "$PHP_INI"
sed -i 's/^memory_limit.*/memory_limit = 256M/' "$PHP_INI"
sed -i 's/^max_execution_time.*/max_execution_time = 180/' "$PHP_INI"
systemctl restart "php${PHP_VER}-fpm"

log "Configuring database…"
systemctl enable --now mariadb
mariadb <<SQL
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
ALTER USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
SQL

log "Installing wp-cli…"
if ! command -v wp >/dev/null 2>&1; then
  curl -fsSL https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar -o /usr/local/bin/wp
  chmod +x /usr/local/bin/wp
fi

log "Preparing site directory at ${WP_DIR}…"
mkdir -p "${WP_DIR}"
chown -R www-data:www-data "${WP_DIR}"

if [[ ! -f "${WP_DIR}/wp-config.php" ]]; then
  log "Downloading and configuring WordPress core…"
  PROTO="$( ((IS_IP)) && echo http || echo https )"
  WP_URL="${PROTO}://${DOMAIN}"
  ADMIN_PW="$(openssl rand -base64 18 | tr -d '=+/')"
  echo "${ADMIN_PW}" > "${WP_DIR}/.wp-admin-password"
  chmod 600 "${WP_DIR}/.wp-admin-password"
  chown www-data:www-data "${WP_DIR}/.wp-admin-password"
  sudo -u www-data -H bash <<EOF
cd "${WP_DIR}"
wp core download --locale=ja --force
wp config create --dbname='${DB_NAME}' --dbuser='${DB_USER}' --dbpass='${DB_PASS}' --dbhost=localhost --skip-check --extra-php <<'PHP'
define('WP_AUTO_UPDATE_CORE', 'minor');
define('DISALLOW_FILE_EDIT', true);
define('FS_METHOD', 'direct');
PHP
wp core install --url="${WP_URL}" --title="Dogo Corporation" --admin_user=admin --admin_password='${ADMIN_PW}' --admin_email='${LE_EMAIL}' --skip-email
wp rewrite structure '/%postname%/' --hard
wp language core install vi
wp language core install ja
wp site switch-language ja
wp plugin install polylang --activate
EOF
  log "Initial admin password saved to ${WP_DIR}/.wp-admin-password (CHANGE IT after first login)."
else
  log "wp-config.php exists — skipping core install."
fi

log "Writing Nginx vhost for ${DOMAIN}…"
SERVER_NAMES="${DOMAIN}"
if (( ! IS_IP )); then
  SERVER_NAMES="${DOMAIN} www.${DOMAIN}"
fi

cat > "${NGINX_AVAIL}" <<NGINX
server {
    listen 80 $( ((IS_IP)) && echo 'default_server' );
    listen [::]:80 $( ((IS_IP)) && echo 'default_server' );
    server_name ${SERVER_NAMES};

    root ${WP_DIR};
    index index.php index.html;

    client_max_body_size 64M;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Permissions-Policy "geolocation=(), microphone=()" always;

    location ~* \.(?:css|js|jpg|jpeg|png|webp|gif|svg|woff2?|ttf|eot|ico)\$ {
        expires 30d;
        add_header Cache-Control "public, immutable";
        try_files \$uri =404;
    }

    location / {
        try_files \$uri \$uri/ /index.php?\$args;
    }

    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:${PHP_FPM_SOCK};
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;
    }

    location ~ /\.(?!well-known) { deny all; }
    location ~* /(?:wp-config\.php|readme\.html|license\.txt)\$ { deny all; }
    location = /xmlrpc.php { deny all; }
}
NGINX

# Remove default vhost if it would compete with us as default_server
if (( IS_IP )); then
  rm -f /etc/nginx/sites-enabled/default
fi

ln -sf "${NGINX_AVAIL}" "${NGINX_ENABLED}"
nginx -t
systemctl reload nginx

if (( ! IS_IP )); then
  log "Requesting Let's Encrypt certificate for ${DOMAIN}…"
  if ! certbot certificates 2>/dev/null | grep -q "Domains: ${DOMAIN}"; then
    certbot --nginx --non-interactive --agree-tos -m "${LE_EMAIL}" \
      -d "${DOMAIN}" -d "www.${DOMAIN}" --redirect || \
      log "WARN: certbot failed. Make sure DNS A records point to this server, then run: certbot --nginx -d ${DOMAIN} -d www.${DOMAIN}"
  else
    log "Certificate already exists — skipping."
  fi
else
  log "IP mode: skipping Let's Encrypt. To enable SSL later: point a domain at this server, then run:"
  log "  apt install -y certbot python3-certbot-nginx && certbot --nginx -d your-domain.com"
fi

log "Provisioning complete."
echo ""
PROTO="$( ((IS_IP)) && echo http || echo https )"
echo "  Site URL : ${PROTO}://${DOMAIN}"
echo "  Web root : ${WP_DIR}"
echo "  DB       : ${DB_NAME} / ${DB_USER}"
echo "  Admin    : ${PROTO}://${DOMAIN}/wp-admin"
if [[ -f "${WP_DIR}/.wp-admin-password" ]]; then
  echo "  Admin pw : $(cat "${WP_DIR}/.wp-admin-password")  ← change immediately"
fi
