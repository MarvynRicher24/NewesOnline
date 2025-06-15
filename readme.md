# NewesOnline

A modern, lightweight news platform built in plain PHP (MVC) with MySQL (or SQLite for testing), featuring an announcements system, user comments & ratings, and role‑based administration.

---

## Table of Contents

1. [Features](#features)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Configuration](#configuration)
5. [Usage](#usage)
6. [Project Structure](#project-structure)
7. [Testing](#testing)
8. [Contributing](#contributing)
9. [License](#license)

---

## Features

- **Admin Panel**
  - Create, edit, delete announcements
  - Upload optional images
  - Categorize content

- **Public Site**
  - Browse latest announcements
  - Filter by category or search terms
  - View announcement details

- **Subscribers**
  - Register & log in
  - Edit profile (avatar, description, password)
  - Post a single comment + star rating (1–5) per announcement
  - Edit or delete their own comment

- **Comments & Ratings**
  - Display average rating per announcement
  - Subscribers can update or remove their own comment
  - Admins can remove any comment

- **Security & UX**
  - CSRF protection on all forms
  - Passwords hashed with `password_hash()`
  - Responsive, mobile‑friendly HTML/CSS

---

## Requirements

- PHP ≥ 7.4 (tested on 8.0+) with PDO extension
- MySQL 5.7+ (or MariaDB)
- Composer (for PHPUnit & autoloading)
- Web server (Apache, Nginx…) or PHP’s built‑in server

---

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/your‑vendor/newesonline.git
   cd newesonline

2. **Install dependencies**
   composer install

3. **Import database schema**
    mysql -u root -p < database.sql
    **note : The default SQL includes a plaintext admin user (marvyn). After import, hash its password by running :**
    php scripts/hash_admin.php

4. **Adjust file permissions**
    chmod -R 775 public/uploads

---

## Configuration

1. **Database credentials**
    Edit config/config.php (or config/database.php) to set your DB host, name, user, and password :

    return [
    'db_host' => 'localhost',
    'db_name' => 'dwwm',
    'db_user' => 'root',
    'db_pass' => 'secret',
    ];

2. **Virtual host (optional)**
    Configure Apache/Nginx to serve the project root, with public/ as the document root.

3. **Session & CSRF**
    PHP sessions & CSRF tokens require a writable session directory (default OS tmp is fine).

---

## Usage

**Database credentials**
Browse and filter announcements: http://your‑domain/index.php

**Admin**
Log in as admin (default marvyn / marvyn before hashing)
➔ http://your‑domain/index.php?controller=auth&action=connection ➔ Admin Panel

**Subscriber**
Register a new account ➔ log in ➔ comment, rate, edit profile

---

## Project Structure

NewesOnline/
├── config/
│   ├── config.php          # DB credentials
│   └── database.php        # PDO setup
├── controllers/            # MVC controller classes
├── models/                 # Data-access models (PDO)
├── public/                 # Public assets & uploads
│   ├── css/
│   ├── uploads/
│   └── index.php           # Front controller
├── scripts/                # Utility scripts (e.g. hash_admin.php)
├── tests/                  # PHPUnit tests (in-memory SQLite)
├── views/                  # Twig‑style PHP views
│   ├── admin/
│   ├── auth/
│   ├── public/
│   └── subscriber/
├── database.sql            # MySQL schema + seeds
├── composer.json
├── phpunit.xml
└── README.md

---

## Testing
We use PHPUnit with an in‑memory SQLite database for fast, isolated model tests.

1. **Install dev dependencies**
composer install --dev

2. **Run test suite**
Run -> "./vendor/bin/phpunit"

All tests are located in tests/ and follow PSR‑4 autoloading via Composer’s autoload-dev.

---

## Contributing

1. Fork the repository

2. Create a feature branch: git checkout -b feature/foo

3. Commit your changes: git commit -am 'Add feature foo'

4. Push to your branch: git push origin feature/foo

5. Open a Pull Request

Be sure to include tests for any new functionality.

---

## License

Marvyn Richer License.