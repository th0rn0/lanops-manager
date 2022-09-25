
Contribution Guide
==================================================

We are looking forward to every conbtirbution you possibly can bring in.

If you encounter serious errors while using eventula and you are not able to fix them, feel free to open issues on https://github.com/Lan2Play/eventula-manager/issues

If you plan to add a feature to eventula, please open an issue as well so no one has to do work when someone already working on something.


Documentation
--------------
This documentation is written in restructured text and its build using sphinx and the read the docs theme. The source can be found in our main repository in the ``docs/`` subfolder (https://github.com/Lan2Play/eventula-manager/tree/master/src).
Feel free to PR corrections or expansions of the documentation at any time! 

To build the documentation locally to the ``docs/build`` subfolder you have two options:

- Building with docker and the make file (recommended)
- Building with docker
- Building manually with the sphinx make file

Building with docker and the make file
.......................................

Windows
'''''''
Prereqirements: 

- Docker for Windows with wsl2 backend (https://docs.docker.com/docker-for-windows/wsl/ Follow the Prerequisites, the Download and the Install part!)


 .. warning::

        If you are using git, consider cloning the repository within your wsl distro instead of with git for windows to get around line ending problems!

To build the documentation just enter yor wsl2 distribution and follow the Linux part below!


Linux
'''''''
Prereqirements: 

- Docker (https://docs.docker.com/engine/install)
- Make (should be available for nearly every linux distro in the corresponding packagemanager)

In order to build the documentation run

.. code-block:: bash

   make docs-html


Building with docker
.......................................

Windows
'''''''
Prereqirements: 

- Docker for Windows with wsl2 backend (https://docs.docker.com/docker-for-windows/wsl/ Follow the Prerequisites, the Download and the Install part!)


 .. warning::

        If you are using git, consider cloning the repository within your wsl distro instead of with git for windows to get around line ending problems!

To build the documentation just enter yor wsl2 distribution and follow the Linux part below!


Linux
'''''''
Prereqirements: 

- Docker (https://docs.docker.com/engine/install)

In order to build the documentation run the following command inside the root of the cloned repository

.. code-block:: bash

   docker run --rm -v $PWD/docs:/docs lan2play/docker-sphinxbuild:latest

Building manually with the sphinx make file
............................................

Windows
'''''''
Prereqirements: 

- python 3 (https://docs.python.org/3/using/index.html) with pip
- sphinx (https://www.sphinx-doc.org/en/master/usage/installation.html) 
- the read the docs theme (https://github.com/readthedocs/sphinx_rtd_theme#installation)

open a cmd or powershell and change your folder to the ``docs/`` subfolder and run

CMD

.. code-block:: cmd

   make.bat html

Psh

.. code-block:: powershell

   ./make.bat html


Linux
'''''''
Prereqirements: 

- python 3 (https://docs.python.org/3/using/index.html) with pip
- sphinx (https://www.sphinx-doc.org/en/master/usage/installation.html) 
- the read the docs theme (https://github.com/readthedocs/sphinx_rtd_theme#installation)
- Make (should be available for nearly every linux distro in the corresponding packagemanager)

open your favorite shell and change your folder to the ``docs/`` subfolder and run

.. code-block:: bash

   make html


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
If you want to get into coding for eventula, check out the developer documentation, there you can find an introduction into how to setup your development environment and some specific parts of eventula where we would love to see adaption for more usecases.

Some things you should think of before starting out implementing new features:

- Can another feature thats already implemented be expanded? yes? then go for that instead of Building complete new stuff!
- Does the addition / change might affect other usecases than your own? Build your changes with legacy support in mind!
- Try to follow the coding Style which is used within eventula, just look around in our features to see which case is handled mostly in which manner
- Have i started an issue to announce that im working on a feature/change to get thoughts from the other developers and to prevent incompatibillities? No? Go for it! :)
- Why i shouldn't join the eventula discord developer channel for discussion?  

Before you want to PR changes to master you should ask yourself some questions:

- Have i tried to update a running version from eventula with data to the one with my changes? Are the changes update proof?
- Have i implemented all strings with localised variables? See Localisation!
- Have i changed the documentation (at least the english on!) on the affected parts?
- Have i changed the readme.md on the affected parts?

