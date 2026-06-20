# FAHPi — Filipino Australian Health Professionals Inc.

A modern website for the [Filipino Australian Health Professionals Inc. (FAHPi)](https://fahpi.org.au), based in Canberra, ACT. Built as a static HTML/CSS/JS frontend with a lightweight PHP backend for content management.

---

## Project structure

```
fahpi/
├── index.html       # Main public website (Home, Who We Are, Updates)
├── admin.html       # Password-protected content management panel
├── save.php         # PHP API — handles post create, read, delete + photo uploads
├── uploads/         # Photo uploads (created automatically, git-ignored)
├── updates.json     # Published posts storage (created automatically, git-ignored)
├── .gitignore
└── README.md
```

---

## Features

### Public site (`index.html`)
- **Home** — hero, four pillars, news grid, vision & mission, CTA
- **Who we are** — board of directors, vision/mission cards
- **Updates** — dynamically loaded from `updates.json` via `save.php`
- Photo lightbox viewer for post images
- Fully responsive (mobile-friendly)
- Single-page app — no page reloads, URL hash navigation (`#home`, `#about`, `#updates`)

### Admin panel (`admin.html`)
- Password login (shared password, stored in `localStorage`)
- **Dashboard** — stats + quick publish form
- **New update** — full editor with title, category, date, body, and photo upload
- **All updates** — table view with thumbnail previews and delete
- **Settings** — change password, export JSON backup, delete all
- Drag-and-drop photo upload (multiple photos per post)
- Posts saved to server via PHP API — works across any domain or virtual host

### Backend (`save.php`)
- `GET /save.php` — returns all posts as JSON
- `POST /save.php` — creates a new post, saves photos to `uploads/`
- `DELETE /save.php?id=xxx` — deletes a post and its photos
- Photos stored as files on disk (not base64 in JSON)
- Auto-creates and fixes permissions on `uploads/` directory

---

## Deployment

### Requirements
- Apache or Nginx web server
- PHP 8.x
- Write permission on the web root directory

### Steps

**1. Clone the repo**
```bash
git clone https://github.com/YOUR_USERNAME/fahpi.git
cd fahpi
```

**2. Copy to web root**
```bash
sudo cp -r . /var/www/html/fahpi/
sudo chown -R www-data:www-data /var/www/html/fahpi/
```

**3. Create the uploads directory**
```bash
sudo mkdir -p /var/www/html/fahpi/uploads
sudo chown www-data:www-data /var/www/html/fahpi/uploads
sudo chmod 775 /var/www/html/fahpi/uploads
```

**4. Enable PHP in Apache (if not already enabled)**
```bash
sudo a2enmod php8.2
sudo systemctl restart apache2
```

**5. Access the site**
- Public site: `http://YOUR_IP/fahpi/`
- Admin panel: `http://YOUR_IP/fahpi/admin.html`

### Default admin password
```
fahpi2024
```
Change this immediately after first login via **Settings → Change password**.

---

## Virtual host setup (when DNS is ready)

Once `fahpi.org.au` points to your server IP, create an Apache virtual host:

```apache
# /etc/apache2/sites-available/fahpi.conf
<VirtualHost *:80>
    ServerName fahpi.org.au
    ServerAlias www.fahpi.org.au
    DocumentRoot /var/www/html/fahpi
    DirectoryIndex index.html
    <Directory /var/www/html/fahpi>
        AllowOverride None
        Require all granted
    </Directory>
</VirtualHost>
```

```bash
sudo a2ensite fahpi.conf
sudo systemctl reload apache2

# Add HTTPS with Let's Encrypt
sudo certbot --apache -d fahpi.org.au -d www.fahpi.org.au
```

---

## Content management

Non-technical editors can manage content at `/fahpi/admin.html`:

1. Sign in with the shared password
2. Use **Quick publish** on the Dashboard for simple text updates
3. Use **New update** for full posts with photos (drag-and-drop or click to upload)
4. Photos are stored in `uploads/` on the server
5. All posts appear instantly on the public Updates page

---

## Notes

- `uploads/` and `updates.json` are git-ignored — they contain user data and should not be committed
- When migrating servers, copy `uploads/` and `updates.json` manually
- Photo URLs in `updates.json` include the server hostname — run the following if you change IP or domain:
  ```bash
  sed -i 's/OLD_IP_OR_DOMAIN/NEW_DOMAIN/g' /var/www/html/fahpi/updates.json
  ```

---

## Tech stack

| Layer | Technology |
|-------|-----------|
| Frontend | HTML5, CSS3, Vanilla JavaScript (ES5) |
| Backend | PHP 8.x |
| Storage | JSON file + filesystem |
| Web server | Apache 2.4 |
| Fonts | Google Fonts (DM Sans, Playfair Display) |

---

## Organisation

**Filipino Australian Health Professionals Inc. (FAHPi)**  
ABN 71 370 519 961  
Based in Canberra, ACT, Australia  
[fahpi.org.au](https://fahpi.org.au)

