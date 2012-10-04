.. _toctree-directive:

Installation
============

Within the lms4labs project, two main components need to be installed:
 * The LabManager, which is the software system 
 * The LMS plug-in (see the Usage section of Moodle for Moodle)

Cardinality:
 * There should be a LabManager representing each university or secondary
   school.
 * While uncommon, there could be more than one LMS for each LabManager
 * Each LabManager will support more than one RLMS
 * Each RLMS must be prepared to support more than one LabManager

LabManager installation
-----------------------

The LabManager has been implemented in `Python <http://www.python.org>`_, so the
first dependency to be installed is Python itself. In OS X and Linux, you
usually have it already installed. In Windows, you should go to the `Python
website <http://www.python.org>`_ and download the latest 2.x version (e.g.
2.7).

The LabManager also uses the `Flask microframework <http://flask.pocoo.org>`_,
and `sqlalchemy <http://www.sqlalchemy.org>`_ to wrap the database, being able
to use `multiple systems
<http://docs.sqlalchemy.org/en/rel_0_7/core/engines.html#supported-databases>`_
but we have only tested `MySQL <http://www.mysql.com>`_ and `sqlite
<http://www.sqlite.org/>`_. If you want to use MySQL, you'll have to install it
(in Windows, you may download it from the `MySQL website
<http://www.mysql.com>`_ or use the `XAMPP package
<http://www.apachefriends.org/en/xampp.html>`_, which also comes with Apache; in
Linux systems you can install it using your package manager -e.g. sudo apt-get
install mysql-server -).

So at this point, the following software packages are assumed:
 * Python 2.x (do not use Python 3.x; it is not yet supported)
 * MySQL (unless you prefer using sqlite)

In order to deploy it, some Python packaging notions are required, explained in
the first section. Then, the deployment itself is detailed for Microsoft Windows
and Linux systems. Finally, notes on the development are described.

Notes on pip and virtualenv
```````````````````````````

Python open source packages are usually uploaded to `PyPI
<http://pypi.python.org/pypi>`_ (commonly refered to as the cheese shop), and
tools such as `easy_install <pypi.python.org/pypi/setuptools>`_ and `pip
<http://pypi.python.org/pypi/pip>`_ make it easy to query, search and install
those packages. During this document we'll use pip, which is indeed a
replacement for easy_install.

In order to install pip, we'll use our distribution package manager in Linux
systems. For instance, in Ubuntu we can simply run::

    $ sudo apt-get install python-pip

On Windows systems, the process is slightly longer since we have to install
first setuptools. So download the `distribute_setup.py file
<http://python-distribute.org/distribute_setup.py>`_ and run it, and then place
the Python installation Script directory to the PATH environment variable. So
append the following to the PATH variable::

    ;C:\Python27\Scripts

Once setuptools is installed, type the following on CMD::

    easy_install pip

From this point, you'll have pip running in your Windows system.

When installing Python packages, by default they are all installed in a
system-wide location. However, for different projects we might be interested in
installing different versions of the same libraries. In order to avoid
conflicts, and manage the installed libraries in an easy way, the `virtualenv
<http://pypi.python.org/pypi/virtualenv/>`_ project was created.

With virtualenv, it is possible to create a virtual environment in a directory,
where one can install a certain set of packages with particular versions. All
those versions are managed in that particular directory, so you can later delete
it, upgrade only that one, and especially, create other environments for other
applications.

The way to use it is very simple. First you need to install virtualenv, using
pip::

    $ pip install virtualenv

Or using your package manager in Linux systems::

    $ sudo apt-get install python-virtualenv

And then, you can create an environment by running::
    
    $ virtualenv --no-site-packages env1
    New python executable in env1/bin/python
    Installing distribute........done.
    Installing pip...............done.

At this point, the environment has been created, but it is not yet being used.
In order to start using this environment, we have to do the following on Linux
and OS X::

    $ . env1/bin/activate

Or the following on Windows::

    > env1\scripts\activate

From this point, you'll see that in the prompt of your shell there is an
indicator such as  *(env1)*. At this point, we will be working with that
environment. So if we install Flask::

    $ pip install Flask

It will be installed in that isolated virtual environment. We can test it by
running Python and checking that Flask is actually installed::

    $ python
    Python 2.7.2+ (default, Oct  4 2011, 20:06:09) 
    [GCC 4.6.1] on linux2
    Type "help", "copyright", "credits" or "license" for more information.
    >>> import flask
    >>> 

If we go out of the Python shell (Ctrl + D / Ctrl + Z), and we deactivate the
environment::

    $ deactivate

Or we simply open a new terminal, then we'll see that we are not using that
environment anymore::

    $ python
    Python 2.7.2+ (default, Oct  4 2011, 20:06:09) 
    [GCC 4.6.1] on linux2
    Type "help", "copyright", "credits" or "license" for more information.
    >>> import flask
    Traceback (most recent call last):
      File "<stdin>", line 1, in <module>
    ImportError: No module named flask
    >>> 
   
To start using it again, we only have to call or import the activate script
again.

Notes on WSGI
`````````````

WSGI stands for `Web Server Gateway Interface
<http://en.wikipedia.org/wiki/Web_Server_Gateway_Interface>`_, which is an
interface that different Python web application providers will use and they can
automatically be integrated in other web servers. For instance, there is a
`WSGI module <http://code.google.com/p/modwsgi/>`_ for Apache or for `nginx
<http://wiki.nginx.org/NgxWSGIModule>`_, so any application developed in a
WSGI-compliant framework (such as Flask) can be deployed in those web servers.
There is plenty of information and links about the support in the `WSGI official
site <http://www.wsgi.org/en/latest/servers.html>`_.

