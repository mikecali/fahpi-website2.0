# FAHPi — Filipino Australian Health Professionals Inc.

A modern website for [FAHPi](https://fahpi.org.au), based in Canberra, ACT.  
Hosted on **GitHub Pages** with **JSONBin.io** as the backend — no server required.

---

## Live site

```
https://mikecali.github.io/fahpi-website2.0/
```

---

## Project structure

```
fahpi/
├── index.html    # Public website (Home, Who We Are, Updates)
├── admin.html    # Password-protected content management panel
├── README.md
└── .gitignore
```

No server files — data is stored in JSONBin.io.

---

## Features

- **Home** — hero, four pillars, news grid, vision & mission
- **Who we are** — board of directors, vision/mission
- **Updates** — dynamically loaded from JSONBin
- Photo support (stored as base64 in JSONBin)
- Fully responsive
- Single-page app with URL hash navigation

### Admin panel (`admin.html`)
- Password login
- Dashboard with quick publish
- Full post editor with photo upload (drag & drop)
- Delete posts
- Export JSON backup
- Change password

---

## Backend

Data is stored in [JSONBin.io](https://jsonbin.io) — a free hosted JSON API.

| Setting | Value |
|---------|-------|
| Bin ID | `6a4b77bada38895dfe343334` |
| API | `https://api.jsonbin.io/v3/b/` |

---

## Deployment (GitHub Pages)

1. Push this repo to GitHub
2. Go to **Settings → Pages**
3. Branch: `main` → folder: `/(root)` → Save
4. Site live at `https://mikecali.github.io/fahpi-website2.0/`

---

## Default admin password

```
fahpi2024
```

Change via **Settings → Change password** after first login.

---

## Tech stack

| Layer | Technology |
|-------|-----------|
| Hosting | GitHub Pages (free) |
| Storage | JSONBin.io (free) |
| Frontend | HTML5, CSS3, Vanilla JS |
| Fonts | Google Fonts (DM Sans, Playfair Display) |

---

## Organisation

**Filipino Australian Health Professionals Inc. (FAHPi)**  
ABN 71 370 519 961 · Canberra, ACT  
[Facebook](https://www.facebook.com/groups/144269106102315) · [Instagram](https://www.instagram.com/fahpi_cbr/) · [YouTube](https://www.youtube.com/channel/UChY_FNUT2nMKgGHc20WpFLQ)
