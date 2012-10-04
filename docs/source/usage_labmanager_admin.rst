Usage: LabManager administrator
===============================

The LabManager administrator can do three different things: administrating which
remote laboratories are available from it, administrating which LMSs can access
it, and assigning remote laboratories to each LMS.

RLMS Administration
~~~~~~~~~~~~~~~~~~~

In the LabManager, they will configure the connection of the LabManager with the
different RLMSs. In the example, they will add the WebLab-Deusto deployed in
Deusto, as seen on the following figure:

.. image:: /_static/labmanager_rlms.png
   :width: 500px
   :align: center

There, the LabManager can select which laboratories will be available. This
means that with the provided credentials, the RLMS has permissions to use all
those laboratories, and therefore it will be able to share them with the LMSs.

.. image:: /_static/labmanager_externals.png
   :width: 500px
   :align: center

LMS Administration
~~~~~~~~~~~~~~~~~~

The LabManager administrator can also add LMSs so they can use this LabManager.
In this case, there is a single LMS to be managed, but in certain scenarios one
institution may have different LMSs in different courses. So as to add it, two
pairs of credentials are required: one to identify the LabManager in the LMS,
and other to identify the LMS in the LabManager. These credentials are not the
username and password of users of the LabManager (i.e. LabManager
administrators) neither of the LMS (i.e. students), but only a pair of login and
password to identify one system in the other.

.. image:: /_static/labmanager_addlms.png
   :width: 500px
   :align: center

The URL to the laboratory will be a URL pointing to the listing service. For
instance, in Moodle, it will point to something like:

   http://localhost/lms/moodle/2.3/blocks/lms4labs/lms/list.php

The system will store the data and will alert if it could reach the service and
if the credentials were correct. If you are installing the LabManager before
configuring the LMS plug-in, it is normal that it fails, but after configuring
the LMS remember to come back to this step to ensure that it is working.

From this point, the LabManager administrator must assign permissions to each
LMS. So as to do this, he must go to each particular laboratory (in the RLMS
panel) and grant access to the particular LMS.

.. image:: /_static/labmanager_grant_permission1.png
   :width: 500px
   :align: center

The system will require a unique identifier for that laboratory in that LMS.
This identifier is a string, that later the SCORM objects will use to say 'I
want to use this laboratory'. The other arguments rely on the particular RLMS.

.. image:: /_static/labmanager_grant_permission2.png
   :width: 500px
   :align: center

