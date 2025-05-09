# Todo Application

A modern, full-stack Todo application built with Laravel and Vue.js that demonstrates best practices in web development.

![Todo App Screenshot](https://via.placeholder.com/800x450.png?text=Todo+App+Screenshot)

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
└── router.ts             # Vue Router configuration
```

### Backend Structure

```
app/
├── Http/
│   ├── Controllers/      # API controllers
│   ├── Requests/         # Form request validation
│   └── Resources/        # API resources
├── Models/               # Eloquent models
└── Services/             # Business logic services
```

## Code Quality Features

-   **Type Safety**: TypeScript for frontend code
-   **Form Validation**: Zod schemas with custom error handling
-   **Reusable Components**: Component-based architecture
-   **Error Handling**: Consistent error handling patterns
-   **Responsive Design**: Mobile-first approach
-   **Clean Code**: Following best practices and coding standards

## Getting Started

### Prerequisites

-   PHP 8.1 or higher
-   Composer
-   Node.js and npm
-   MySQL

### Installation

1. Clone the repository:

```bash
git clone https://github.com/yourusername/todo-app.git
cd todo-app
```

2. Install PHP dependencies:

```bash
composer install
```

3. Install JavaScript dependencies:

```bash
npm install
```

4. Copy the environment file and configure your database:

```bash
cp .env.example .env
```

5. Generate application key:

```bash
php artisan key:generate
```

6. Run migrations:

```bash
php artisan migrate
```

7. Build frontend assets:

```bash
npm run dev
```

8. Start the development server:

```bash
php artisan serve
```

9. Visit `http://localhost:8000` in your browser

## Development Workflow

### Running Tests

```bash
php artisan test       # Run PHP tests
npm run test           # Run JavaScript tests
```

### Building for Production

```bash
npm run build
```

## Design Decisions

### Form Validation

The application uses Zod for form validation with a reusable error handling approach. The `setupFormErrorClearer` utility function provides dynamic error clearing as users type, improving the user experience.

### Component Structure

Components follow a consistent naming convention with the "Component" suffix, making the codebase more maintainable and easier to navigate.

### State Management

The application uses reactive state management with Vue's Composition API, providing a clean and efficient way to handle component state.

### API Integration

API calls are abstracted through service layers, making the code more maintainable and testable.

## Future Enhancements

-   Task categories/tags
-   Due dates and reminders
-   User profile management
-   Dark mode support
-   Collaborative todo lists
-   Mobile application

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Author

Created by [Your Name] as a demonstration of modern web development practices.
