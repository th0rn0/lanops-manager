
Getting Started
==================================================

    .. warning::

        This fork of eventula manager is not 100% update compatible to the current upstream. We have intended to merge it someday, but currently there are to many changes and there is not enough time to do so. If you migrate from the upstream to our fork, please backup youre database and storage before doing so and please report any issues you encounter with the upgrade. Also this documentation is not fully up to date and not finished, so if you find something that is not right, consider contributing to it or open an issue. 

We are glad that you want to try or use the eventula event manager.
You have 2 different ways to distribute your instance of eventula:

Eventula.com Hosted
--------------------
In the future there will be the option again to get a productive instance of eventula running as a subscription service on https://eventula.com/ .
Currently the subscription service is not available due the small numbers of events occuring during covid.


Eventula self hosted - Installation
------------------------------------

There are 3 different ways to install the eventula event Manager:

- Installation with Docker and your own Database
- Installation with docker-compose bundled with a database instance
- Installation with the Makefile



    .. warning::

        Currently the manager is not publicly available on Docker Hub, therfore you should use the installation variant using make or build the manager docker image yourself with our sourcecode.

Prerequisites
..............

- Docker v17
- Docker-compose v1.18
- API Keys for one of the Payment Providers
- for development / the make installation way you need ``make`` installed


Installation with Docker and your own Database
................................................

This method is intended to be run as just a image with your own database. Persistant storage is required for the ``storage/`` directory.
You minimally have to set the ``APP_URL``, ``DB_HOST``, ``DB_DATABASE``, ``DB_USERNAME``, ``DB_PASSWORD`` to get it initially running.
For productive use you have to set your ``APP_KEY`` (Follow Post-Docker Below) and should set ``APP_DEBUG`` to ``false``, ``APP_ENV`` to ``production`` and you should take care of HTTPS (see HTTPS or Running behind a reverse proxy
) 


.. code-block:: bash

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
        -e FORCE_APP_HTTPS=false \
        -e SESSION_SECURE_COOKIE=false \
        -e DB_CONNECTION=mysql \
        -e DB_MIGRATE=true \
        -e DB_SEED=true \
        -p 80:80 \
        -p 443:443 \
        -v eventula_manager_storage:/web/html/storage/ \
        --name eventula_manager_app \
        eventula/manager:latest



Follow Post-Docker Below



Installation with docker-compose bundled with a database instance
..................................................................

This method is intended to be run with docker-compose. It will create a full stack including database, load balancer & SSL Encryption.

.. code-block:: yaml

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
        image: mysql:5.7
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





Follow Post-Docker Below




Post-Docker
..............
When running for the first time you'll be a new ``APP_KEY`` will be generated. Keep this safe!. You'll need to add it to the env variables (EG ``-e APP_KEY=someRandomKey`` ) otherwise it will regenerate the ``APP_KEY`` on each reboot.

Once running and the database has migrated go to your app and you will be greated with the install page (see Installation page below for details)





Installation with the Makefile
...............................
This method is intended for development but can be used in production. It uses docker-compose to build the image and database from the source code instead of pulling them from docker hub. You need the whole source code for running this! If you plan to participate in the development of eventula, its worth checking out the Makefile. There are many useful commands implemented that you can use to make your life easier (see developer documentation).

1. Setup & Configuration
^^^^^^^^^^^^^^^^^^^^^^^^^

Run ``make env-file`` to create a ``.env`` file in the ``src``  directory. Then modify it as according to your preferences. KEEP THIS SAFE & SECURE! This file holds the credentials used within the app. If any would be hacker was to get hold of this file they have access to everything! 

2. Run
^^^^^^^^^^^^^^^^^^^^^^^^^
Run the stack detached. This is re attachable. 

``make``

Interactive
^^^^^^^^^^^^^^^^^^^^^^^^^
Run the stack in the foreground. Once exited the stack will stop.

``make interactive``

Migrate & Seed Database
^^^^^^^^^^^^^^^^^^^^^^^^^

Run Database migrations and populate.

``make database-migrate``
``make database-seed``

