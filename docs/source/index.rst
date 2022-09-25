.. Eventula Event Manager documentation master file, created by
   sphinx-quickstart on Tue Dec 22 03:54:45 2020.
   You can adapt this file completely to your liking, but it should at least
   contain the root `toctree` directive.

Welcome to Eventula Event Manager's documentation!
==================================================

    .. warning::

        This fork of eventula manager is not 100% update compatible to the current upstream. We have intended to merge it someday, but currently there are to many changes and there is not enough time to do so. If you migrate from the upstream to our fork, please backup youre database and storage before doing so and please report any issues you encounter with the upgrade. Also this documentation is not fully up to date and not finished, so if you find something that is not right, consider contributing to it or open an issue. 


The Eventula Event Manager / ECO System is a fully featured White labeled Event Management system. The only prerequisite is docker and/or docker-compose. Everything is self contained. The purpose of this application is to unify Event Management (Venue, attendees, seating), Ticket Sales, Tournament Management, Shop Management and Credit Management.

https://eventula.com

If you are using this please consider signing up to eventula for event mapping.



.. toctree::
   :maxdepth: 1
   :caption: Contents:

   contribution
   license


.. toctree::
   :caption: User Documentation
   :maxdepth: 2

   user/getting_started
   user/events/basic
   user/news
   user/usermanagement
   user/polls
   user/venues
   user/gallery
   user/help
   user/games/basic
   user/matchmaking
   user/purchases
   user/settings/basic
   user/mailing
   user/credit
   user/shop
   user/tutorials/basic

   
.. toctree::
   :caption: Developer Documentation
   :maxdepth: 1

   dev/getting_started
   dev/folderstructure
   dev/games/gamecommandhandler
   dev/games/gamematchapihandler
   
.. toctree::
   :caption: API Documentation
   :maxdepth: 1

   api/events
   api/matchmaking
   api/tournaments
   



Indices and tables
==================

* :ref:`genindex`
* :ref:`modindex`
* :ref:`search`
