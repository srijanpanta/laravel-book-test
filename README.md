<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="#"><img src="https://github.com/yourusername/laravel-book-test/workflows/tests/badge.svg" alt="Build Status"></a>
  <a href="#"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
  <a href="#"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
  <a href="#"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# BookApp - Laravel + Inertia.js One-Page Application

## About BookApp

BookApp is a one-page web application built with **Laravel 12**, **Inertia.js**, **Vue 3**, and **Tailwind CSS**.  
It allows users to browse, search, filter, add, edit, and delete books in a clean and responsive interface. Authenticated users can manage books while guests can explore the collection.

---

## Features

- Display books with title, author, description, tags, and images.
- Search and filter books by title, author, or tags.
- Add, edit, and delete books (authentication required).
- Responsive design for mobile, tablet, and desktop.
- User authentication: login, register, logout, profile management.
- Smooth modals and transitions for book management.
- Pagination for large book lists.
- Unit tests for BookController API operations.

---

## Technology Stack

- **Backend:** Laravel 12
- **Frontend:** Inertia.js + Vue 3
- **Styling:** Tailwind CSS
- **Database:** MySQL
- **File Storage:** Laravel Storage (`storage/app/public/images`)
- **Testing:** PHPUnit (unit tests for BookController)

---

## Installation

1. Clone the repository:

```bash
git clone https://github.com/srijanpanta/laravel-book-test.git
cd laravel-book-test
```

2. Install PHP dependencies via Composer:

```bash
composer install
npm install
```

3. Setup Environment Variables

```bash
cp .env.example .env
php artisan key:generate
```

Configure Database by updating `.env` file:

```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_book_test
DB_USERNAME=root
DB_PASSWORD=
```

4. Run Migrations and Seeders

```bash
php artisan migrate --seed
```

5. Build Front-end Assets

```bash
npm run build
npm run dev
```

6. Start the Development Server

```bash
php artisan serve 
```

7. Access the Application
Visit http://localhost:8000 to access the application.

##  Database Schema
| Column      | Type      | Description          |
| ----------- | --------- | -------------------- |
| id          | bigint    | Primary Key          |
| title       | string    | Book title           |
| author      | string    | Author name          |
| description | text      | Book description     |
| tags        | JSON      | Array of tags        |
| image       | string    | Book cover filename  |
| created_at  | timestamp | Record creation time |
| updated_at  | timestamp | Record update time   |

##  API Endpoints
1.  GET /books - List books (pagination & search supported)
2.  GET /books/{book} - Show a specific book
3.  POST /books - Create a new book (authenticated)
4.  PUT /books/{book} - Update an existing book (authenticated)
5.  DELETE /books/{book} - Delete a book (authenticated)

##   Unit Testing
```bash
php artisan test --filter=BookControllerUnitTest
```
This will run the unit tests for the BookController API operations. It covers
1. Creating a book
2. Updating a book
3. Deleting a book
4. Showing a book

#   Frontend Notes
1.  SPA-like experience using Inertia.js and Vue 3.
2.  Responsive Tailwind CSS design.
3.  Modals for book viewing, creating, and editing.
4.  Smooth scrolling to book section on search or pagination.
5.  Profile dropdown and logout for authenticated users.

# Author
Srijan Panta

GitHub: github.com/SrijanPanta

# License
This project is for educational purposes and part of a technical test.

