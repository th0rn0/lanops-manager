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
- Event Sign in
  - Sign in via QR Code
- Event specific page for when at the event
  - Shows Timetables, Attendees, Tournaments, Announcements and Seating
- Gallery
- Admin Interface
- Web based CSS Editor
- Expandable templating and CSS System
- API Endpoints
  - Events
  - Participants
  - Seating
  - Timetables
- Payments
  - Terms & Conditions
  - Paypal Express
  - Stripe Card payments
  - Breakdowns in Admin
- Voting and Poll Management
- News Management
- Comment Management
- Account Management
- Fully Encapsulated in Docker Containers
- Built on Laravel
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
  -e APP_KEY=somekey \
  -e APP_DEBUG=true \
  -e APP_ENV=local \
  -e APP_URL=localhost \
  -e ENV_OVERRIDE=true \
  -e DB_HOST=database \
  -e DB_DATABASE=eventula_manager \
  -e DB_PORT=3306 \
  -e DB_USERNAME=eventula_manager \
  -e DB_PASSWORD=password \
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
  -e DB_CONNECTION=mysql \
  -e DB_MIGRATE=true \
  -e DB_SEED=true \
  -p 80:80 \
  -p 443:443 \
  -v eventula_manager_storage:/web/html/storage/ \
  --name eventula_manager_app \
  th0rn0/eventula-manager:latest
```

Follow Post-Docker Below

### Docker-compose

This method is intended to be run with docker-compose. It will create a full stack including database, load balancer & SSL Encryption.


Example:

```
version: "3.4"
services:
  app:
    image: th0rn0/eventula-manager:latest
    volumes:
      - eventula_manager_certs:/etc/nginx/certs
      - eventula_manager_storage:/web/html/storage/
    environment:
      # App Config
      - APP_KEY=somekey
      - APP_DEBUG=true
      - APP_ENV=local
      - APP_URL=localhost
      - APP_EMAIL=example@example.com
      - ENV_OVERRIDE=true
      # Database Settings
      - DB_DATABASE=eventula_manager_database
      - DB_USERNAME=eventula_manager
      - DB_PASSWORD=password
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
      # Migrate Database on Boot
      - DB_SEED=true
      - DB_MIGRATE=true
      # DO NOT CHANGE BELOW
      - DB_CONNECTION=mysql
      - DB_PORT=3306
      - DB_HOST=eventula_manager_database
    container_name: eventula_manager_app
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

Please also refer to the ENV section below.

Once running and the database has migrated go to your app and you will be greated with the install page

### Makefile

This method is intended for development but can be used in production. It uses docker-compose to build the image and database from the source code instead of pulling them from docker hub.

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
## ENV

By Default the manager will take env variables from the database unless the override is set. See below.

### Variables

| Key                          | Required | Default          | Notes                                                                               |
|------------------------------|----------|------------------|-------------------------------------------------------------------------------------|
| APP_KEY                      | True     |                  |                                                                                     |
| APP_DEBUG                    | False    | DEBUG            |                                                                                     |
| APP_ENV                      | False    | local            |                                                                                     |
| APP_NAME                     | True     |                  |                                                                                     |
| APP_TAGLINE                  | True     |                  |                                                                                     |
| APP_URL                      | True     | http://localhost |                                                                                     |
| APP_EMAIL                    | True     |                  |                                                                                     |
| MAIL_DRIVER                  | False    | smtp             |                                                                                     |
| MAIL_HOST                    | False    |                  |                                                                                     |
| MAIL_PORT                    | False    |                  |                                                                                     |
| MAIL_USERNAME                | False    |                  |                                                                                     |
| MAIL_PASSWORD                | False    |                  |                                                                                     |
| MAIL_ENCRYPTION              | False    |                  |                                                                                     |
| DB_DATABASE                  | True     |                  |                                                                                     |
| DB_USERNAME                  | True     |                  |                                                                                     |
| DB_PASSWORD                  | True     |                  |                                                                                     |
| DB_HOST                      | True     |                  |                                                                                     |
| DB_SEED                      | False    | true             |                                                                                     |
| DB_CONNECTION                | False    | mysql            |                                                                                     |
| DB_PORT                      | False    | 3306             |                                                                                     |
| GOOGLE_ANALYTICS_TRACKING_ID | False    |                  | Google Tracking                                                                     |
| PAYPAL_USERNAME              | False    |                  |                                                                                     |
| PAYPAL_PASSWORD              | False    |                  |                                                                                     |
| PAYPAL_SIGNATURE             | False    |                  |                                                                                     |
| STRIPE_SECRET_KEY            | False    |                  |                                                                                     |
| STRIPE_PUBLIC_KEY            | False    |                  |                                                                                     |
| STEAM_API_KEY                | False    |                  | Used for Steam Login                                                                |
| CHALLONGE_API_KEY            | False    |                  | Used for Tournaments                                                                |
| FACEBOOK_APP_ID              | False    |                  | Experimental                                                                        |
| FACEBOOK_APP_SECRET          | False    |                  | Experimental                                                                        |
| LOG_FILES                    | False    | False            | If set to true, the App and Nginx will log to file                                  |
| ENABLE_HTTPS                 | False    | False            | If set to true, the App will redirect all requests to HTTPS                         |
| DB_MIGRATE                   | True     | True             | If set to true, the App will migrate the database on boot                           |
| ENV_OVERRIDE                 | False    | False            | If set to true, the App will take its API Keys from the ENV instead of the database |

### Override

By Default the Manager will take its API Keys from the Database and ignore the Env variables set. To change this set ```ENV_OVERRIDE=true``` and API Keys in the database will be ignored if it set. NOTE: This will not affect the API Keys page in the admin. This will still show the API Keys in the database. If an env variable is ommited it will refer to the database.

## HTTPS

To enable HTTPS set ```ENABLE_HTTPS=true```. If you wish to use your own certs, copy them to ```resources/certs``` or mount in the certs to the ```/etc/nginx/certs``` directory on the container. 

Note: Only set HTTPS to true if you are doing SSL Termination within the app. If you are doing SSL Termination within an external loadbalancer (EG Traefik) set ```ENABLE_HTTPS=false```.

### Caveats

- You must rename the certs to ```eventula_manager.crt``` and ```eventula_manager.key```.

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
