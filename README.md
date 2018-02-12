
# LanOps Lan Manager - WIP - v0.1.4.1
Lan Manager by Th0rn0 of LanOps. 

https://github.com/th0rn0

Initial Front End Design by Invert.

https://github.com/richardmountain

The Lan Manager is a fully featured White Labelled Event Management system. The only prerequisite is `docker & docker-compose`. Everything is self contained.

https://lanops.co.uk
##### Home Page:
![Manager front page](https://i.imgur.com/IqKAK1h.png)
##### Event Page:
![Event Page](https://i.imgur.com/BAvJU2l.png)
##### Successful Payment Page
![Successful payment](https://i.imgur.com/Qb2fyPw.png)
##### Event Management
![Event Admin Page](https://i.imgur.com/w9aD10o.png)

##### Ticket Management
![Tickets breakdown](https://i.imgur.com/nM7lcnG.png)

##### Features

- White Label
- Event Management
  - Signup/Info Pages
  - Tickets
  - Signups/Gifts/Staff/Refunds
  - Timetables
  - Tournaments
  - Seating
- Steam Integration
  - All Logins are done via Steam - NO MORE PASSWORDS!
- Ticket Management
  - Sale Periods
  - Limited Quantity
  - Weekend, Day, Spectator Ticket Types
  - Eligible/Non Eligible for seats Ticket Type supported!
  - QR Codes supported!
- Seating Plans
  - Multiple Plans per Event supported!
  - Manual seating of participants
- Venue Management
- User Management
- Timetable Management
  - Multiple timetables per event supported!
- Tournament Management
  - Supported via Challonge API (To be changed)
  - 1v1, Teams and PUGs supported!
  - Single/Double Elimination and Round Robin supported!
- Event Sign in
  - Sign in via QR Code
- Event specific page for when at the event
  - Shows Timetables, Attendees, Tournaments, Announcements and Seating
- Gallery
- Admin Interface
- API Endpoints
  - Events
  - Participants
  - Seating
  - Timetables
- Payments
  - Terms & Conditions
  - Paypal Express
  - Breakdowns in Admin
- Account Management
- Fully Encapsulated in Docker Containers
- Built on Laravel
- Easily Expandable
- NGINX, MYSQL & PHP Docker stack

## Installation Prerequisites

### Prerequisites
- Docker v17
- Docker-compose v1.18
- Paypal Account for payments
- Challonge Team API key  (Challongeteamapiherelocation)
- Steam Developers API Key (steamapiherelocation)
- Google Analytics Tracking ID (googleanalyticsapiherelocation)

### Pre Requisites - Dockerless
- PHP
- MYSQL
- Composer
- NPM
- Imagick

### Pre Setup
Make a .env file - Use the example - KEEP THIS SAFE & SECURE! This file holds the credentials used within the app. If any would be hacker was to get hold of this file they have access to everything!

Anything wrapped in <<>> needs your input!
```
APP_DEBUG=<< true OR false >>
APP_ENV=<< local OR prod >>
APP_URL=<< app URL. Use localhost if unsure >>
APP_KEY=<< Laravel App key. Use make generate-key helper >>
ANALYTICS_PROVIDER=GoogleAnalytics
ANALYTICS_TRACKING_ID=<< Google Analytics tracking ID >>

PAYPAL_USERNAME=<< Paypal account to use for payments. If in development ENV use SANDBOX credentials! >>
PAYPAL_PASSWORD=<< Paypal password >>
PAYPAL_SIGNATURE=<< Paypal signature >>
STEAM_API_KEY=<< Steam API key >>
CHALLONGE_API_KEY=<< Challonge API key >>

DB_CONNECTION=mysql
DB_PORT=3306
DB_HOST=database
DB_DATABASE=<< database name here >>
DB_USERNAME=<< database username here >>
DB_PASSWORD=<< database password here >>
MYSQL_DATABASE=<< database name here >>
MYSQL_USER=<< database username here >>
MYSQL_PASSWORD=<< database password here >>
MYSQL_ROOT_PASSWORD=<< database root password here >>
```

### Ezi Install

#### First time install
```
make app-install-clean
```

#### Update
```
make app-install
```

### Dockerless Setup

Disclaimer: This part is untested and I dont intend on testing it. If you are struggling to get it to work, leave the year 1999 and utlize Docker.

1. Y U DO DIS?

2. Set the server root as public/. Apache and NGINX are supported. WAMP or its alternatives are untested (and shouldn't be used)

3. Install Prerequisites:

```
sudo apt-get update \
&& apt-get install \
&& mysql-client libmagickwand-dev --no-install-recommends \
&& pecl install imagick \
&& openssl \
&& imagick \
&& composer \
&& npm
&& php-mcrypt && php-pdo_mysql && php-bcmath && php-gd && php-bc2 && php-zip
```

4. Install PHP Dependencies:
```
composer install
```

5. Install JS Dependencies:
```
npm install
```

6. Compile JS & CSS:
```
gulp --production
```

7. Run the database migration:
```
php artisan migrate
```

8. Seed the database:
```
php artisan db:seed
```

9. Create symlink to storage:
```
php artisan storage:link
```

10. Copy default images:
```
cp -r resources/assets/images/* storage/app/public/images/main/
```

11. Set folder structure:
```
mkdir storage/app/public/images/gallery/
mkdir storage/app/public/images/events/
mkdir storage/app/public/images/venues/
mkdir storage/app/public/images/main/
chmod +x bootstrap/cache/
chmod +x storage/
```

## Run

```
make
```

##### Interactive
```
make interactive
```

##### Stop
```
make stop
```

## Contributors

- Th0rn0 - https://github.com/th0rn0
- 1nvert - https://github.com/richardmountain

##### To Do

- Refunds
- Twitch Integration
- Tournament API
- Server Management
- Add more payment Gateways
- Unit Tests
- Get a life
