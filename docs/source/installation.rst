Installation
============

Within the lms4labs project, two main components need to be installed:
 * The LabManager, which is the software system 
 * The LMS plug-in

Cardinality:
 * There should be a LabManager representing each university or secondary
   school.
 * While uncommon, there could be more than one LMS for each LabManager
 * Each LabManager will support more than one RLMS
 * Each RLMS must be prepared to support more than one LabManager

LabManager installation
-----------------------

The LabManager has been implemented in `Python <http://www.python.org>`_ using
the `Flask microframework <http://flask.pocoo.org>`_, and `sqlalchemy
<http://www.sqlalchemy.org>`_ to wrap the database, being able to use
`multiple systems
<http://docs.sqlalchemy.org/en/rel_0_7/core/engines.html#supported-databases>`_
but we have only tested `MySQL <http://www.mysql.com>`_ and `sqlite
<http://www.sqlite.org/>`_.

In order to deploy it, some Python packaging notions are required, explained in
the first section. Then, the deployment itself is detailed for Microsoft Windows
and Linux systems. Finally, notes on the development are described.

pip and virtualenv
``````````````````

adfasdfa

WSGI
````

adsfas

Development
```````````

asdfad

Blah blah some code here::

   print "hello world"

Installation on LMSs
--------------------

Blah blah

Installation on Moodle
``````````````````````

blah blah blah
