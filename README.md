# Task Management Application

A simple, robust task management application built with **Laravel 11**, **PHP 8.3**, **MySQL**, and an interactive frontend powered by **Blade**, **Bootstrap 5**, **jQuery**, and **SortableJS**.

The application allows users to seamlessly create, edit, delete, filter, and dynamically reorder tasks. Task priority is automatically recalculated on the backend based on the drag-and-drop arrangement on the frontend.

---

## 🚀 Features

### 📋 Task Operations
* **Create Tasks** – Add new tasks instantly with clean UI forms.
* **Edit Tasks** – Modify task titles and reassign project categories on the fly.
* **Delete Tasks** – Safely remove tasks with correct HTTP response lifecycles.

### 🔄 Dynamic Ordering & Context
* **Drag-and-Drop Reordering** – Move tasks visually using a seamless frontend interface.
* **Automatic Priority Management** – Order changes trigger instant, backend-recalculated priority mapping.
* **Database Transactions** – Bulk priority updates run inside strict database transactions for optimal data integrity.
* **Project Isolation** – Assign tasks to specific projects and filter lists instantly. Drag-and-drop actions are strictly scoped to the active project context.

### 🛡️ Architecture & Security
* **Server-Side Validation** – Robust Laravel Form Request validation safeguards incoming payloads.
* **Pest Test Suite** – Fully covered by feature tests ensuring regression-free changes.

---

## 🛠️ Tech Stack

### Backend
* [PHP 8.3+](https://www.php.net/)
* [Laravel 11.x](https://laravel.com/)
* [MySQL 8.0+](https://www.mysql.com/)
* Eloquent ORM & Laravel Form Requests

### Frontend
* [Blade Templates](https://laravel.com/docs/11.x/blade)
* [Bootstrap 5](https://getbootstrap.com/)
* [jQuery](https://jquery.com/)
* [SortableJS](https://sortablejs.github.io/Sortable/)
* [Vite Assets Bundler](https://vite.dev/)

### Testing
* [Pest Testing Framework](https://pestphp.com/) (Laravel Feature Tests)

---

## 📂 Project Structure

```text
app/
├── Http/
│   ├── Controllers/
│   │   └── TaskController.php       # Handles core task operations & transaction reordering
│   └── Requests/
│       ├── StoreTaskRequest.php     # Validates incoming task payloads
│       └── UpdateTaskRequest.php    # Validates updates and modifications
└── Models/
    ├── Project.php                  # One-To-Many: Project has many Tasks
    └── Task.php                     # BelongsTo Project relationship code

database/
├── factories/                       # Blueprint models for seeding & Pest testing
├── migrations/                      # Tables setup for projects & sequenced tasks
└── seeders/                         # Dummy data for zero-config evaluation

resources/
├── css/
│   └── app.css
├── js/
│   ├── app.js                       # Entry point bundling Bootstrap & global modules
│   └── tasks.js                     # Implements SortableJS & AJAX payload sync
└── views/
    ├── layouts/
    │   └── app.blade.php            # Primary structural template shell
    └── tasks/
        ├── index.blade.php          # Main dashboard view
        ├── edit.blade.php           # Focused editing interface
        └── partials/
            ├── task-row.blade.php   # Reusable table line items for drag-and-drop
            └── create-form.blade.php
```

---

## ⚙️ Technical Approach & Design Choices

### Core Philosophy
The codebase strictly follows official Laravel style conventions and leverages integrated framework ecosystems instead of packing arbitrary architectural abstractions (such as repositories or service layers). It leans heavily on native Eloquent relationships, **Route Model Binding**, Form Requests, and Database Transactions.

### Priority Realignment Flow
Priority calculations are strictly server-controlled to prevent client-side data contamination or race conditions:
1. **SortableJS** triggers a drop action in the browser.
2. **jQuery AJAX** intercepts the mutation and passes serialized structural element IDs back to the controller.
3. The backend validates structural array IDs within a scoped **Database Transaction**.
4. Loop iterations sequentially rewrite structural indexes from `1` to `N` keeping operations clean and atomic.

```text
Visual Move:          Backend Transaction Sync:
[ Task C ]   ───┐     Task C ➔ Priority 1
[ Task A ]      │     Task A ➔ Priority 2
[ Task D ]   ───┼─➔   Task D ➔ Priority 3
[ Task B ]   ───┘     Task B ➔ Priority 4
```

---

## 💻 Installation & Local Setup

### 1. System Requirements
Ensure your workspace includes:
* **Composer**
* **Node.js & NPM**
* Active **MySQL** Service

### 2. Clone & Setup Environments
```bash
# Clone the repository
git clone <repository-url>
cd task-manager

# Install vendor dependencies
composer install
npm install
```

### 3. Application Configurations
```bash
# Set up environment workspace config
cp .env.example .env

# Generate encryption token key
php artisan key:generate
```

### 4. Database Initialization
Modify your local `.env` settings to target your MySQL instance:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=YOUR_PASSWORD_HERE
```

Apply schemas and generate seeded sample rows:
```bash
php artisan migrate --seed
```

### 5. Fire Up the Servers
Launch your local PHP instance:
```bash
php artisan serve
```
In a secondary terminal tab, spin up the Vite compiler:
```bash
npm run dev
```
Open your web browser and view the app at: **`http://127.0.0.1:8000`**

---

## 🚀 Production Optimizations

To launch securely on production nodes, build cached artifacts and run isolated environment triggers:

```bash
# Production installation routines
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Fast execution caching
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Secure schema adjustment without interactive inputs
php artisan migrate --force
```

---

## 🧪 Running the Test Suite

This application enforces feature integrity through a highly readable **Pest** suite. 

```bash
# Execute the entire suite
php artisan test

# Alternatively, run via Pest directly
./vendor/bin/pest

# Run localized Task validation flows exclusively
./vendor/bin/pest tests/Feature/TaskManagementTest.php
```

### Coverage Assertions
* Task Creation & Validation rulesets
* Task Index Listing and Project filter queries
* Soft or complete data mutations (Update/Delete lifecycles)
* Dynamic order preservation loops inside transactions

---

## 📝 License
This project is open-source software licensed under the [MIT License](LICENSE).