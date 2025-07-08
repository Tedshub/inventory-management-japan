
<p align="center">
  <a href="https://laravel.com" target="_blank">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  </a>
</p>

<p align="center">
  <a href="https://github.com/laravel/framework/actions">
    <img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version">
  </a>
  <a href="https://packagist.org/packages/laravel/framework">
    <img src="https://img.shields.io/packagist/l/laravel/framework" alt="License">
  </a>
</p>

---

# Inventory Management Japan 🇯🇵

**Inventory Management Japan** is a modern inventory management application built with Laravel, Inertia.js, and Vue 3, featuring a Japanese user interface and Excel import/export support. This system is suitable for small to medium-scale inventory tracking and stock management.

## ✨ Key Features

- ✅ CRUD for Items
- ✅ Import & Export Excel Files
- ✅ Interactive Table using `VueGoodTable`
- ✅ Multi-Select & Bulk Delete
- ✅ Flash Notification Support
- ✅ Japanese UI Localization
- ✅ Responsive & Mobile Friendly Design

## 🧰 Tech Stack

- **Laravel 10+**
- **Jetstream + Inertia.js**
- **Vue 3 + Composition API**
- **VueGoodTable**
- **Tailwind CSS**
- **Laravel Excel** (`maatwebsite/excel`)

## 📦 Installation

1. Clone this repository:
   ```bash
   git clone https://github.com/Tedshub/inventory-management-japan.git
   cd inventory-management-japan
   ```

2. Install backend dependencies:
   ```bash
   composer install
   ```

3. Install frontend dependencies:
   ```bash
   npm install && npm run dev
   ```

4. Setup environment file:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. Configure your database in `.env`, then run:
   ```bash
   php artisan migrate
   ```

6. Start the local server:
   ```bash
   php artisan serve
   ```


## 📁 Key Directory Structure

```
resources/
  └── js/
      └── Pages/
          └── Barang/
              ├── Index.vue
              ├── Create.vue
              └── Edit.vue
```

## ✅ Roadmap

- [x] Japanese UI
- [x] Excel Import/Export
- [ ] Role & Permission System
- [ ] PDF Reports
- [ ] Advanced Filtering & Sorting

## 🤝 Contribution

Contributions are welcome! Please open an **Issue** or submit a **Pull Request**. Follow Laravel and Vue 3 best practices.

## 📄 License

This project is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).

---

<p align="center">
  Thank you for using <strong>Inventory Management Japan</strong> 🇯🇵<br>
  Built with ❤️ by <a href="https://github.com/Tedshub" target="_blank">Tedshub</a>
</p>
