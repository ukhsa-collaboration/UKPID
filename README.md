# UK Poisons Information Database - Backend

[![tests status](https://gitlab.com/juicy-media-ltd/ukpid/ukpid-backend/badges/main/pipeline.svg)](https://gitlab.com/juicy-media-ltd/ukpid/ukpid-backend/commits/main)

## Introduction

This is the backend application which powers the UK Poisons Information Database (UKPID), a service provided by
the [National Poisons Information Service (NPIS)](https://www.npis.org/).

The main role of NPIS is to advise NHS healthcare professionals on the diagnosis, treatment and care of poisoned
patients across the UK. The major workload of the NPIS is to advise hospital emergency departments, however minor
injuries units and primary care services are also significant users of the service – the latter involving NHS advice
services (NHS 111, NHS 24 and NHS Direct) to a large extent. Telephone calls are answered by Specialists in Poisons
Information (SPIs). Data from telephone enquiries to the NPIS and the advice given are stored in UKPID.

This is a Laravel-based application supported by a MongoDB database for enquiry data and a MySQL database used for
storing application data (e.g. users, sessions, tokens).

Primary access to this backend is via
the [Electron-based frontend application](https://gitlab.com/juicy-media-ltd/ukpid/ukpid-desktop-app).

## Development

A [Docker](https://www.docker.com/)-based local environment is provided for development.

PHP, Nginx, MySQL 8, and MongoDB containers are provided to run the core application. Additional database containers are
available for PHPUnit testing purposes.

Composer and NPM (Node.js) containers are provided for package management, negating the need for versions of these to be
maintained on the host machine.

A [Mailpit](https://github.com/axllent/mailpit) container is provided as a SMTP host to capture and preview emails sent
from the application. It can be accessed at [http://localhost:35082](http://localhost:35082)

### Prerequisites

You must have the following setup and ready to go before working on this project:

* [Docker Desktop](https://www.docker.com/products/docker-desktop/)
    * On Windows, ensure Docker is using WSL2. It is highly recommended that you clone this project inside your WSL
      environment, the performance gain of this vs running it from Windows will be vast.
    * On Mac, ensure Docker is configured to use VirtioFS as its file sharing implementation.
    * For more details on this, visit
      the [Juicy Media Wiki](https://juicy-media-ltd.gitlab.io/wiki/development/docker.html)
* [PhpStorm](https://www.jetbrains.com/phpstorm/) **2023.2 or later**
    * The `.idea` directory is intentionally committed to this repository, as it stores IDE configurations that will assist
      with the development of this application (e.g. Larvel Pint inspection profiles, an XDebug config, and so on).
    * [Laravel Idea](https://plugins.jetbrains.com/plugin/13441-laravel-idea) is a paid extension for PhpStorm which
      provides additional IDE support for Laravel. It is highly recommended.

### Directory Structure

This repository is organised to make things a little cleaner and not too overwhelming the first time you look at it.

The root of the repo will contain _utility stuff_, mainly for aiding development. Docker files, IDE configs, CI/CD
files, documentation, etc.

The actual application is contained within the `/application` directory.

### Setup

1. In `/application`, copy the `.env.development` file to `.env`.
    * This should contain all the environment variables required to get started for development
2. **Windows only:** Copy the `/docker-compose.override-wsl.yml` file to `/docker-compose.override.yml`
3. Run `docker-compose up` in the root of the project to start the docker containers
4. Run `docker-compose run composer composer install` to Composer packages
    * 🤩 Add yourself to the authors listed in the composer.json file!
5. Run `docker-compose run npm npm install` to install node packages
6. Run `docker-compose run vite npm run build` to generate frontend assets
7. Run `docker-compose exec laravel php artisan key:generate` to generate application encryption keys
8. Run `docker-compose exec laravel php artisan migrate --seed` to create and populate the database
9. Once that's all finished, the application should be visible at [http://localhost:35080](http://localhost:35080)

### Composer

A Composer docker container is provided for PHP package management.

To interact with it, use `docker-compose run composer composer <command>`.

### NPM

A Node docker container is provided for npm package management.

To interact with it, use `docker-compose run npm npm <command>`.

### Frontend assets

While this application doesn't have much of a frontend as its primary purpose is to provide an API for consumption by
the desktop app, we do need to provide a login page for OAuth purposes. As such, we need something that can bundle our
CSS and JS, such as Vite.

Vite has two commands:

* `docker-compose run --service-ports vite` to watch for changes and compile for development. _(Note: you must include
  the `--service-ports` flag to expose the required ports for this to work)_
* `docker-compose run vite npm run build` to compile for production

As with the frontend application, the minimal UI provided by the backend
utilises [Microsoft's Fluent UI web components](https://learn.microsoft.com/en-us/fluent-ui/web-components/).

### Telescope

[Laravel Telescope](https://laravel.com/docs/10.x/telescope) is accessible on local environments
via [/telescope](http://localhost:35080/telescope/).

### XDebug

XDebug is available for PHP debugging. A PhpStorm configuration is provided in this repo.

You will need to send a `XDEBUG_SESSION` cookie with the value `PHPSTORM` with your API requests. In the browser, this
cookie can be set with
the [Xdebug Helper](https://chrome.google.com/webstore/detail/xdebug-helper/eadndfjplgieldjbigjakmdgkmoaaaoc) extension.

## Testing

This project uses [Laravel's testing tools](https://laravel.com/docs/10.x/testing) for unit and feature testing.

You must write tests to accompany the code you write and the features you implement. The majority of these tests are
likely to be feature tests to ensure the application produces the expected result for a given input. Where the code you
write can be tested in isolation, you should write a unit test.

Tests are run automatically on merge requests, commits to the `main` branch, and on deployments. Tests must pass for the
merge request to be accepted and for the deployment to start.

You can use `docker-compose exec laravel php artisan test` to run the tests.

## Contributing

### Code quality

This project uses several tools to enforce code standards and style.

<!-- /* @formatter:off */ -->
![Screenshot from news satire programme The Day Today, showing Peter O'Hanraha-hanrahan with a quote that reads "I don't like it, but I'll have to go along with it](https://i.imgur.com/aYa6uPG.jpeg){width="200"}
<!-- /* @formatter:on */ -->

There's always debates to had over which code style is better, but they're not really important. These tools enforce one
style and strive solve the issue of conflicts between developers. You might not like it, but just go along with it!

#### Laravel Pint

[Laravel Pint](https://laravel.com/docs/10.x/pint) is installed and configured to enforce an opinionated code standard
for PHP files in this project. You should ensure Pint is run before you make a commit.

PhpStorm 2023.2 introduced support for Pint, so ensure you are running an up-to-date version of the IDE before making
contributions. The PhpStorm configuration included in this repo is set up to run Pint on code format, though you may
need to ensure that the PHP Docker is selected as the configuration (Settings/Preferences > PHP > Quality Tools >
Laravel Pint > Configuration: Select `laravel`. If that isn't an option, create a new configuration and point it to the
Laravel Docker container).

You may wish to set PhpStorm to run code reformatting on save (Settings/Preferences > Tools > Actions on Save).

You can also use `docker-compose exec laravel ./vendor/bin/pint -v` to run Laravel Pint manually.

#### Prettier

[Prettier](https://prettier.io) is installed and configured to enforce an opinionated code standard
for JS, CSS, JSON and Markdown files in this project. You should ensure Pint is run before you make a commit.

PhpStorm is configured to run Prettier on save and on reformat. You should configure PhpStorm to use the node.js
interpreter provided by Docker for this project (Settings/Preferences > Languages > node.js > Node Interpreter: In the
dropdown, select Add > Add Remote > Docker Compose. PhpStorm should auto select the docker-compose.yml file.
Select `npm` as the service.)

You can also use `docker-compose run npm npx prettier . --write` to run Prettier manually.

#### ESLint and Stylelint

ESLint and Stylelint are also installed and configured on this project to enforce an opinionated code standard
for JS and CSS files.

PhpStorm should highlight errors in code.

You can use `docker-compose run npm npm run lint:js` to run ESLint, `docker-compose run npm npm run lint:style` to run
Stylelint, or `docker-compose run npm npm run lint` to run both along with a Prettier check.

### Review process

No code should be committed to the main branch without being submitted and reviewed as a merge request.

Merge requests must be reviewed and approved by a lead developer. CI/CD Pipelines are configured to run Laravel tests
and code quality tests on the code being submitted for merge. These pipelines must succeed before the merge request can
be approved. To save time and reduce the usage of pipeline minutes, it is recommended you run these tools locally before
marking your MR as ready for review.

## Documentation

### API Documentation
[Scramble](https://scramble.dedoc.co) is used to generate API documentation. 

You'll have two routes where you can view the documentation on your local environment:
- `/docs/api` - UI viewer for your documentation
- `/docs/api.json` - Open API document in JSON format describing your API.

Scramble will analyze the code and try and detect what type a property is, however it may not always be correct (it doesn't appear to have support for Eloquent Accessors & Mutators, for example). You should review the documentation as you add new resources and endpoints and ensure the type definitions are correct. 

### Application Documentation
While your code should be clean, obvious, and documented through docblocks and comments as appropriate, it may be necessary to write documentation to detail processes, features, and anything else you deem relevant as you contribute to this application. If you do this, you should write it in a Markdown file in the `/docs` directory.
