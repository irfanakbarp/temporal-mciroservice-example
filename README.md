This is an example of temporal that orchestrate microservice:

## Run temporal server using docker 
```
cd temporal
docker-compose -f docker-compose-postgres.yml up
```

## Our Logging Service uses python:
Make sure you installed:
```
python -m pip install django djangorestframework
python -m pip install psycopg2-binary

python manage.py runserver <your_host>:3002

```

## Contact Service with Goravel

```
// Generate APP_KEY
go run . artisan key:generate

// Install dependencies
go mod tidy

// Run app without hot reload
go run . 

// Run app with hot reload
air

// Run the worker
go run . app/temporal/worker/main.go

```

## User Service with Laravel
```
// Run composer install
composer i

// Run the app
php artisan serve --host=127.0.0.1 --port=3000

// Run the roadrunner
./rr serve -c .rr.yaml

```
