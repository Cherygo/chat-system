# Real-Time Chat Application

A real-time chat application utilizing Laravel Reverb for broadcasting and WebSockets.

## Installation & Setup

1. Clone the repository, navigate to the project directory and install dependencies:
```bash
cd chat-system
composer install
npm install
```
2. Set up .env and generate application key
```bash
cp .env.example .env
php artisan key:generate
```
3. Configure .env to match your PostgreSQL database credentials. PostgreSQL must
   be running before migrations or web requests can succeed. The application
   also stores sessions, cache, and queued jobs in PostgreSQL.

   On Ubuntu/WSL, check the cluster and start it if it is down:

```bash
pg_lsclusters
sudo pg_ctlcluster --skip-systemctl-redirect 16 main start
pg_isready -h 127.0.0.1 -p 5432
```

   Use the version and cluster name reported by `pg_lsclusters` if they differ
   from `16 main`. WSL without systemd may require this again after restarting.

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

Open the URL printed by `php artisan serve`. If port 8000 is occupied, Laravel
tries the next available port; make sure you are opening this project's server.

After changing backend or event code, stop and rerun `php artisan queue:work`,
since the worker keeps application code in memory.

Alternatively, `composer run dev` starts Laravel, Vite, a queue listener, and
log tailing together. PostgreSQL must already be running, and
`php artisan reverb:start` is still required separately for realtime updates.
Restarting these processes cannot fix PHP errors such as an incorrect exception
import; those require code changes.

Run the regression tests with `composer test` and check the production assets
with `npm run build`.
