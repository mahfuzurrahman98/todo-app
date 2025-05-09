# Laravel Vue Todo App

## Live Demo
**Deployed at:** [Todo App](https://ghostwhite-partridge-578659.hostingersite.com)

### Demo Login
- Email: mahfuz@test.com
- Password: Pass@123

## Overview
A modern Todo application built with Laravel and Vue.js, featuring secure authentication and comprehensive task management.

## Key Features
- User Authentication with Laravel Sanctum
- Todo CRUD Operations
- Form Validation with Zod
- Responsive UI with Tailwind CSS
- Real-time Statistics

## Tech Stack
### Backend
- Laravel 12
- MySQL
- Laravel Sanctum
- PHPUnit

### Frontend
- Vue.js 3
- TypeScript
- Tailwind CSS
- Vue Router
- Zod

## Quick Start
1. Clone and install:
```bash
composer install
npm install
```

2. Setup environment:
```bash
cp .env.example .env
php artisan key:generate
```

3. Configure database in `.env`

4. Prepare database (manual user entry):
```bash
php artisan migrate --seed
```

5. Start development:
```bash
php artisan serve
npm run dev
```

## Project Overview

This Todo application is a Single Page Application (SPA) that allows users to create, read, update, and delete tasks. It features a clean, responsive UI built with Vue.js and Tailwind CSS on the frontend, with a robust Laravel backend providing RESTful API services.

### Key Features

-   **User Authentication**: Secure login system with validation
-   **Todo Management**: Create, view, edit, and delete todos
-   **Status Filtering**: Filter todos by completion status (All, Completed, Pending)
-   **Sorting Options**: Sort todos by creation date or title
-   **Form Validation**: Client-side validation using Zod schema validation
-   **Responsive Design**: Mobile-friendly interface using Tailwind CSS
-   **Statistics**: View summary statistics of todo items

## Technology Stack

### Frontend

-   **Vue.js 3**: Progressive JavaScript framework with Composition API
-   **TypeScript**: For type safety and better developer experience
-   **Tailwind CSS**: Utility-first CSS framework for responsive design
-   **Zod**: TypeScript-first schema validation
-   **Vue Router**: Official router for Vue.js
-   **Lucide Icons**: Beautiful, consistent icon set

### Backend

-   **Laravel 10**: PHP framework for web applications
-   **MySQL**: Relational database for data storage
-   **Laravel Sanctum**: For API token authentication
-   **Eloquent ORM**: Object-relational mapper for database interactions

## Project Structure

The application follows a modular architecture with clear separation of concerns:

### Frontend Structure

```
resources/js/
├── components/           # Reusable Vue components
│   ├── todo/             # Todo-specific components
│   └── ui/               # UI components (buttons, inputs, etc.)
├── pages/                # Page components
├── schemas/              # Zod validation schemas
├── services/             # API service layer
├── stores/               # State management
├── utils/                # Utility functions
│   ├── enums/            # Enum definitions
│   ├── interfaces/       # TypeScript interfaces
│   └── form-utils.ts     # Form handling utilities
└── router                # Vue Router configuration
```

### Backend Structure

```
app/
├── Http/
│   ├── Controllers/      # API controllers
│   ├── Requests/         # Form request validation
│   └── Resources/        # API resources
```bash
composer install
npm install
```

2. Setup environment:
```bash
cp .env.example .env
php artisan key:generate
```

3. Configure database in `.env`

4. Prepare database:
```bash
php artisan migrate --seed
```

5. Start development:
```bash
php artisan serve
npm run dev
```

## Testing
```bash
# Backend tests
php artisan test

# Frontend tests
npm run test
npm run build
```

## Design Decisions

### Form Validation

The application uses Zod for form validation with a reusable error handling approach. The `setupFormErrorClearer` utility function provides dynamic error clearing as users type, improving the user experience.

### Component Structure

Components follow a consistent naming convention with the "Component" suffix, making the codebase more maintainable and easier to navigate.

### State Management

The application uses reactive state management with Vue's Composition API, and Pinia library providing a clean and efficient way to handle component state.

### API Integration

API calls are abstracted through service layers, making the code more maintainable and testable.