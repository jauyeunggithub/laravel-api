@echo off

REM Ensure that Docker Compose is running and rebuild containers
echo Starting Docker Compose and rebuilding containers...
docker-compose up --build -d

REM Wait for the containers to be ready (optional, adjust based on your needs)
echo Waiting for containers to be ready...
timeout /t 10

REM Install composer dependencies inside the container
echo Installing Composer dependencies...
docker-compose exec app composer install

REM Run the migrations
echo Running migrations...
docker-compose exec app php artisan migrate

REM Run the unit tests
echo Running tests...
docker-compose exec app php artisan test

REM Optionally, stop the containers after tests are done
echo Stopping Docker Compose containers...
docker-compose down
