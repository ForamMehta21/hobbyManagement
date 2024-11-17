# hobbyManagement

# Project Setup Instructions

## Prerequisites
Make sure you have the following installed:
- PHP (version8.2)
- Composer
- Laravel
- A database server (MySQL)

## Setup Instructions

1. **Extract the ZIP File**
   - Extract the downloaded ZIP file to your desired directory.

2. **Navigate to the Project Directory**
   ```bash
   cd path/to/your/project

3.  **Install Dependencies Run the following command to install the necessary packages via Composer:**
    composer install

4.  **Create a Database and configure into .env file**
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=your_database_name
    DB_USERNAME=your_username
    DB_PASSWORD=your_password

5.  **Generate Application Key Run the following command to generate a new application key:**
    php artisan key:generate

6.  **Migrate Database Run the following command to migrate the database:**
    php artisan migrate

7.  **Create a Super Admin and for other data run below command:**
    php artisan db:seed

8.  **To run your Laravel application, use the following command:**
    php artisan serve