Lms4labs has been developed using Flask, which is WSGI-compliant microframework.
Therefore, a WSGI-compliant server is required. There are two approaches:

 1) Use Apache, nginx, `IIS <http://code.google.com/p/isapi-wsgi/>`_ or any other
    well known web server. There is plenty of documentation on how to deploy
    Flask applications on those environments in the `Flask documentation
    <http://flask.pocoo.org/docs/>`_.

 2) Use a Python WSGI-compliant web server such as `cherrypy
    <http://www.cherrypy.org/>`_. The advantage of this is that it does not
    require you to deploy any additional plug-ins to the web server you are
    already using, and then you can use that server directly or the proxy module
    of the web server to manage the connections. This approach might be slower,
    but it is useful to test the system and even to use it in production with a
    small number of students.

This document covers both approaches, but it is important to understand the
benefits and drawbacks of each one.

Deploying Lms4labs
``````````````````

In this section, it is assumed that you already have installed pip and
virtualenv, and that you have notions of how you want to deploy the Lms4labs
application.

First of all, download the source code of the lms4labs project and go to the
labmanager code::

    $ git clone https://github.com/porduna/lms4labs/
    $ cd lms4labs/labmanager

Then, create an environment called *env* in the same directory where the
labmanager is installed, and activate it::

    $ virtualenv --no-site-packages env
    $ . env/bin/activate
    (or, on Windows)
    $ . env\scripts\activate

Install all the requirements. They are detailed requirements.txt file, so you
can install them all by running::

    $ pip install -r requirements.txt

At this point, everything is ready to be deployed. First, we should add the
configuration file. A sample one is distributed, so you can copy it::

    $ cp config.py.dist config.py

And modify it so as to fit your local data. If the engine is sqlite, you don't
need to worry about the connection DB configuration (username, password,
hostname, etc.). If you are using MySQL, you don't need to create the user and
the database by your own, since that is managed by the deployment script itself.
Just check that you're fine with the credentials you're going to establish in
the config.py file. Then you can create the database by running::

    $ python deploy.py -cdu

Finally, you can test it by running::

    $ python run.py

If you open `<http://localhost:5000/>`_ with your web browser, you should see
the system up and running in development mode. You'll be able to use the
username  *admin* and the password *password*.

Development
```````````

The development mode is a Flask mode used during the application development. By
running::

    $ python run.py

You are using that mode. It is a risky mode since users might be able to execute
random code in the server, so use it only while developing or testing a
particular condition.

While using the development mode, the application will be automatically reloaded
every time you modify any code file, and if an exception is raised, you'll be
able to see the complete trace and even evaluate conditions through the web
browser by writing Python code in any stack level. To see further information,
please refer to the `official flask documentation
<http://flask.pocoo.org/docs/quickstart/#debug-mode>`_.

Production
``````````

In order to run the system in production, there are two ways, as previously
detailed. The easiest mode is to rely on a Python web server such as `cherrypy
<http://cherrypy.org>`_. A very simple example is provided in the run_cherry.py
script, which basically does the following::

    from cherrypy import wsgiserver
    from labmanager import app

    PORT = 8080
    server = wsgiserver.CherryPyWSGIServer(('0.0.0.0', PORT), app)
    server.start()

This code is enough for deploying a threaded HTTP server. So as to run it, you
must run::

    $ python run_cherry.py

If you want to work with this server behind an Apache server, you can still use
the Apache `mod_proxy <http://httpd.apache.org/docs/2.2/mod/mod_proxy.html>`_
module, which comes by default with Apache. Refer to the Apache documentation
for details, but this is an example of configuration (once the module has been
enabled)::

    ProxyVia On
    ProxyPass        /lms4labs http://localhost:8080/lms4labs
    ProxyPassReverse /lms4labs http://localhost:8080/lms4labs

The other approach is using WSGI in the web server. Refer to the `Flask
documentation on how to deploy it <http://flask.pocoo.org/docs/deploying/>`_. In
the particular case of Apache, the documentation on `how WSGI works on Apache
<http://code.google.com/p/modwsgi/>`_ is also very good. 

As a summary for the deployment on Apache: first, download mod_wsgi. In Linux
systems, it may be available in the package repositories (e.g. in Ubuntu, you
may install the *libapache2-mod-wsgi* package). In Windows, the process is
documented `here
<http://code.google.com/p/modwsgi/wiki/InstallationOnWindows>`_. Once mod_wsgi
is installed in Apache, the following configuration may work::

    WSGIDaemonProcess labmanager user=weblab group=weblab threads=5 python-path=/PATH/TO/ENV/lib/pythonVERSION/site-packages/
    WSGIScriptAlias /lms4labs /PATH/TO/lms4labs/labmanager/run_wsgi.wsgi
    WSGIRestrictStdout Off
    WSGIPassAuthorization On

    <Directory /PATH/TO/lms4labs/labmanager/>
        WSGIProcessGroup labmanager
        WSGIApplicationGroup %{GLOBAL}
        Order deny,allow
        Allow from all
    </Directory>

Being /PATH/TO/lms4labs/ the lms4labs root project. Additionally, you will need
to modify the *run_wsgi.wsgi* script to change the project directory.

