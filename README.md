# Eventula Event Manager - ECO System

The Eventula Event Manager / ECO System is a fully featured White labeled Event Management system. The only prerequisite is `docker and/or docker-compose`. Everything is self contained. The purpose of this application is to unify Event Management (Venue, attendees, seating), Ticket Sales, Tournament Management, Shop Management and Credit Management.

https://eventula.com

You can find the full documentation on https://eventula.lan2play.de .

If you are using this please consider signing up to eventula for event mapping.

> **Warning**
> This fork of eventula manager is not 100% update compatible to the current upstream. We have intended to merge it someday, but currently there are to many changes and there is not enough time to do so. 

##### Home Page:

![Manager front page](https://raw.githubusercontent.com/Lan2Play/eventula-manager/master/resources/images/home-page.png)
##### Event Page:
![Event Page](https://raw.githubusercontent.com/Lan2Play/eventula-manager/master/resources/images/events-page.png)
##### Successful Payment Page
![Successful Payment](https://raw.githubusercontent.com/Lan2Play/eventula-manager/master/resources/images/success-payment-page.png)
##### Event Management
![Event Admin Page](https://raw.githubusercontent.com/Lan2Play/eventula-manager/master/resources/images/events-management-page.png)
##### Ticket Management
![Tickets Breakdown](https://raw.githubusercontent.com/Lan2Play/eventula-manager/master/resources/images/tickets-management-page.png)
##### Tournament Management
![Tournament Management](https://raw.githubusercontent.com/Lan2Play/eventula-manager/master/resources/images/tournaments-management-page.png)
##### Tournament Brakcets
![Tournament Brackets](https://raw.githubusercontent.com/Lan2Play/eventula-manager/master/resources/images/tournaments-brackets-page.png)

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

## Usage

Hop over to our [Documentation](https://eventula.lan2play.de/user/getting_started.html) to see how to run and use eventula.

##### To Do

- Refunds
- Unit Tests

## Sites that use the Lan Manager

- LanOps - https://lanops.co.uk
- TheLanProject - https://thelanproject.co.uk
- IronLan - https://ironlan.co.uk
- Lan2Play - https://lan2play.de


/user/getting_started.html