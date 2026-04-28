# Deploying to a Vultr VPS

This walks through the one-time setup of a fresh Vultr instance and the day-to-day deploy loop.

## 1. Create the Vultr instance

1. **Vultr → Deploy New Server**
   - **Type**: Cloud Compute (Regular Performance is fine to start)
   - **Image**: Ubuntu 22.04 LTS (or 24.04)
   - **Size**: 1 vCPU / 2 GB RAM minimum (`vc2-1c-2gb`)
   - **Region**: closest to your audience (Tokyo for JP, Singapore for SEA)
   - Add your **SSH public key** so you can connect without a password
2. After ~60s, copy the server's **public IPv4** address.
3. Point your DNS (Cloudflare / Vultr DNS / etc.) at it:
   ```
   A    @     <vultr-ip>
   A    www   <vultr-ip>
   ```
   Wait until `dig +short your-domain.com` returns the new IP before continuing (Cloudflare proxy → grey cloud during cert issuance).

## 2. Configure local credentials

Copy the env template and fill in the production block:

```bash
cp .env.example .env
$EDITOR .env
```

Required for deploy:

```ini
PROD_HOST=203.0.113.42
PROD_SSH_USER=root
PROD_DOMAIN=dogo-corporation.com
PROD_LE_EMAIL=admin@dogo-corporation.com
PROD_DB_NAME=dogo_prod
PROD_DB_USER=dogo_prod
PROD_DB_PASSWORD=$(openssl rand -base64 24)
```

Make sure your local public key (`~/.ssh/id_ed25519.pub`) is in `~/.ssh/authorized_keys` on the VPS.

## 3. First-time deploy

```bash
make deploy
```

This will:

1. Upload `deploy/provision-vultr.sh` to the server and run it
2. Install Nginx, PHP 8.2-FPM, MariaDB, certbot, ufw, fail2ban
3. Create the database and download/install WordPress core
4. Install Polylang plugin and language packs (vi, ja)
5. Configure the Nginx vhost + Let's Encrypt SSL
6. `rsync` the theme to `/var/www/<domain>/wp-content/themes/dogo-corporation/`
7. Activate the theme via `wp-cli`

When it finishes, log in at `https://<domain>/wp-admin` with `admin / changeme-on-first-login` — **change the password immediately**.

## 4. Day-to-day

**Push only the theme** (no provisioning):
```bash
make deploy-files
```

**Push uploads** (after media library changes locally):
```bash
make deploy-images
```

**SSH in**:
```bash
ssh root@$PROD_HOST
sudo -u www-data wp --path=/var/www/$PROD_DOMAIN plugin list
```

## 5. Recommended hardening (after first deploy)

```bash
ssh root@$PROD_HOST
adduser deploy
usermod -aG sudo deploy
rsync ~root/.ssh/authorized_keys ~deploy/.ssh/authorized_keys
chown deploy:deploy ~deploy/.ssh/authorized_keys

# Disable root SSH login
sed -i 's/^#\?PermitRootLogin.*/PermitRootLogin no/' /etc/ssh/sshd_config
systemctl restart sshd
```

Then change `PROD_SSH_USER=deploy` in `.env`.

## 6. Renewals & backups

- **SSL renewal** is automatic via certbot's systemd timer (`systemctl status certbot.timer`).
- **Database backup** (run from your laptop):
  ```bash
  ssh $PROD_SSH_USER@$PROD_HOST "mysqldump $PROD_DB_NAME" | gzip > "backup-$(date +%F).sql.gz"
  ```
- Schedule it with cron, or move to **Vultr Snapshots** for whole-disk backups.

## 7. Troubleshooting

| Symptom | Check |
|---|---|
| 502 Bad Gateway | `systemctl status php8.2-fpm` |
| Permissions error in WP admin | `chown -R www-data:www-data /var/www/<domain>` |
| Certbot fails | DNS not propagated, or Cloudflare proxy is on (turn off during issuance) |
| `wp` command not found | re-run provision script — it installs to `/usr/local/bin/wp` |
| Theme not visible | `sudo -u www-data wp theme list --path=/var/www/<domain>` |
