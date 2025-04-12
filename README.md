
# socialapp



## Installation

To run this application, it must be installed inside a Docker container. Please ensure that Docker Desktop is installed on your system and actively running before starting the setup.
All required services (app, database, web server, etc.) are automatically managed through Docker Compose — no manual configuration is needed. Once everything is up, you'll have a fully self-contained environment ready to use.
All commands below are executed from the **host system**, not inside the container.

```bash
docker-compose build
composer update
docker-compose up -d

docker exec -it socialapp_app cp .env.example .env
docker exec -it socialapp_app php artisan key:generate
docker exec -it socialapp_app php artisan storage:link
docker exec -it socialapp_app chmod -R 775 storage
docker exec -it socialapp_app chmod -R 775 public/storage
docker exec -it socialapp_app php artisan migrate:fresh --seed
docker exec -it socialapp_app php artisan test
```
## Note

The user locations are assigned randomly during seeding, which may result in some filter options returning no results.
If full coverage of all filter options is desired, simply regenerate the data by re-running the seeder.

## Usage

```html
http://localhost:8000/admin
Email:    test@example.com
Password: password
```

## User Password Handling

Password input and validation were not part of the project requirements.
To ensure consistency with the specification and avoid database errors, the password field has been removed from the Filament user form.

Instead, a default password is assigned automatically during user creation to satisfy internal application logic (e.g. authentication constraints).
This keeps the interface aligned with the scope while maintaining functional integrity.


## Test User

A test user is seeded with no posts and no location. This account is used solely for logging into the admin panel.