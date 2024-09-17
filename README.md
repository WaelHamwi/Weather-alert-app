🌦️ Weather Alert Application

## Project Overview

The Weather Alert Application is a Laravel-based web app designed to provide users with real-time weather updates, alerts, and subscription management. It integrates with a weather API and uses Laravel Cashier for handling subscriptions and Stripe payments. This document outlines the setup, features, and usage of the application.

## 1. Project Setup

### Prerequisites

-   PHP >= 8.0
-   Composer
-   Node.js and npm

### Installation

1. **Create a New Laravel Project:**

    ```bash
    composer create-project --prefer-dist laravel/laravel weather-alert-app
    cd weather-alert-app
    php artisan key:generate
    ```

2. **Set Up Environment Variables:**

    - Add your API keys and base URL to your `.env` file:
        ```env
        WEATHER_API_KEY=your_api_key_here
        WEATHER_API_URL=https://api.openweathermap.org/data/2.5
        STRIPE_KEY=your-stripe-public-key
        STRIPE_SECRET=your-stripe-secret-key
        ```

3. **Install Required Packages:**

    ```bash
    composer require laravel/cashier
    npm install alpinejs tailwindcss postcss autoprefixer
    ```

4. **Initialize TailwindCSS:**
    ```bash
    npx tailwindcss init
    ```

### Database Design

1. **Create Models and Migrations:**

    ```bash
    php artisan make:model Location -m
    php artisan make:model WeatherAlert -m
    ```

2. **Run Migrations and Seeders:**
    ```bash
    php artisan migrate
    php artisan make:seeder UserSeeder
    php artisan make:seeder LocationSeeder
    php artisan make:seeder WeatherAlertSeeder
    php artisan db:seed --class=UserSeeder
    php artisan db:seed --class=LocationSeeder
    php artisan db:seed --class=WeatherAlertSeeder
    or seed them all: php artisan db:seed
    ```

### API Consumption

