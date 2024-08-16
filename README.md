# Chat System Backend for Laravel

This repository contains the backend implementation of a chat system using Laravel. It includes API endpoints for managing chat threads and messages, as well as test cases to ensure functionality.

## Installation and Setup

Follow these steps to set up the backend chat system:

### 1. Clone the Repository

git clone <URL>
cd chat-backend

### 2. Install dependency

composer install

### 3.  Envoirmnent Setup

cp .env.example .env and create database chat_db

### 4. Generate Application Key
php artisan key:generate

### 5. Run Migrations

php artisan migrate

### 6. Seed the Database

php artisan db:seed

### 7. Start the Laravel Development Server

php artisan serve


### Run test cases
php artisan test


