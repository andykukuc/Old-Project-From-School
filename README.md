# Fleet Beacon Tracker — ITMT430

Team capstone project built for Illinois Institute of Technology. A full-stack PHP web application that tracks fleet vehicles in real time using Estimote LTE GPS beacons. Includes a custom PHP API wrapper around the Estimote Cloud REST API, a MySQL-backed user system, dual admin/user portals, and live map visualization via Mapbox.

**Team:** Robert Bacius · John Collins · Jacob Krupa · Andy Kukuc · Geldi Omeri

---

## What It Does

Estimote LTE beacons are physically attached to trucks. Each beacon runs custom JavaScript firmware that collects GPS coordinates, speed, temperature, battery level, and uptime on button press, then transmits the data to the Estimote Cloud. The PHP backend polls that API and surfaces the data through a web portal.

- **Live map** — vehicle positions plotted on a Mapbox map, updated from beacon telemetry
- **Admin portal** — manage beacon registrations, view all vehicle locations, download SQL backups
- **User portal** — view assigned vehicle location, receive notifications, manage account
- **Custom API layer** — `fetch_improved.php` wraps the Estimote Cloud REST API, handles auth, converts units (km/h → mph, Celsius → Fahrenheit), and returns structured data
- **Account system** — registration, login, username/password/name/phone changes, session management

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | PHP 8 (no framework) |
| Database | MySQL 8.0 |
| Frontend | HTML, CSS, vanilla JavaScript |
| Mapping | Mapbox GL JS |
| Beacon API | Estimote Cloud REST API v3 |
| Beacon Firmware | JavaScript (Estimote Micro App SDK) |
| Container | Docker + Docker Compose |
| Web Server | Apache with SSL (self-signed cert) |

---

## Project Structure

```
code/
├── fetch_improved.php        # Estimote Cloud API client — fetches GPS, speed, temp per beacon
├── server.php                # User management — registration, auth, profile updates, SQL dump
├── config.php                # DB connection config
├── index.php                 # Login page
├── register.php              # Registration page
├── logout.php                # Session teardown
├── errors.php                # Error display helper
├── pages_admin/              # Admin portal pages (map, beacons, notifications, settings, account)
├── pages_user/               # User portal pages (map, notifications, settings, account)
├── javascript/
│   ├── map.js                # Mapbox map init — reads MAPBOX_TOKEN from env
│   └── script.js             # General frontend logic
├── css/                      # Stylesheets per portal section
├── sql/
│   ├── create.sql            # Schema — customer table
│   ├── insert.sql            # Seed data
│   ├── encryption.sql        # Encryption helpers
│   └── create-user-with-grants.sql
├── docker/
│   ├── web.Dockerfile        # PHP + Apache image
│   ├── Dockerfile            # Utility image (Fedora + vim)
│   ├── apache-ssl.conf       # Apache SSL virtual host config
│   ├── build.sh / run.sh     # Container build/run helpers
│   └── ssl/                  # SSL certs (gitignored — generate locally)
├── beaconFirmware/
│   └── beaconFirmware-v1.0.1.js   # Estimote Micro App firmware deployed to LTE beacons
├── images/                   # Map markers and icons
└── docker-compose.yml        # Full stack: web + MySQL with health check
diagrams/                     # Architecture, DB schema, wireframes, infrastructure, sprint screenshots
reports/                      # Sprint 1–5 reports
archive/                      # Original Vagrant/Packer VM provisioning scripts (superseded by Docker)
```

---

## Running Locally with Docker

```bash
cd code
docker compose up --build
```

The web container maps to `10.20.20.75` on a Docker bridge network (`br-aux`). Adjust the IP in `docker-compose.yml` to match your network setup.

The database is initialized automatically from `sql/` on first run.

**Mapbox token** — set via environment variable before starting:

```bash
export MAPBOX_TOKEN=your_token_here
docker compose up --build
```

---

## Beacon API — `fetch_improved.php`

The `BeaconDataFetcher` class handles all communication with the Estimote Cloud API. It:

1. Authenticates with HTTP Basic auth (credentials from environment variables)
2. Queries `/v3/lte/device_events` for each registered beacon identifier
3. Returns the most recent telemetry event per beacon

**Response shape per beacon:**

```json
{
  "id": "Truck 1 Blue",
  "temperature": "72.5 ℉",
  "speed": "34.2 mph",
  "location": {
    "lat": 41.88345,
    "long": -87.63241
  }
}
```

Credentials are read from `.env` using `vlucas/phpdotenv`. The `username` and `password` keys map to your Estimote Cloud API credentials.

---

## Beacon Firmware

The firmware in `beaconFirmware/beaconFirmware-v1.0.1.js` runs on-device via the Estimote Micro App SDK. On button press it captures:

- GPS coordinates (lat/long)
- Speed
- Temperature
- Battery percentage and voltage
- Uptime
- Firmware version

Deploy via the Estimote Cloud IDE (IoT Apps → Web IDE). Each deployed app gets its own REST endpoint URL used by `fetch_improved.php`.

---

## Database Schema

```sql
CREATE TABLE customer (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  username    VARCHAR(255) NOT NULL,
  create_date DATETIME     NOT NULL,
  password    VARCHAR(255) NOT NULL,   -- MD5 hashed (legacy)
  last_name   VARCHAR(255),
  first_name  VARCHAR(255),
  phone       VARCHAR(25)
);
```

---

## Diagrams

`diagrams/` includes:

- System architecture diagram
- Database schema (ERD)
- Network/infrastructure diagram with firewall ports
- Hardware communication diagram (beacon → cloud → server)
- UI wireframes
- Final sprint screenshots

---

## Notes

- SSL certs in `docker/ssl/` are gitignored — generate a self-signed cert locally and place `MyCertificate.crt` and `MyKey.key` there before building
- The original VM provisioning approach (Vagrant + Packer) is preserved in `archive/` but is superseded by Docker
- Passwords are stored as MD5 hashes — this is a school project; production use would require `password_hash()`/`password_verify()`

---

## License

MIT
