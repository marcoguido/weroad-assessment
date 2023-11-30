# :hammer_and_wrench: Build the project

## Prerequisites

1. Make sure Docker is installed on your machine
2. Ensure `make` command is available by running `make -v`. If an error shows up, install it as follows
    ```bash
   # On Ubuntu/linux (should work also under WSL shell)
   sudo apt install build-essential
   # On MacOS
   xcode-select --install
   ```

## Setup

1. Clone the repository and open a terminal window pointing to the cloned project
2. Pull all `composer` dependencies and create Docker containers by running `make`
3. Start the application either by running `make up` or `make up-d` (Docker will run as daemon with this one)
4. Setup database structure and install default data by running `make run-fresh-migrations`
5. Open a new browser window and head to `http://localhost`, you'll be greeted by a default Laravel window

## Utils

1. If you need to connect to a shell in master container, run `make tty`
2. If you need to seed the database with some fake data, run:
    - `make tty` to connect to a new shell session
    - `php artisan db:seed --class TravelSeeder`
3. If new migrations needs to be executed, run `make run-migrations`
4. When done, shut everything down by running `make down`
5. To create a new user, run `make admin-user` and follow the prompts
6. If you need to access the database without the hassle of configuring a local client, there is an instance
   of [PhpMyAdmin](http://localhost:8081) running. Login credentials are `root` as username and an empty password.
7. Environment build step generates also API documentation. If something goes wrong or the file needs an update, run
   either `make api-docs` (when running CLI from host machine) or `composer generate-api-docs` commands
8. Take a look at `Makefile` targets for other useful commands and routines
