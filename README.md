# Yii2 Logger Module

A simple logging module for Yii2 framework that supports multiple logging types: email, file, and database logging.
Inspired by test task.

## Requirements

- PHP 8.1 or higher
- Yii2 Framework 2.0.49
- MySQL 8.0 or higher
- Redis (for queue functionality)
- Composer

## Installation

1. Clone the repository:
```bash
git clone https://github.com/deadbyback/yii2-logger-module-example
cd yii2-logger-module-example
```

2. Install dependencies:
```bash
composer install
```

3. Create database:
```sql
CREATE DATABASE yii2_logger CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

4. Configure your database connection in `config/db.php`:
```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2_logger',
    'username' => 'your_username',
    'password' => 'your_password',
    'charset' => 'utf8mb4',
];
```

5. Apply migrations:
```bash
php yii migrate/up --migrationPath=@app/modules/logger/migrations
```

6. Configure your mail settings in `config/params.php`:
```php
return [
    'adminEmail' => 'admin@example.com',
];
```

## Module Configuration

Add the logger module to your application configuration in `config/web.php` and `config/console.php`:

```php
'modules' => [
    'logger' => [
        'class' => 'app\modules\logger\Module',
        'defaultLogger' => 'file', // Default logger type
    ],
],
```

## Usage

### Console Commands

1. Log using default logger:
```bash
php yii logger/log
```

2. Log using specific logger type:
```bash
php yii logger/log-to email
php yii logger/log-to file
php yii logger/log-to database
```

3. Log to all available loggers:
```bash
php yii logger/log-to-all
```

### Available Logger Types

- `email`: Sends log messages via email
- `file`: Writes log messages to a file
- `database`: Stores log messages in the database

### Log File Location

File logs are stored in:
```
runtime/logs/app.log
```

### Database Logs

Logs are stored in the `log` table with the following structure:
- `id`: Primary key
- `message`: Log message
- `created_at`: Timestamp
- `level`: Log level

## Queue Worker (for Database Logger)

To process database logs asynchronously, run:
```bash
php yii queue/listen
```

## Testing

1. Create test database:
```sql
CREATE DATABASE yii2_logger_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Configure test database in `config/test_db.php`

3. Run tests:
```bash
./vendor/bin/phpunit
```

## Docker Support

1. Build and start containers:
```bash
docker-compose up -d
```

2. Install dependencies:
```bash
docker-compose exec php composer install
```

3. Apply migrations:
```bash
docker-compose exec php php yii migrate/up --migrationPath=@app/modules/logger/migrations
```

## Project Structure

```
modules/
└── logger/
    ├── components/     # Core components
    ├── enums/          # Enums
    ├── exceptions/     # Exceptions for loggers
    ├── factories/      # Factories for logger creation
    ├── interfaces/     # Interfaces
    ├── jobs/           # Jobs for asynchronous tasks
    ├── loggers/        # Logger implementations
    ├── migrations/     # Database migrations
    ├── models/         # Models
    ├── services/       # Generators, helpers, etc.
    └── Module.php      # Module configuration
```
