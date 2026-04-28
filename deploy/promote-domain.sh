#!/usr/bin/env bash
# Promote an IP-only WordPress install to a real domain + Let's Encrypt SSL.
# Run on the server.
set -euo pipefail

DOMAIN="${1:?domain required}"
EMAIL="${2:?email required}"
OLD_DIR="${3:?old web root required}"

NEW_DIR="/var/www/${DOMAIN}"
PHP_SOCK="/var/run/php/php8.3-fpm.sock"
NGX_AVAIL="/etc/nginx/sites-available/${DOMAIN}"
NGX_ENABLED="/etc/nginx/sites-enabled/${DOMAIN}"

log() { printf "\033[1;36m[promote]\033[0m %s\n" "$*"; }

# 1. Move webroot if needed
if [[ -d "$OLD_DIR" && ! -d "$NEW_DIR" ]]; then
  log "Moving ${OLD_DIR} → ${NEW_DIR}"
  mv "$OLD_DIR" "$NEW_DIR"
  chown -R www-data:www-data "$NEW_DIR"
fi

cd "$NEW_DIR"

# 2. Update WP URLs (HTTPS — certbot will activate it momentarily)
log "Updating WordPress siteurl/home to https://${DOMAIN}"
sudo -u www-data -H wp option update siteurl "https://${DOMAIN}"
sudo -u www-data -H wp option update home "https://${DOMAIN}"
sudo -u www-data -H wp search-replace "//45.76.97.66" "//${DOMAIN}" --skip-columns=guid --report-changed-only --quiet || true
sudo -u www-data -H wp cache flush

# 3. Write fresh nginx vhost
log "Writing nginx vhost for ${DOMAIN}"
cat > "${NGX_AVAIL}" <<NGX
server {
    listen 80;
    listen [::]:80;
    server_name ${DOMAIN} www.${DOMAIN};

    root ${NEW_DIR};
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

    location / { try_files \$uri \$uri/ /index.php?\$args; }

    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:${PHP_SOCK};
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;
    }

    location ~ /\.(?!well-known) { deny all; }
    location ~* /(?:wp-config\.php|readme\.html|license\.txt)\$ { deny all; }
    location = /xmlrpc.php { deny all; }
}
NGX

ln -sf "${NGX_AVAIL}" "${NGX_ENABLED}"
# remove the old IP-based vhost
rm -f "/etc/nginx/sites-enabled/$(basename "$OLD_DIR")"
nginx -t
systemctl reload nginx

# 4. Install certbot if missing
if ! command -v certbot >/dev/null 2>&1; then
  log "Installing certbot…"
  DEBIAN_FRONTEND=noninteractive apt-get update -qq
  DEBIAN_FRONTEND=noninteractive apt-get install -y -qq certbot python3-certbot-nginx
fi

# 5. Issue cert
log "Requesting Let's Encrypt cert for ${DOMAIN} + www.${DOMAIN}…"
certbot --nginx --non-interactive --agree-tos -m "${EMAIL}" \
  -d "${DOMAIN}" -d "www.${DOMAIN}" --redirect

log "Done. https://${DOMAIN} should be live."
