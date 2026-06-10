# FitZone Fitness - Gym Management Web App

FitZone Fitness is a comprehensive web-based gym and fitness management application built to help fitness centers manage classes, trainer schedules, member bookings, blog posts, and user queries.

## 🌟 Key Features

* **User Authentication & Role-Based Access Control:** Separate roles for `customer`, `staff`, and `admin`.
* **Class Scheduling & Bookings:** Customers can browse gym classes and schedule/book sessions.
* **Trainer Profiles:** Displays certified trainer bios and specializations.
* **Membership Plans:** Details premium gym subscriptions and feature lists.
* **Fitness Blog:** Share articles about workouts, nutrition, and success stories.
* **Member Feedback & Queries:** Interface for potential members to send queries to gym staff.

## 🛠️ Technology Stack

* **Frontend:** HTML5, CSS3, Vanilla JS
* **Backend:** PHP (OOP & PDO)
* **Database:** MySQL / MariaDB

## 📦 Setup & Installation

1. **Database Import:**

   * Create a MySQL database named `fitzone_db`.
   * Import the schema and sample data using the provided [db.sql](db.sql) file:
     ```bash
     mysql -u root -p fitzone_db < db.sql
     ```
2. **Configuration:**

   * Edit [config.php](config.php) to update your database host, database name, username, and password credentials:
     ```php
     $DB_HOST = 'localhost';
     $DB_NAME = 'fitzone_db';
     $DB_USER = 'root';
     $DB_PASS = ''; // Your MySQL password
     ```
3. **Deploy to local server:**

   * Place the project folder in your local web server root directory (e.g. `htdocs` in XAMPP, `www` in WampServer, or using Laragon).
   * Open your browser and navigate to `http://localhost/fitzone_fitness`.

## 📷 Screenshots

### Application Preview

|                                        |                                                  |
| :------------------------------------: | :-----------------------------------------------: |
|      **Home Page / Banner**      |           **Classes & Bookings**           |
| ![Home Page](screenshots/image%2020.png) |  ![Classes & Bookings](screenshots/image%2021.png)  |
|      **Certified Trainers**      |                 **Classes**                 |
| ![Trainers](screenshots/image%2022.png) |   ![Membership Plans](screenshots/image%2023.png)   |
|       **Membership Plans**       |               **Admin Dashboard**               |
|   ![Blog](screenshots/image%2024.png)   | ![Login / Contact Form](screenshots/image%2025.png) |

## 📄 License

This project is licensed under the [MIT License](LICENSE).
