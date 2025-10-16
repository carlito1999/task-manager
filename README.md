# 📝 Task Manager with Team Collaboration

A modern **Laravel-based task management system** with real-time Kanban boards, team collaboration features, and a sleek user interface built with Tailwind CSS and Alpine.js.

<p align="center">
<a href="https://github.com/carlito1999/task-manager"><img src="https://img.shields.io/github/stars/carlito1999/task-manager" alt="GitHub Stars"></a>
<a href="https://github.com/carlito1999/task-manager"><img src="https://img.shields.io/github/forks/carlito1999/task-manager" alt="GitHub Forks"></a>
<a href="https://github.com/carlito1999/task-manager/blob/main/LICENSE"><img src="https://img.shields.io/github/license/carlito1999/task-manager" alt="License"></a>
<a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-12.x-red.svg" alt="Laravel Version"></a>
</p>

---

## 🎯 Overview

This task management system enables teams to create projects, manage tasks through an intuitive Kanban interface, and collaborate effectively with real-time updates, file attachments, and comprehensive project tracking.

### ✨ Key Features

- **🎨 Kanban Board Interface** - Drag-and-drop task management with visual status columns
- **👥 Team Collaboration** - Invite members with role-based permissions (Owner/Member/Viewer)
- **📊 Project Dashboard** - Real-time progress tracking and statistics
- **📝 Task Management** - Comprehensive CRUD with priorities, due dates, and assignments
- **💬 Comments System** - Task-specific discussions and updates
- **📎 File Attachments** - Upload and manage task-related files
- **🔄 Subtasks** - Break down complex tasks into manageable pieces
- **🔔 Email Notifications** - Automated alerts for task assignments and status changes
- **📱 Responsive Design** - Works seamlessly on desktop and mobile devices

---

## 🚀 Tech Stack

### Backend
- **Laravel 12.x** - PHP framework with elegant syntax
- **MySQL/SQLite** - Reliable database storage
- **Laravel Breeze** - Simple authentication scaffolding
- **Eloquent ORM** - Elegant database relationships

### Frontend
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Minimal JavaScript framework
- **SortableJS** - Smooth drag-and-drop functionality
- **Blade Templates** - Laravel's powerful templating engine

### Development Tools
- **Vite** - Lightning-fast build tool
- **Laravel Pint** - Code style fixer
- **PHPUnit** - Testing framework

---

## 📋 Database Schema

```sql
users
├── id, name, email, avatar, timestamps

projects
├── id, user_id (creator), name, description
├── status (active/completed/archived)
├── deadline, timestamps

project_user (pivot)
├── project_id, user_id
├── role (owner/member/viewer), timestamps

tasks
├── id, project_id, assigned_to (nullable)
├── title, description, status (todo/in_progress/done)
├── priority (low/medium/high), due_date, timestamps

comments
├── id, task_id, user_id, content, timestamps

attachments
├── id, task_id, user_id, filename, path
├── mime_type, size, timestamps

subtasks
├── id, task_id, title, description
├── status, priority, assigned_to, due_date
├── sort_order, timestamps
```

---

## 🛠️ Installation & Setup

### Prerequisites

- **PHP 8.2+**
- **Composer** - Dependency manager for PHP
- **Node.js & NPM** - For frontend asset compilation
- **MySQL/SQLite** - Database

### Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/carlito1999/task-manager.git
   cd task-manager
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database configuration**
   ```bash
   # For SQLite (recommended for development)
   touch database/database.sqlite
   
   # Update .env file
   DB_CONNECTION=sqlite
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

Visit `http://localhost:8000` to access the application.

### Development Environment

For active development with hot reloading:

```bash
# Terminal 1: Laravel server
php artisan serve

# Terminal 2: Frontend watcher
npm run dev

# Or use the convenient development script
composer run dev
```

---

## 🎨 Features Showcase

### 📊 Kanban Board
- **Drag & Drop**: Move tasks between Todo, In Progress, and Done columns
- **Real-time Updates**: AJAX-powered status changes without page reload
- **Visual Feedback**: Smooth animations and loading states
- **Quick Actions**: One-click task status updates
- **Priority Indicators**: Color-coded task priorities

### 👥 Project Management
- **Team Collaboration**: Invite users with specific roles
- **Progress Tracking**: Visual progress bars and completion percentages
- **Role-based Access**: Owner, Member, and Viewer permissions
- **Project Statistics**: Task counts, team size, and deadline tracking

### 📝 Task Features
- **Rich Task Details**: Title, description, priority, and due dates
- **User Assignment**: Assign tasks to specific team members
- **File Attachments**: Upload documents, images, and other files
- **Subtask Management**: Break complex tasks into smaller pieces
- **Comment System**: Task-specific discussions and updates

### 🔔 Notifications
- **Email Alerts**: Automated notifications for task assignments
- **Status Updates**: Notifications when task status changes
- **Project Invitations**: Email invites for new team members

---

## 🌟 Usage Guide

### Creating Your First Project

1. **Register/Login** to your account
2. **Click "Create Project"** from the dashboard
3. **Fill in project details** (name, description, deadline)
4. **Invite team members** by email with appropriate roles
5. **Start adding tasks** using the Kanban board

### Managing Tasks

1. **Add New Task**: Click the "+" button in any Kanban column
2. **Set Details**: Add title, description, priority, and due date
3. **Assign Member**: Choose a team member to work on the task
4. **Track Progress**: Drag tasks between columns or use quick action buttons
5. **Add Attachments**: Upload relevant files and documents
6. **Collaborate**: Use comments for task-specific discussions

### Team Collaboration

- **Owner**: Full control over project and team management
- **Member**: Can create, edit, and complete tasks
- **Viewer**: Read-only access to project and tasks

---

## 🧪 Testing

Run the test suite:

```bash
# Run all tests
php artisan test

# Run specific test files
php artisan test --filter=ProjectTest

# Generate coverage report
php artisan test --coverage
```

---

## 📂 Project Structure

```
app/
├── Http/Controllers/     # Request handling logic
├── Models/              # Eloquent models and relationships
├── Mail/                # Email notification classes
└── Providers/           # Service providers

database/
├── migrations/          # Database schema definitions
├── seeders/            # Sample data generators
└── factories/          # Model factories for testing

resources/
├── views/              # Blade templates
├── css/                # Tailwind CSS styles
└── js/                 # Alpine.js components

routes/
├── web.php             # Web routes definition
└── auth.php            # Authentication routes

tests/
├── Feature/            # Integration tests
└── Unit/               # Unit tests
```

---

## 🤝 Contributing

We welcome contributions! Please follow these steps:

1. **Fork the repository**
2. **Create a feature branch** (`git checkout -b feature/amazing-feature`)
3. **Commit your changes** (`git commit -m 'Add amazing feature'`)
4. **Push to the branch** (`git push origin feature/amazing-feature`)
5. **Open a Pull Request**

### Development Guidelines

- Follow **PSR-12** coding standards
- Write **tests** for new features
- Update **documentation** as needed
- Use **conventional commits** for commit messages

---

## 📜 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 🙏 Acknowledgments

- **Laravel Team** - For the amazing framework
- **Tailwind CSS** - For the utility-first CSS framework
- **Alpine.js** - For the lightweight JavaScript framework
- **SortableJS** - For the drag-and-drop functionality

---

## 📞 Support

If you have any questions or need help, please:

- **Open an issue** on GitHub
- **Check the documentation** in the `/docs` folder
- **Review existing issues** for similar problems

Built with ❤️ using Laravel and modern web technologies.
