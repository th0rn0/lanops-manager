
Games
==================================================

Basic
-----
You can create games which will be available in your event, your tournaments and your matchmaking.
You are then able to add gameservers for each game and for a few games you can show serverstats / remote control the servers from eventula or even automate your matchmaking or tournament.

Add Game
.........
Lets create a game! go to your Admin Panel to the ``Games`` Section and you can add your game in the ``Add Game`` area.
To use the basic features like manual tournaments you have to fill in a name and you can add a description, a version if you want to.
Its recommended to add a ``Thumbnail Image `` and a ``Header Image``, they will be shown on the tournaments / matches.


.. image:: ../../images/games_add_01.png
   :scale: 50 %
   :alt: eventula event deletion
   :align: center

If you want to enable the possibilities to show the status of the later created gameservers on either the events page or the public page or you want to remotly control the gamservers via eventula you have to select a ``Game Commandhandler``

The currently available Commandhandlers are:

- SourceQuery GoldSource (supported games: full support for all HL1/HL2 games and mods )
- SourceQuery Source (supported games: CS:GO, Minecraft only commands | no status support)
- Maniaplanet XRPC (supported games: Trackmania nations, and all new maniaplanet games )

.. image:: ../../images/games_add_02.png
   :scale: 50 %
   :alt: eventula event deletion
   :align: center

If you want to enable the possibilities to manage fully automated tournaments or fully automated matchmaking you have to set ``Game Match Api handler`` as well as the corresponding ``Game Commandhandler``. 

The currently available APIhandlers are: 

- Get5 (supported games: CS:GO with the `Get5 Plugin from Splewis <https://github.com/splewis/get5>`_ and the `get5_eventula_apistats plugin <https://github.com/Lan2Play/get5_eventula_apistats>`_)

.. image:: ../../images/games_add_03.png
   :scale: 50 %
   :alt: eventula event deletion
   :align: center

For a fully working example of the automated Tournament / Matchmaking take a look on the ``Tutorials`` section of the documentation.





Press ``Submit`` to add the Category.

You will then be redirected to the detailed / editing /upload view. 


Gameservers
-----------

GameCommands
------------


GameCommandParameters
---------------------
