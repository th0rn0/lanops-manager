# LanOps Event Management Platform

From the original Eventula Management Platform comes the next iteration of the Lan Management Platform. Built For LanOps. 

I originally built the eventula manager as a fully whitelabelled solution. The original build had a central hub to deploy these fully bespoke and white labelled Event Management Solutions as well as a searchable google map. Unfornately through time I've not been able to keep up with its maintainence. However some wonderful people have decided to fork it and have a go at her themselves! If you are wanting a fully white labelled event management system

Built with Vodka and Hatred ontop of Laravel. Full Docker stack included.

## Installation

### Prerequisites

- Docker v24+
- Docker compose v2.21+
- Make (optional but makes life a little easier)

### Steps

- ```cp src/.env.example src/.env```
- Fill that sucker in
- ```make build-init```
- ```docker compose up -d```


### TODO

- upgrade NPM - using node8!!!
- re do frontend
- move images into app/resources and have gulp pull them in
- move all assets in public into app/resources and have gulp sort them
    - Maybe move them into storage?
    - Maybe move away from gulp?
- Re-evaluate what is needed from NPM
- Storage permissions need sorting
- This fucking README.md


##### Features

- Event Management
  - Signup/Info Pages
  - Tickets
  - Signups/Gifts/Staff/Refunds
  - Timetables
  - Seating
- Ticket Management
- Seating Plans
- Venue Management
- User Management
- Timetable Management
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
- Voting and Poll Management
- News Management


## ENV

### Variables

| Key                          | Required | Default          | Notes                                                                               |
|------------------------------|----------|------------------|-------------------------------------------------------------------------------------|
| APP_KEY                      | True     |                  |                                                                                     |
| APP_DEBUG                    | False    | DEBUG            |                                                                                     |
| APP_ENV                      | False    | local            |                                                                                     |
| APP_NAME                     | True     |                  |                                                                                     |
| APP_TAGLINE                  | True     |                  |                                                                                     |
| APP_URL                      | True     | http://localhost |                                                                                     |
| DB_DATABASE                  | True     |                  |                                                                                     |
| DB_USERNAME                  | True     |                  |                                                                                     |
| DB_PASSWORD                  | True     |                  |                                                                                     |
| DB_HOST                      | True     |                  |                                                                                     |
| DB_CONNECTION                | False    | mysql            |                                                                                     |
| DB_PORT                      | False    | 3306             |                                                                                     |
| PAYPAL_USERNAME              | False    |                  |                                                                                     |
| PAYPAL_PASSWORD              | False    |                  |                                                                                     |
| PAYPAL_SIGNATURE             | False    |                  |                                                                                     |
| STRIPE_SECRET_KEY            | False    |                  |                                                                                     |
| STRIPE_PUBLIC_KEY            | False    |                  |                                                                                     |
| STEAM_API_KEY                | False    |                  | Used for Steam Login                                                                |
| LOG_FILES                    | False    | False            | If set to true, the App and Nginx will log to file                                  |
