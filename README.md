# Eventula Event Manager - ECO System

The Eventula Event Manager / ECO System is a fully featured White labeled Event Management system. The only prerequisite is `docker and/or docker-compose`. Everything is self contained. The purpose of this application is to unify Event Management (Venue, attendees, seating), Ticket Sales, Tournament Management, Shop Management and Credit Management. 

https://eventula.com

If you are using this please consider signing up to eventula for event mapping.

##### Home Page:

![Manager front page](resources/images/home-page.png)
##### Event Page:
![Event Page](resources/images/events-page.png)
##### Successful Payment Page
![Successful Payment](resources/images/success-payment-page.png)
##### Event Management
![Event Admin Page](resources/images/events-management-page.png)
##### Ticket Management
![Tickets Breakdown](resources/images/tickets-management-page.png)
##### Tournament Management
![Tournament Management](resources/images/tournaments-management-page.png)
##### Tournament Brakcets
![Tournament Brackets](resources/images/tournaments-brackets-page.png)

##### Features

- White Label
- Event Management
  - Online and presence events
  - Signup/Info Pages
  - Tickets
  - Signups/Gifts/Staff/Refunds
  - Timetables
  - Tournaments
  - Seating
- Multiple Login Gateways
- Ticket Management
  - Sale Periods
  - Limited Quantity
  - Weekend, Day, Spectator Ticket Types
  - Eligible/Non Eligible for seats Ticket Type supported!
  - QR Codes supported!
- Seating Plans
  - Multiple Plans per Event supported!
  - Manual seating of participants
- Shop
- Credit System
- Venue Management
- User Management
- Timetable Management
  - Multiple timetables per event supported!
- Tournament Management
  - Supported via Challonge API
  - 1v1, Teams and PUGs supported!
  - Single/Double Elimination and Round Robin supported!
  - Different rules for each Tournament 
- Matchmaking feature
- Event Sign in
  - Sign in via QR Code
- Event specific page for when at the event
  - Shows Timetables, Attendees, Tournaments, Announcements and Seating
