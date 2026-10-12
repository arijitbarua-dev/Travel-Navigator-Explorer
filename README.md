# ✈️ Travel Navigator Explorer

### Explore Destinations. Plan Your Journey. Travel Smarter.

**Travel Navigator Explorer** is a web-based travel and tourism platform developed as a collaborative group project for the **Software Engineering** course at **BRAC University**. The platform aims to help travelers explore destinations, book accommodations, connect with local guides, and plan their trips more conveniently.

By bringing essential travel-planning features together, the project demonstrates the practical application of software engineering principles in building a user-focused web application.

---

## ✨ Key Features

- **🌍 Destination Exploration** — Discover travel destinations and explore places to visit.
- **🏨 Hotel & Room Booking** — Browse accommodation options and manage hotel and room bookings.
- **🧭 Travel Guide Connections** — Connect with guides to make travel experiences more convenient.
- **💰 Travel Budget Calculator** — Estimate travel expenses and plan trips according to your budget.
- **💬 Messaging & Admin Panel** — Support communication through messaging and provide administrative tools for managing platform activities.

---

## 🛠️ Technologies Used

| Technology | Purpose |
|---|---|
| **PHP** | Server-side application logic |
| **Laravel** | Backend framework and application architecture |
| **MySQL** | Relational database management |
| **JavaScript** | Frontend interactivity |
| **Vite** | Frontend asset development and bundling |
| **HTML5 & CSS3** | Web page structure and styling |
| **Composer** | PHP dependency management |
| **npm** | JavaScript dependency management |

---

## 🚀 Getting Started

Follow these steps to run the project locally.

### Prerequisites

Make sure you have installed:

- PHP
- Composer
- Node.js and npm
- MySQL
- Git

### Installation

**1. Clone the repository**

```bash
git clone https://github.com/arijitbarua-dev/Travel-Navigator-Explorer.git
```

**2. Navigate to the project directory**

```bash
cd Travel-Navigator-Explorer
```

**3. Install PHP dependencies**

```bash
composer install
```

**4. Install frontend dependencies**

```bash
npm install
```

**5. Configure environment variables**

```bash
cp .env.example .env
php artisan key:generate
```

Update the `.env` file with your local database credentials and database name.

**6. Create the database and run migrations**

Create a MySQL database, then run:

```bash
php artisan migrate --seed
```

**7. Build frontend assets**

```bash
npm run build
```

**8. Start the development server**

```bash
php artisan serve
```

Open [http://localhost:8000](http://localhost:8000) in your browser.

> **Note:** These commands are a standard Laravel setup guide. Depending on the repository configuration, you may need to adjust database settings or setup steps. The `--seed` option is only necessary if seeders are configured.

---

## 🎓 Academic Project

- **Course:** Software Engineering
- **Institution:** BRAC University
- **Project Type:** Collaborative Group Project

This project provided practical experience in software development, team collaboration, application architecture, database integration, and building a web-based solution for travel planning and tourism management.

---

## 👥 Contributors

This project was developed collaboratively by the following team members as part of the Software Engineering course at BRAC University.

| Contributor | GitHub Profile |
|---|---|
| **Arijit Barua Turza** | [@arijitbarua-dev](https://github.com/arijitbarua-dev) |
| **Anisaislam22** | [@Anisaislam22](https://github.com/Anisaislam22) |
| **rifaXOXO** | [@rifaXOXO](https://github.com/rifaXOXO) |
| **Anindya066** | [@Anindya066](https://github.com/Anindya066) |

---

## 📌 Repository

Explore the source code and project updates on GitHub:

**[Travel Navigator Explorer](https://github.com/arijitbarua-dev/Travel-Navigator-Explorer)**

---

<p align="center">
  <strong>✈️ Travel Navigator Explorer — Your Journey, Better Planned.</strong>
</p>
