@echo off

REM Ensure that Docker Compose is running and rebuild containers
echo Starting Docker Compose and rebuilding containers...
docker-compose up --build -d

REM Remove the existing SQLite database file (if it exists)
echo Removing existing SQLite database file...
docker-compose exec app rm -f /var/www/html/database/database.sqlite

REM Install composer dependencies inside the container
echo Installing Composer dependencies...
docker-compose exec app composer install

REM Automatically answer "Yes" to the SQLite database creation prompt and run migrations
echo Running migrations (answering "Yes" to SQLite prompt)...
docker-compose exec app bash -c "echo 'yes' | php artisan migrate --database=sqlite"

REM Run the unit tests
echo Running tests...
docker-compose exec app php artisan test

REM Optionally, stop the containers after tests are done
echo Stopping Docker Compose containers...
docker-compose down