Stop
^^^^^^^^^^^^^^^^^^^^^^^^^

Stop the stack.

``make stop``



HTTPS
..................................................................

To enable HTTPS set ``ENABLE_HTTPS=true``. If you wish to use your own certs, copy them to ``resources/certs`` or mount in the certs to the ``/etc/nginx/certs`` directory on the container. 

Note: Only set HTTPS to true if you are doing SSL Termination within the app.

Caveats
^^^^^^^^^^^^^^^^^^^^^^^^^
- You must rename the certs to ```eventula_manager.crt``` and ```eventula_manager.key```.


Running behind a reverse proxy
..................................................................
If you want to run eventula with http and a reverse proxy in front which serves it to the web via https you have to enable set ``ENABLE_HTTPS=false`` and ``FORCE_APP_HTTPS=true`` in your env file / your docker-compose.yml . This will run the NGINX running eventula in http mode but it will force the https link schema for all the links in eventula.


Custom Timezone
..................................................................
If you have to run the Container for a different Timezone, you can specify it via ``TIMEZONE=Europe/Berlin`` in your env file / your docker-compose.yml . You have to set your specify a valid timezone name for Alpine linux.

Secret Managers
..................................................................
The Event Manager ships with a file reader for Env variables such as Passwords as API Keys for Secrets Managers such as Ranchers Secret Manager, EnvKey and Summon. To use it append ``_FILE`` to the Env variable and change the value to be the location of the secret file located on the container! It is recommended you mount a secrets directory into the container for example:

If we add the volume; ``resources/secrets/:/run/secrets`` and store our ``DB_PASSWORD`` in a file called ``DB_PASSWORD`` in ``resources/secrets/`` directory, we can set ``DB_PASSWORD_FILE=/run/secrets/DB_PASSWORD`` and the app will read the file and inject the password into the Environment Variable ``DB_PASSWORD``.

Caveats
^^^^^^^^^^^^^^^^^^^^^^^^^

- Only one entry per file
- File must only container value
- The file can be named anything, but it must be reflected in the ``_FILE`` env variable
- It will only work on the following Env Variables;

  - ``FACEBOOK_APP_ID``
  - ``FACEBOOK_APP_SECRET``
  - ``FACEBOOK_PIXEL_ID``
  - ``DB_DATABASE``
  - ``DB_USERNAME``
  - ``DB_PASSWORD``
  - ``MYSQL_DATABASE``
  - ``MYSQL_USER``
  - ``MYSQL_PASSWORD``
  - ``TIMEZONE``
  - ``ANALYTICS_TRACKING_ID``
  - ``PAYPAL_USERNAME``
  - ``PAYPAL_PASSWORD``
  - ``PAYPAL_SIGNATURE``
  - ``STEAM_API_KEY``
  - ``CHALLONGE_API_KEY``
  - ``APP_KEY``

Custom SCSS & Views
..................................................................
To load in custom SCSS (Sassy CSS) & Views (Blade) mount the following directories;

- ``path_to_scss:/web/html/resources/assets/sass``
- ``path_to_views:/web/html/resources/assets/views``

CSS must be in SCSS format.

To recompile CSS use the 'Recompile CSS' link located at http://localhost/admin/appearance (change localhost ofc with the URL of your eventula host)

You can also change base variables and ad your own in the appearance page (more in the settings documentation).


Installation Page
..................................................................
the last step before you can use the event manager is the installation page, it will pop up after the initial installation.

.. image:: ../images/Installation01.png
   :height: 1136px
   :width: 910px
   :scale: 50 %
   :alt: eventula installation page
   :align: center

You have to fill out all the input fields in Step 1 & Step 2 and at least the API credentials for one of the payment providers in Step 3 and finnaly click on Confirm to get redirected to your working Managers settings Page.
To get to your front page, klick on the page title at the top left. If you want to go back to the Admin area you can do this by opening the menue while clicking onto your username in the top right corner and the entry ``Admin``
If you want to know all about the settings, take a look into the settings documentation part. But you can follow step to step and we tell you if you have to take care of something in the settings part.