1. **Integrate with Weather API:**

    - Create a service class to interact with the weather API.
    - Example API: [OpenWeatherMap](https://home.openweathermap.org/)

2. **Set Up API Configuration:**
    ```bash
    php artisan make:controller WeatherController
    php artisan make:controller Auth/LoginController
    ```

### Stripe Integration with Laravel Cashier

1. **Install Laravel Cashier:**

    ```bash
    composer require laravel/cashier
    php artisan vendor:publish --tag="cashier-config"
    ```

2. **Create Subscription Controller and Migrations:**

    ```bash
    php artisan make:controller SubscriptionController
    php artisan make:migration add_subscription_columns_to_users_table --table=users
    php artisan make:controller WebhookController
    ```

3. **Set Up Webhooks:**

    - Configure Stripe webhooks to handle events.

4. **Stripe Webhook Setup:**

## Steps to Configure Stripe Webhooks

### 1. Download Stripe CLI

Follow the instructions to download the Stripe CLI from [Stripe Documentation](https://stripe.com/docs/stripe-cli).

-   **Extract** the compressed file and set it up in your environment.

### 2. Configure Webhook Endpoint

-   **Go to your Stripe Dashboard:**
    -   Navigate to **Developers > Webhooks**.
    -   **Add a new endpoint**: `http://localhost:8000/stripe/webhook`.

### 3. Exclude Webhook Route from CSRF Protection

Webhooks come from external services and do not have CSRF tokens. To handle this:

-   **In Laravel, update your `VerifyCsrfToken.php`:**

    ```php
    protected $except = [
        '/stripe/webhook',
    ];
    Add it to your .env file:
    STRIPE_WEBHOOK_SECRET=whsec_...
    ```

### Subscription Restrictions

1. **Implement Middleware:**
    ```bash
    php artisan make:middleware CheckSubscription
    ```

### Queue Implementation

1.  **Set Up Queues:**

        ```bash
        php artisan queue:table
        php artisan migrate
        php artisan make:job CheckWeatherForUsers
        php artisan make:job TrialExpirationReminder
        php artisan make:notification TrialExpirationNotification
        php artisan make:controller NotificationController
        ```
        use the database driver In .env: QUEUE_CONNECTION=database

2.  **Start the Queue Worker:**
    ```bash
    php artisan queue:work
    php artisan queue:restart
    php artisan schedule:run
    php artisan make:mail WeatherUpdateMail
    ```

# 🚀 Start the queue worker and related tasks

php artisan queue:work && php artisan queue:restart && php artisan schedule:run && php artisan make:mail WeatherUpdateMail

# ✉️ Mail service setup (manual steps):

# 1️⃣ Go to your Manage Account -> Security -> App Passwords.

# 2️⃣ 🔐 Enable 2-step verification if it's not already enabled.

# 3️⃣ 📝 Create an app password, name it something like "weather-alert-app", and copy the password.

        sending emails with laravel:
            MAIL_MAILER=smtp
            MAIL_HOST=smtp.gmail.com
            MAIL_PORT=587
            MAIL_USERNAME=ex:waellhamwii@gmail.com
            MAIL_PASSWORD=yourpassword
            MAIL_ENCRYPTION=tls
            MAIL_FROM_ADDRESS=ex:waellhamwii@gmail.com
            MAIL_FROM_NAME="${APP_NAME}"

    we can test it through the kernel console:
    ```php artisan schedule:run```

### Notification System

1. **Create Notifications:**

    ```bash
    php artisan make:notification RainForecastNotification
    php artisan make:command CheckRainForecast
    php artisan make:notification SevereWeatherAlertNotification
    ```

2. **Test Notifications:**
    ```bash
    php artisan test --filter CheckRainForecastTest
    php artisan test --filter SevereWeatherAlertNotificationTest
    ```

### Frontend Development

1.  **Install Livewire and Alpine.js:**

    ```bash
    composer require livewire/livewire
    npm install alpinejs
    ```

2.  **Create Livewire Components:**

    ```bash
    php artisan make:livewire LocationManagerComponent
    php artisan make:livewire SubscriptionCheckTrial
    php artisan make:livewire SubscriptionCheckout
    php artisan make:livewire SubscriptionRenew
    php artisan make:livewire SubscriptionCancel
    php artisan make:livewire CustomerPortal
    ```

3.  **Configure TailwindCSS:**

    ```bash
    npm install laravel-mix --save-dev
    npx tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --watch
    ```

4.  **Contact Us Section:**

        ```bash
        php artisan make:controller ContactController
        ```

        - Ensure `.env` either file is configured with mail settings:
            ```env
            MAIL_MAILER=smtp
            MAIL_HOST=smtp.gmail.com
            MAIL_PORT=587
            MAIL_USERNAME=yourname@gmail.com
            MAIL_PASSWORD=yourpassword
            MAIL_ENCRYPTION=tls
            MAIL_FROM_ADDRESS=yourname@gmail.com
            MAIL_FROM_NAME="${APP_NAME}"

    ```

    ```

## Testing

1. **Run Unit and Feature Tests:**

    ```bash
    php artisan make:test UserTest --unit
    php artisan test
    ```

2. **Check Test Coverage:**
    ```bash
    php artisan test --coverage-html=coverage
    ```

## Notes

-   **general:** Firstly, I used a CDN, which is not the best practice. It is generally better to install the full package locally to ensure stability in case of updates. The validation implemented in the project could be improved for enhanced security, but this requires additional time. Typically, I apply design patterns for controllers to ensure a more structured and maintainable process, though this was not fully implemented here due to the project's scope.
-   **Database:** I used the subscribtion in the same table with the users cause it is a small business.
-   **Design Patterns:** In this mini project, design patterns were not used for the controllers as the scope was limited.
-   **Database Seeder Order:** Ensure seeders are run in the correct order to avoid foreign key issues.
-   **Queue Issues:** Use `php artisan queue:restart` to handle any queue worker issues.

```

```

#!/bin/bash

# 🎉 Frontend Setup Script 🎉

# 🚀 Install Livewire

composer require livewire/livewire

# 🛠️ Create Livewire component for managing locations

php artisan make:livewire LocationManagerComponent

# ⚙️ Install Alpine.js

npm install alpinejs

# 🔧 Create Livewire components for subscription management

php artisan make:livewire SubscriptionCheckTrial
php artisan make:livewire SubscriptionCheckout
php artisan make:livewire SubscriptionRenew
php artisan make:livewire SubscriptionCancel
php artisan make:livewire CustomerPortal

# 🎨 Start Tailwind CSS setup

npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init

# 🛠️ Initialize npm and install Laravel Mix

npm init -y
npm install laravel-mix --save-dev
npm install tailwindcss --save-dev

# 📜 Generate Tailwind CSS output file and watch for changes

npx tailwindcss -i ./resources/css/app.css -o ./public/css/app.css --watch

#!/bin/bash

#!/bin/bash

# 🧪 Testing Results

# 📋 Unit Tests
# Write unit tests for your models and services.
# Mock external API calls to ensure reliability.
# Aim for at least 70% code coverage.

# 🧑‍🔬 Test the User Model
php artisan make:test UserTest --unit
php artisan test
# Expected Output:
# PASS Tests\Unit\UserTest
# ✓ Test user model functionality 0.01s
# Tests: 5 passed (10 assertions)
# Duration: 0.20s

# 🌤️ Web Service Test
php artisan make:test WeatherServiceTest --unit
php artisan test --filter WeatherServiceTest
php artisan test --filter it_gets_severe_weather_alerts
# Expected Output:
# PASS Tests\Unit\WeatherServiceTest
# ✓ it gets severe weather alerts 0.61s
# Tests: 1 passed (4 assertions)
# Duration: 0.97s

# 🧩 Feature Tests
# Purpose: Test end-to-end functionality, including interactions between different components of the application.

# 🌧️ Check Rain Forecast Test
php artisan make:test CheckRainForecastTest
php artisan test --filter CheckRainForecastTest
# Expected Output:
# PASS Tests\Feature\CheckRainForecastTest
# ✓ weather check command 3.16s
# Tests: 1 passed (1 assertion)
# Duration: 3.47s

# ⚠️ Severe Weather Alert Notification
php artisan make:test SevereWeatherAlertNotificationTest
php artisan test --filter SevereWeatherAlertNotificationTest
# Expected Output:
# PASS Tests\Feature\SevereWeatherAlertNotificationTest
# ✓ notification is sent 0.48s
# Tests: 1 passed (1 assertion)
# Duration: 0.96s

# 🏷️ Testing Subscription Flows
php artisan make:test SubscriptionTest
php artisan test --filter SubscriptionTest

# For Testing Stripe Methods
composer require mockery/mockery --dev
# Expected Output:
# PASS Tests\Unit\SubscriptionTest
# ✓ user can start subscription with trial period 0.03s
# ✓ user is charged when trial ends 0.04s
# ✓ user can cancel subscription during trial 0.03s
# ✓ trial can be extended 0.03s
# Tests: 10 passed (16 assertions)
# Duration: 2.14s

# 🧪 Generate Coverage Report
php artisan test --coverage-html=coverage
# Expected Output:
# Coverage report generated in the 'coverage' directory.