- Game Management
  - Gamserver Management
    - RCON Support for remote management and status gathering (currently only for Maniaplanet (and Trackmania), Source and Goldsource)
    - Support for automated Gameserver assignments and fully automated matchmaking / tournaments (currently only Get5 for CS:GO is supported via https://github.com/Lan2Play/get5_eventula_apistats)
    - Public gameserver list on the homepage 
- (File)Gallery
- Helpsystem to publish help/faq articles
- Admin Interface
- Web based CSS Editor
- Light and Dark Theme out of the Box
- Expandable templating and CSS System based on Bootstrap 4
- API Endpoints
  - Events
  - Participants
  - Seating
  - Timetables
- Payments
  - Terms & Conditions
  - Paypal Express
  - Stripe Card payments
  - Free payment provider
  - Breakdowns in Admin
- Voting and Poll Management
- News Management
- Comment Management
- Account Management
- Newsletter / Email Feature
- Multilanguage (currently EN and DE)
- EU cookie consent
- Imprint / Dataprotection page
- Fully Encapsulated in Docker Containers
- Built on Laravel 8x
- Easily Expandable
- NGINX, MYSQL & PHP Docker stack

## Installation Prerequisites

### Prerequisites

- Docker v17
- Docker-compose v1.18
- API Keys for one of the Payment Providers

## Usage

### Docker

This method is intended to be run as just a image with your own database. Persistant storage is required for the storage/ directory.

```
docker run -it \
  -e APP_DEBUG=true \
  -e APP_ENV=local \
  -e APP_URL=localhost \
  -e ENV_OVERRIDE=true \
  -e DB_HOST=database \
  -e DB_DATABASE=eventula_manager \
  -e DB_PORT=3306 \
  -e DB_USERNAME=eventula_manager \
  -e DB_PASSWORD=password \
  -e TIMEZONE=UTC \
  -e ANALYTICS_TRACKING_ID= \
  -e PAYPAL_USERNAME= \
  -e PAYPAL_PASSWORD= \
  -e PAYPAL_SIGNATURE= \
  -e STEAM_API_KEY= \
  -e CHALLONGE_API_KEY= \
  -e FACEBOOK_APP_ID= \
  -e FACEBOOK_APP_SECRET= \
  -e LOG_FILES=false \
  -e ENABLE_HTTPS=false \
  -e SESSION_SECURE_COOKIE=false \
  -e DB_CONNECTION=mysql \
  -e DB_MIGRATE=true \
  -e DB_SEED=true \
  -p 80:80 \
  -p 443:443 \
  -v eventula_manager_storage:/web/html/storage/ \
  --name eventula_manager_app \
  eventula/manager:latest
```

Follow Post-Docker Below

### Docker-compose

This method is intended to be run with docker-compose. It will create a full stack including database, load balancer & SSL Encryption.

```
version: "3.4"
services:
  app:
    image: eventula/manager:latest
    volumes:
      - eventula_manager_certs:/etc/nginx/certs
      - eventula_manager_storage:/web/html/storage/
    environment:
      # App Config
      - APP_DEBUG=true
      - APP_ENV=local
      - APP_URL=localhost
      - ENV_OVERRIDE=true
      # Database Settings
      - DB_DATABASE=eventula_manager
      - DB_USERNAME=eventula_manager
      - DB_PASSWORD=password
      # Timezone
      - TIMEZONE=UTC
      # Google Analytics
      - ANALYTICS_TRACKING_ID=
      # Paypal
      - PAYPAL_USERNAME=
      - PAYPAL_PASSWORD=
      - PAYPAL_SIGNATURE=
      # Steam
      - STEAM_API_KEY=
      # Challonge
      - CHALLONGE_API_KEY=
      # Facebook
      - FACEBOOK_APP_ID=
      - FACEBOOK_APP_SECRET=
      # File Logger
      - LOG_FILES=false
      # HTTPS
      - ENABLE_HTTPS=false
      # Reverse Proxy HTTPS
      - FORCE_APP_HTTPS=false
      # Only Secure Cookies
      - SESSION_SECURE_COOKIE=false
      # Migrate Database on Boot
      - DB_SEED=true
      - DB_MIGRATE=true
      # DO NOT CHANGE BELOW
      - DB_CONNECTION=mysql
      - DB_PORT=3306
      - DB_HOST=database
    container_name: eventula_manager_app
    ports:
      - 80:80
      - 443:443
  database:
    image: mysql:5.6
    volumes:
      - eventula_manager_database:/var/lib/mysql
    environment:
      # Change The password as according
      - MYSQL_PASSWORD=password
      # DO NOT CHANGE BELOW
      - MYSQL_DATABASE=eventula_manager_database
      - MYSQL_USER=eventula_manager
      - MYSQL_RANDOM_ROOT_PASSWORD=true
    ports:
      - 3306:3306
    container_name: eventula_manager_database
  loadbalancer:
    image: traefik:v2.0
    volumes:
    - /var/run/docker.sock:/var/run/docker.sock:ro
    - eventula_manager_certs:/certs:z
    - eventula_manager_acme:/acme:z
    ports:
    - 80:80/tcp
    - 443:443/tcp
    # Debug Only
    # - 8080:8080/tcp
    command:
    # Debug Only
    # - --api.insecure=true
    - --providers.docker=true
    - --entryPoints.web.address=:80
    - --entryPoints.websecure.address=:443
    - --providers.docker.exposedByDefault=false
    - --certificatesresolvers.le.acme.email=me@mydomain.com
    - --certificatesresolvers.le.acme.storage=/acme/acme.json
    - --certificatesresolvers.le.acme.tlschallenge=true
    container_name: eventula_manager_loadbalancer
volumes:
  eventula_manager_database:
    name:
      eventula_manager_database
  eventula_manager_certs:
    name:
      eventula_manager_certs
  eventula_manager_storage:
    name:
      eventula_manager_storage
  eventula_manager_acme:
    name:
      eventula_manager_acme
```

Follow Post-Docker Below

#### Post-Docker

When running for the first time you'll be a new APP_KEY will be generated. Keep this safe!. You'll need to add it to the env variables (EG ```-e APP_KEY=someRandomKey```) otherwise it will regenerate the APP_KEY on each reboot.

Once running and the database has migrated go to your app and you will be greated with the install page

### Makefile

This method is intended for development but can be used in production. It uses docker-compose to build the image and database from the source code instead of pulling them from docker hub. If you plan to participate in the development of eventula, its worth checking out the Makefile. There are many useful commands implemented that you can use to make your life easier.

#### 1. Setup & Configuration

Run ```make env-file``` to create a ```.env``` file in the ```src```  directory. Then modify it as according to your preferences. KEEP THIS SAFE & SECURE! This file holds the credentials used within the app. If any would be hacker was to get hold of this file they have access to everything! 

#### 2. Run

Run the stack detached. This is re attachable. 

```
make
```

##### Interactive

Run the stack in the foreground. Once exited the stack will stop.

```
make interactive
```

##### Migrate & Seed Database

Run Database migrations and populate.

```
make database-migrate
make database-seed
```

#### Stop

Stop the stack.

```
make stop
```

## HTTPS

To enable HTTPS set ```ENABLE_HTTPS=true```. If you wish to use your own certs, copy them to ```resources/certs``` or mount in the certs to the ```/etc/nginx/certs``` directory on the container. 

Note: Only set HTTPS to true if you are doing SSL Termination within the app.

### Caveats

- You must rename the certs to ```eventula_manager.crt``` and ```eventula_manager.key```.

## Running behind a reverse proxy

If you want to run eventula with http and a reverse proxy in front which serves it to the web via https you have to enable set ```ENABLE_HTTPS=false``` and ```FORCE_APP_HTTPS=true``` in your env file / your docker-compose.yml . This will run the NGINX running eventula in http mode but it will force the https link schema for all the links in eventula.


## Custom Timezone

If you have to run the Container for a different Timezone, you can specify it via ```TIMEZONE=Europe/Berlin``` in your env file / your docker-compose.yml . You have to set your specify a valid timezone name for Alpine linux.

## Secret Managers

The Event Manager ships with a file reader for Env variables such as Passwords as API Keys for Secrets Managers such as Ranchers Secret Manager, EnvKey and Summon. To use it append ```_FILE``` to the Env variable and change the value to be the location of the secret file located on the container! It is recommended you mount a secrets directory into the container for example:

If we add the volume; ```resources/secrets/:/run/secrets``` and store our ```DB_PASSWORD``` in a file called ```DB_PASSWORD``` in ```resources/secrets/``` directory, we can set ```DB_PASSWORD_FILE=/run/secrets/DB_PASSWORD``` and the app will read the file and inject the password into the Environment Variable ```DB_PASSWORD```.

### Caveats

- Only one entry per file
- File must only container value
- The file can be named anything, but it must be reflected in the ```_FILE``` env variable
- It will only work on the following Env Variables;
  - FACEBOOK_APP_ID
  - FACEBOOK_APP_SECRET
  - FACEBOOK_PIXEL_ID
  - DB_DATABASE
  - DB_USERNAME
  - DB_PASSWORD
  - MYSQL_DATABASE
  - MYSQL_USER
  - MYSQL_PASSWORD
  - TIMEZONE
  - ANALYTICS_TRACKING_ID
  - PAYPAL_USERNAME
  - PAYPAL_PASSWORD
  - PAYPAL_SIGNATURE
  - STEAM_API_KEY
  - CHALLONGE_API_KEY
  - APP_KEY

## Custom SCSS & Views

To load in custom SCSS (Sassy CSS) & Views (Blade) mount the following directories;

- ```path_to_scss:/web/html/resources/assets/sass```
- ```path_to_views:/web/html/resources/assets/views```

CSS must be in SCSS format.

To recompile CSS use the 'Recompile CSS' link located at http://localhost/admin/appearance

You can also change base variables and ad your own in the appearance page.

##### To Do

- Refunds
- Unit Tests

## Sites that use the Lan Manager

- LanOps - https://lanops.co.uk
- TheLanProject - https://thelanproject.co.uk
- IronLan - https://ironlan.co.uk
- Lan2Play - https://lan2play.de
