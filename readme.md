**NewesOnline**

> A modern announcement publishing platform built in PHP (MVC) with MySQL, featuring announcements, comments & star ratings, and role‑based administration.

---

## 🚀 Table of Contents

1. [Features](#features)
2. [Tech Stack](#tech-stack)
3. [Requirements](#requirements)
4. [Getting Started](#getting-started)
5. [Configuration](#configuration)
6. [Usage](#usage)
7. [Project Structure](#project-structure)
8. [Testing](#testing)
9. [Roadmap](#roadmap)
10. [Contributing](#contributing)
11. [License](#license)

---

## ✨ Features

* **Admin Dashboard**

  * Create, edit, and delete announcements
  * Upload and validate images (max 2 MB)
  * Assign categories for better organization

* **Public Interface**

  * Browse, search, and filter announcements by category or keyword
  * Paginated listing for performance
  * Detail pages with content, images, and metadata

* **Subscriber Experience**

  * Secure registration and login
  * Profile management (avatar, description, password)
  * Post **one** comment + star rating (1–5) per announcement
  * Edit or delete own comment anytime

* **Comments & Ratings**

  * Display average rating on listings and detail pages
  * Subscribers can update or remove their feedback
  * Admins can moderate (delete) any comment

* **Security & Best Practices**

  * PDO prepared statements → SQL Injection prevention
  * CSRF tokens on all forms → request forgery protection
  * `htmlspecialchars()` everywhere → XSS defense
  * Passwords hashed with `password_hash()` and verified with `password_verify()`
  * UTF‑8 encoding (`utf8mb4`) for full multilingual support

* **Responsive Design**

  * Mobile‑friendly layout with CSS grids
  * SVG icons and accessible markup

---

## 🛠️ Tech Stack

* **Language:** PHP ≥ 7.4 (tested on 8.0+)
* **Database:** MySQL 5.7+ (MariaDB) or SQLite (testing)
* **Frontend:** HTML5, CSS3 (responsive, no framework)
* **Testing:** PHPUnit, in‑memory SQLite
* **Deployment:** Apache/Nginx or PHP built‑in server

---

## ⚙️ Requirements

* PHP with PDO extension
* MySQL / MariaDB (or SQLite for local tests)
* [Composer](https://getcomposer.org/)
* Web server (Apache, Nginx) or PHP’s built‑in server

---

## 🏁 Getting Started

Follow these steps to get your development environment up and running:

1. **Clone the repo**

   ```bash
   git clone https://github.com/your-vendor/newesonline.git
   cd newesonline
   ```
2. **Install dependencies**

   ```bash
   composer install
   ```
3. **Initialize the database**

   ```bash
   mysql -u root -p < database.sql
   ```

   > **Note:** The seed SQL creates a default admin (`marvyn`/`marvyn`). Immediately hash its password:

   ```bash
   php scripts/hash_admin.php
   ```
4. **Set permissions**

   ```bash
   chmod -R 775 public/uploads
   ```
5. **Start the server**

   ```bash
   php -S localhost:8000 -t public
   ```
6. **Visit in your browser**
   Open [http://localhost:8000](http://localhost:8000)

---

## 🔧 Configuration

1. **Database credentials**
   Edit `config/config.php`:

   ```php
   return [
       'db_host' => 'localhost',
       'db_name' => 'dwwm',
       'db_user' => 'root',
       'db_pass' => 'secret',
   ];
   ```
2. **Virtual host (optional)**
   Point your server’s document root to `public/`.
3. **Sessions & CSRF**
   Ensure PHP can write to its session directory (default OS temp is fine).

---

## 💻 Usage

* **Public**
  Browse and filter announcements on the home page.
* **Admin**

  1. Log in: `marvyn` / `marvyn` (then hashed)
  2. Access Admin Panel: `/index.php?controller=admin&action=index`
* **Subscriber**

  1. Register or log in
  2. Comment, rate, and manage your profile

---

## 📁 Project Structure

```
NewesOnline/
├─ config/              # Database settings & connection
│  ├─ config.php
│  └─ database.php
├─ controllers/         # MVC controllers
├─ models/              # Data-access via PDO
├─ public/              # Public assets & front controller
│  ├─ css/
│  ├─ uploads/
│  └─ index.php
├─ scripts/             # Utility scripts (e.g. hash_admin.php)
├─ tests/               # PHPUnit tests (SQLite)
├─ views/               # PHP templates (admin, auth, public, subscriber)
├─ database.sql         # Schema + seed data
├─ composer.json        # Dependencies & autoloading
├─ phpunit.xml          # PHPUnit configuration
└─ README.md            # You are here
```

---

## 🧪 Testing

Unit and integration tests are powered by PHPUnit using an in‑memory SQLite database:

1. **Install dev dependencies**

   ```bash
   composer install --dev
   ```
2. **Run tests**

   ```bash
   ./vendor/bin/phpunit
   ```

All tests live under `tests/` and adhere to PSR‑4 autoloading via Composer.

---

## 🤝 Contributing

I love contributions! Please:

1. Fork the repo
2. Create a branch: `git checkout -b feature/YourFeature`
3. Commit your changes: `git commit -m 'Add amazing feature'`
4. Push: `git push origin feature/YourFeature`
5. Open a Pull Request

Please include tests for new functionality and follow existing coding standards.

---

## 📄 License

This project is released under the **Marvyn Richer License**.
