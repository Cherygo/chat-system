# Real-Time Chat Application

A real-time chat application utilizing Laravel Reverb for broadcasting and WebSockets.

## Installation & Setup

1. Clone the repository, navigate to the project directory and install dependencies:
```bash
cd PROJECT_ROUTE
composer install
npm install
```
2. Set up .env and generate application key
```bash
cp .env.example .env
php artisan key:generate
```
3. Configure .env to match your PostgreSQL database credentials
4. Run database migrations
```bash
php artisan migrate
```
## Running the application
To run the application you will require a total of 4 terminals:
1. Laravel local server
```bash
php artisan serve
```
2. Vite for frontend
```bash
npm run dev
```
3. Laravel Reverb WebSocket server
```bash
php artisan reverb:start
```
4. Queue worker for processing background events
```bash
php artisan queue:work
```
