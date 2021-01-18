
Contribution Guide
==================================================

We are looking forward to every conbtirbution you possibly can bring in.

If you encounter serious errors while using eventula and you are not able to fix them, feel free to open issues on https://github.com/th0rn0/eventula-manager/issues

If you plan to add a feature to eventula, please open an issue as well so no one has to do work when someone already working on something.


Documentation
--------------
This documentation is written in restructured text and its build using spingx and the read the docs theme. The source can be found in our main repository in the ``docs/`` subfolder (https://github.com/th0rn0/eventula-manager/tree/master/src).

To build the documentation locally you have to install sphinx (https://www.sphinx-doc.org/en/master/usage/installation.html) and the read the docs theme (https://github.com/readthedocs/sphinx_rtd_theme#installation) and run

.. code-block:: bash

   make html


in the local copy of your ``docs/`` folder.

Feel free to PR corrections or expansions of the documentation at any time! 


Localisation
-------------
We try to implement the software and the documentation localable, but we currently can only do german and english translations, therefore any help is appreciated!

Documentation localisation
..........................
Todo!

Eventula localisation
.....................
You can find the localisation files in ``src/resources/lang/``. If you want to fix mistakes, you can find the files for every translated language in the corresponding subfolder.
If you want to add a whole language, copy the whole en folder and rename it to the Language code you want to add. The language files are Key - Value pair files, just edit the Value in there.

The localisations could be accessed in the PHP code with (example whoeweare from src/resources/lang/de/about.php):

.. code-block:: php

    __('about.whoeweare')

or within blade files (Views):

.. code-block:: php

    @lang('about.whoeweare')



Code
-----
If you want to get into coding for eventula, check out the developer documentation, there you can find an introduction into how to setup your development environment and some specific Parts of eventula where we would love to see adaption for more usecases.

Some things you should think of before starting out implementing new features:

- Can another feature thats already implemented be expanded? yes? then go for that instead of Building complete new stuff!
- Does the addition / change might affect other usecases than your own? Build your changes with legacy support in mind!
- Try to follow the coding Style which is used within eventula, just look around in our features to see which case is handled mostly in which manner
- Have i started an ssue to announce that im working on a feature/change to get thoughts from the other developers and to prevent incompatibillities
- Why is shouldn't join the eventula discord developer channel for discussion?  

Before you want to PR changes to master you should ask yourself some questions:

- Have i tried to update a running version from eventula with data to the one with my changes? Are the changes update proof?
- Have i implemented all strings with localised variables? See Localisation!
- Have i changed the documentation (at least the english on!) on the affected parts?
- Have i changed the readme.md on the affected parts?

