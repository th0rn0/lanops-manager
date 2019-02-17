# LanOps Lan Manager

The Lan Manager is a fully featured White labeled Event Management system. The only prerequisite is `docker & docker-compose`. Everything is self contained. The purpose of this application was to remove the need for websites like WIX, SquareSpace, EventBrite or bloated Wordpress plugins that charge a small fortune for use of their code base and/or services all the while keeping all of the IPs and rights to your event hosting and ticket sales. Coupled with this there was no decent fully fledged alternative to the likes of ALP (2004 baby!) that did everything we (LanOps) needed. There was a lot of software out there but there wasn't one unified application that tied all these services such as tournaments, ticket management & sales, event signup, server management all in a easily extendable OPEN SOURCE package.

Thus the LanOps Lan Manager was born!

https://lanops.co.uk

#### A Docker image is coming.

##### Home Page:

![Manager front page](https://i.imgur.com/IqKAK1h.png)
##### Event Page:
![Event Page](https://i.imgur.com/BAvJU2l.png)
##### Successful Payment Page
![Successful Payment](https://i.imgur.com/Qb2fyPw.png)
##### Event Management
![Event Admin Page](https://i.imgur.com/w9aD10o.png)
##### Ticket Management
![Tickets Breakdown](https://i.imgur.com/nM7lcnG.png)
##### Tournament Management
![Tournament Management](https://i.imgur.com/55zynWs.png)
##### Tournament Brakcets
![Tournament Brackets](https://i.imgur.com/lcSCq0s.png)

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
  - Supported via Challonge API
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
- Challonge API key
  - A verified challonge account is required - https://challonge.com/settings/developer
- Steam Developers API Key
  - Any Steam API key will do. It's best to create a new account though - https://steamcommunity.com/dev/apikey
- Google Analytics Tracking ID
  - Optional but highly recommended

## Usage

There are 3 ways to run the Lan Manager

### Docker

```docker run -it -d lanopsdev/manager:latest```

### Docker-compose

```
version: "3.4"
services:
  app:
    image: lanopsdev/manager:latest
    volumes:
      - $PWD/certs:/etc/nginx/certs
    environment:
      - asd=asd
    container_name: lan_manager_app
    ports:
      - 80:80
      - 443:443
  database:
    image: mysql:5.6
    volumes:
      - db:/var/lib/mysql
    env_file: $PWD/src/.env
    ports:
      - 3306:3306
    container_name: lan_manager_database
volumes:
  db:
    name: lan_manager_db
```

or with a load balancer /w letsEncrypt

```
version: "3.4"
services:
  app:
    image: lanopsdev/manager:latest
    volumes:
      - $PWD/certs:/etc/nginx/certs
    environment:
      - asd=asd
    container_name: lan_manager_app
    ports:
      - 80:80
      - 443:443
  database:
    image: mysql:5.6
    volumes:
      - db:/var/lib/mysql
    env_file: $PWD/src/.env
    ports:
      - 3306:3306
    container_name: lan_manager_database
volumes:
  db:
    name: lan_manager_db
```

### Makefile

This method is intended for development but can be used in production. It uses docker-compose to build the image and database from the source code instead of pulling them from docker hub.

#### 1. Setup & Configuration

Run ```make env-file``` to create a ```.env``` file in the ```src```  directory. Then modify it as according to your preferences. KEEP THIS SAFE & SECURE! This file holds the credentials used within the app. If any would be hacker was to get hold of this file they have access to everything! 

#### 2. Installation

##### First Time Build

To run a clean build run the command below. This will also generate self signed Certificates in the ```resources/certs``` directory.
```
make app-build-clean
```

##### Build Dependencies (Optional)

Install dependencies. This is run when ```app-build-clean``` is run.

```
make app-build-dep
```

##### Build Development App & Dependencies (Optional)

Install dependencies for Development

```
make app-build-dep-dev
```

#### 3. Run

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

To enable HTTPS set ```ENABLE_HTTPS=true```. If you wish to use your own certs, copy them to ```resources/certs```

## Contributors

- Th0rn0 - https://github.com/th0rn0
- 1nvert - https://github.com/richardmountain

##### To Do

- Refunds
- Twitch Integration
- Add more payment Gateways
- Unit Tests
- Push to Docker Hub

## Sites that use the Lan Manager

- LanOps - https://lanops.co.uk
- EngLan - https://englan.co.uk
